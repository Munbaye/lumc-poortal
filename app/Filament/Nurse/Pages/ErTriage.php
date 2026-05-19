<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\Vital;
use App\Models\ActivityLog;
use App\Services\PatientSearchService;
use App\Models\UnknownPatientSequence;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ErTriage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'ER Triage';
    protected static ?string $title           = 'ER Triage';
    protected static ?string $navigationGroup = 'Emergency';
    protected static ?int    $navigationSort  = 1;
    protected static string  $view            = 'filament.nurse.pages.er-triage';

    // Search state — mirrors clerk RegisterPatient flow
    public string  $searchFamilyName  = '';
    public string  $searchFirstName   = '';
    public ?string $searchSex         = null;
    public ?string $searchBirthday    = null;
    public ?int    $searchAge         = null;
    public array   $searchResults     = [];
    public bool    $hasSearched       = false;
    public ?int    $selectedPatientId = null;
    public bool    $showTriageForm    = false;  // true after search confirms no match or patient selected
    public bool    $confirmNoMatch    = false;  // nurse checks "no existing record"
    public bool    $isUnknownMode     = false;  // unknown/unidentified patient

    // Patient
    public array $patientData = [
        'family_name' => '', 'first_name' => '', 'middle_name' => '',
        'birthday' => null, 'age' => null, 'sex' => null,
        'address' => '', 'contact_number' => '',
        'brought_by' => '', 'condition_on_arrival' => 'Ambulatory',
    ];

    // Triage assessment
    public string $triageNurseOnDuty   = '';
    public string $chiefComplaint      = '';
    public string $complaintDuration   = '< 1 day';
    public string $consciousness       = 'alert';
    public string $breathing           = 'normal';
    public string $mobility            = 'walking';
    public string $triageCategory      = '';
    public bool   $categoryManuallySet = false;
    public string $triageNotes         = '';
    public string $assignedDepartment  = 'Emergency Room (ER)';

    // Vitals
    public ?float  $temperature     = null;
    public string  $temperatureSite = 'Axilla';
    public ?int    $pulseRate       = null;
    public ?int    $respiratoryRate = null;
    public ?string $bloodPressure   = null;
    public ?int    $o2Saturation    = null;
    public ?string $painScale       = null;
    public ?float  $weightKg        = null;
    public ?float  $heightCm        = null;
    public string  $vitalNotes      = '';

    // Doctor assign tab
    public string $assignSearch = '';

    public const TRIAGE_CATEGORIES = [
        'red'    => ['label' => 'Immediate',   'color' => '#dc2626', 'badge' => 'Immediate — life-threatening'],
        'orange' => ['label' => 'Very Urgent', 'color' => '#ea580c', 'badge' => 'Very Urgent'],
        'yellow' => ['label' => 'Urgent',      'color' => '#d97706', 'badge' => 'Urgent'],
        'green'  => ['label' => 'Minor',       'color' => '#16a34a', 'badge' => 'Minor / Non-urgent'],
        'black'  => ['label' => 'Expectant',   'color' => '#111827', 'badge' => 'Dead / Dying / Expectant'],
    ];

    public function mount(): void
    {
        $this->triageNurseOnDuty = auth()->user()->full_name;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Visit::where('status', 'triage')->where('visit_type', 'ER')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string { return 'danger'; }

    // Auto-triage — only runs when nurse has NOT manually set the category
    public function runAutoTriage(): void
    {
        if ($this->categoryManuallySet) return;
        $c = strtolower($this->chiefComplaint);
        $suggested = 'green';
        if ($this->consciousness === 'unconscious' || $this->breathing === 'severe') {
            $suggested = 'red';
        } elseif ($this->breathing === 'difficulty'
            || str_contains($c, 'chest pain') || str_contains($c, 'stroke') || str_contains($c, 'seizure')) {
            $suggested = 'orange';
        } elseif (str_contains($c, 'fever') || str_contains($c, 'abdominal')
            || str_contains($c, 'fracture') || $this->consciousness === 'drowsy') {
            $suggested = 'yellow';
        }
        $this->triageCategory = $suggested;
    }

    public function updatedConsciousness(): void  { $this->runAutoTriage(); }
    public function updatedBreathing(): void      { $this->runAutoTriage(); }
    public function updatedChiefComplaint(): void { $this->runAutoTriage(); }
    public function updatedTriageCategory(): void { $this->categoryManuallySet = true; }

    public function resetAutoTriage(): void
    {
        $this->categoryManuallySet = false;
        $this->runAutoTriage();
    }

    // Search
    public function updatedSearchFamilyName(): void { $this->runSearch(); }
    public function updatedSearchFirstName(): void  { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }
    public function updatedSearchSex(): void        { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }
    public function updatedSearchBirthday(): void
    {
        if ($this->searchBirthday) $this->searchAge = (int) \Carbon\Carbon::parse($this->searchBirthday)->diffInYears(now());
        if (strlen($this->searchFamilyName) >= 2) $this->runSearch();
    }

    public function runSearch(): void
    {
        if (strlen($this->searchFamilyName) < 2) { $this->searchResults = []; $this->hasSearched = false; return; }
        $results = (new PatientSearchService())->search(
            $this->searchFamilyName, $this->searchFirstName ?: null,
            $this->searchSex, $this->searchBirthday, $this->searchAge,
        );

        // Extra filter for ER triage — remove soundex false positives.
        // Only keep results where the stored family name actually starts with
        // what the nurse typed, or the Levenshtein distance is ≤ 2.
        $searchNorm = strtolower(preg_replace('/[\s\-]/', '', $this->searchFamilyName));
        $results = $results->filter(function ($p) use ($searchNorm) {
            $stored = strtolower(preg_replace('/[\s\-]/', '', $p->family_name));
            // Must start with the typed prefix OR be within edit distance 2
            return str_starts_with($stored, $searchNorm)
                || str_starts_with($searchNorm, $stored)
                || levenshtein($searchNorm, $stored) <= 2;
        });

        $this->searchResults = $results->map(fn($p) => [
            'id' => $p->id, 'case_no' => $p->case_no, 'full_name' => $p->full_name,
            'age_display' => $p->age_display, 'sex' => $p->sex,
            'birthday' => $p->birthday ? \Carbon\Carbon::parse($p->birthday)->format('M d, Y') : null,
            'address' => substr($p->address ?? '', 0, 50),
            'last_visit' => $p->latestVisit?->registered_at
                ? \Carbon\Carbon::parse($p->latestVisit->registered_at)->format('M d, Y')
                : null,
        ])->toArray();
        $this->hasSearched = true;
        $this->showTriageForm = false;
        $this->selectedPatientId = null;
    }

    public function selectPatient(int $patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;
        $this->patientData = array_merge($this->patientData, [
            'family_name' => $patient->family_name, 'first_name' => $patient->first_name,
            'middle_name' => $patient->middle_name ?? '', 'sex' => $patient->sex,
            'address' => $patient->address ?? '', 'contact_number' => $patient->contact_number ?? '',
        ]);
        if ($patient->birthday) {
            $this->patientData['birthday'] = \Carbon\Carbon::parse($patient->birthday)->format('Y-m-d');
            $this->patientData['age']      = $patient->current_age;
        } else {
            $this->patientData['age'] = $patient->age;
        }
        $this->showTriageForm = true;
    }

    public function showNewPatientForm(): void
    {
        if (!$this->confirmNoMatch) {
            Notification::make()->title('Please confirm no match first.')->warning()->send();
            return;
        }
        $this->patientData = array_merge($this->patientData, [
            'family_name' => $this->searchFamilyName, 'first_name' => $this->searchFirstName,
            'sex' => $this->searchSex, 'birthday' => $this->searchBirthday,
            'age' => $this->searchBirthday ? (int) \Carbon\Carbon::parse($this->searchBirthday)->diffInYears(now()) : $this->searchAge,
        ]);
        $this->showTriageForm = true;
    }

    public function activateUnknownMode(): void
    {
        $this->isUnknownMode = true; $this->showTriageForm = false;
        $this->hasSearched = false; $this->searchResults = []; $this->selectedPatientId = null;
        $this->patientData['sex'] = 'Male';
    }

    public function cancelUnknownMode(): void { $this->isUnknownMode = false; }

    public function clearSelectedPatient(): void
    {
        $this->selectedPatientId = null;
        $this->patientData = array_merge($this->patientData, [
            'family_name' => '', 'first_name' => '', 'middle_name' => '',
            'birthday' => null, 'age' => null, 'sex' => null,
            'address' => '', 'contact_number' => '',
        ]);
    }

    public function updatedPatientDataBirthday(): void
    {
        if ($this->patientData['birthday'])
            $this->patientData['age'] = (int) \Carbon\Carbon::parse($this->patientData['birthday'])->diffInYears(now());
    }

    public function saveTriage(): void
    {
        $this->validate([
            'triageNurseOnDuty' => 'required|string|min:2',
            'chiefComplaint'    => 'required|string|min:3',
            'triageCategory'    => 'required|in:red,orange,yellow,green,black',
            'consciousness'     => 'required|in:alert,drowsy,unconscious',
            'breathing'         => 'required|in:normal,difficulty,severe',
            'temperature'       => 'required|numeric|between:30,45',
            'pulseRate'         => 'required|integer|between:20,300',
            'respiratoryRate'   => 'required|integer|between:0,80',
        ]);

        $sex = $this->patientData['sex'] ?? 'Male';
        if (!in_array($sex, ['Male', 'Female'])) $sex = 'Male';

        // Unknown/unidentified patient — use sequence like clerk (John #001, Jane #002)
        if ($this->isUnknownMode) {
            $year     = now()->year;
            $seq      = UnknownPatientSequence::nextForYear($year);
            $seqLabel = str_pad($seq, 3, '0', STR_PAD_LEFT);
            $patient  = Patient::create([
                'family_name'         => 'Doe',
                'first_name'          => ($sex === 'Female' ? 'Jane' : 'John') . ' #' . $seqLabel,
                'sex'                 => $sex,
                'address'             => 'Unknown',
                'registration_type'   => 'ER',
                'is_unknown'          => true,
                'has_incomplete_info' => true,
            ]);
        } elseif ($this->selectedPatientId) {
            // Existing patient — reuse record
            $patient = Patient::findOrFail($this->selectedPatientId);
        } else {
            // New patient — nurse filled basic info, clerk will complete
            $familyName = trim($this->patientData['family_name'] ?? '');
            $firstName  = trim($this->patientData['first_name']  ?? '');

            // If nurse left name blank — default John/Jane Doe (no sequence, clerk will update)
            if (empty($familyName) && empty($firstName)) {
                $familyName = 'Doe';
                $firstName  = ($sex === 'Female') ? 'Jane' : 'John';
            }

            $patient = Patient::create([
                'family_name' => $this->properName($familyName),
                'first_name'  => $this->properName($firstName),
                'middle_name' => $this->patientData['middle_name'] ? $this->properName($this->patientData['middle_name']) : null,
                'sex' => $this->patientData['sex'],
                'address' => $this->patientData['address'] ?: 'For completion',
                'contact_number' => $this->patientData['contact_number'] ?: null,
                'birthday' => $this->patientData['birthday'] ?: null,
                'age' => $this->patientData['age'] ?: null,
                'registration_type' => 'ER', 'has_incomplete_info' => true,
            ]);
        }

        // Determine if migration has run
        $migrated = \Illuminate\Support\Facades\Schema::hasColumn('visits', 'triage_nurse_id');

        // Base visit data — only columns guaranteed to exist
        $visitData = [
            'patient_id'           => $patient->id,
            'visit_type'           => 'ER',
            'chief_complaint'      => $this->chiefComplaint,
            'brought_by'           => $this->patientData['brought_by'] ?: null,
            'condition_on_arrival' => $this->patientData['condition_on_arrival'] ?: null,
            'status'               => $migrated ? 'triage' : 'registered',
            'registered_at'        => now(),
        ];

        // Add triage-specific columns only if migration has run
        if ($migrated) {
            $visitData = array_merge($visitData, [
                'triage_nurse_id'    => auth()->id(),
                'triage_at'          => now(),
                'triage_category'    => $this->triageCategory,
                'consciousness'      => $this->consciousness,
                'breathing'          => $this->breathing,
                'mobility'           => $this->mobility,
                'complaint_duration' => $this->complaintDuration,
                'triage_nurse_name'  => $this->triageNurseOnDuty,
                'triage_notes'       => $this->triageNotes ?: null,
            ]);
        }

        $visit = Visit::create($visitData);

        Vital::create([
            'visit_id' => $visit->id, 'patient_id' => $patient->id,
            'recorded_by' => auth()->id(), 'nurse_name' => $this->triageNurseOnDuty,
            'temperature' => $this->temperature, 'temperature_site' => $this->temperatureSite,
            'pulse_rate' => $this->pulseRate, 'respiratory_rate' => $this->respiratoryRate,
            'blood_pressure' => $this->bloodPressure, 'o2_saturation' => $this->o2Saturation,
            'pain_scale' => $this->painScale, 'weight_kg' => $this->weightKg,
            'height_cm' => $this->heightCm, 'notes' => $this->vitalNotes ?: null,
            'taken_at' => now(),
        ]);

        ActivityLog::record(
            action: 'er_triage_initiated',
            category: ActivityLog::CAT_PATIENT,
            subject: $visit,
            subjectLabel: $patient->full_name . ' — ER Triage',
            newValues: [
                'triage_category' => strtoupper($this->triageCategory),
                'chief_complaint' => $this->chiefComplaint,
                'consciousness'   => $this->consciousness,
                'breathing'       => $this->breathing,
                'temperature'     => $this->temperature . ' °C',
                'pulse_rate'      => $this->pulseRate . ' bpm',
                'triage_nurse'    => $this->triageNurseOnDuty,
            ],
            panel: 'nurse',
        );

        Notification::make()->title('Triage saved — forwarded to clerk for registration.')->success()->send();
        $this->resetTriageForm();
    }

    // Doctor assignment
    public function getRegisteredErVisitsProperty()
    {
        return Visit::with(['patient', 'latestVitals'])
            ->where('visit_type', 'ER')->where('status', 'registered')->whereNull('assigned_doctor_id')
            ->when($this->assignSearch, function ($q) {
                $s = '%' . $this->assignSearch . '%';
                $q->whereHas('patient', fn($p) =>
                    $p->where('family_name', 'like', $s)
                      ->orWhere('first_name', 'like', $s)
                      ->orWhere('case_no', 'like', $s));
            })
            ->orderBy('registered_at', 'asc')->get();
    }

    public function getDoctorsProperty()
    {
        return \App\Models\User::where('is_active', true)
            ->whereHas('roles', fn($q) => $q->where('name', 'doctor'))
            ->orderBy('last_name')->get()
            ->map(fn($u) => ['id' => $u->id, 'label' => $u->doctor_label]);
    }

    public function assignDoctor(int $visitId, int $doctorId): void
    {
        $visit = Visit::findOrFail($visitId);
        $migrated = \Illuminate\Support\Facades\Schema::hasColumn('visits', 'triage_nurse_id');
        $visit->update([
            'assigned_doctor_id' => $doctorId,
            'status'             => $migrated ? 'for_assessment' : 'vitals_done',
        ]);
        $doctor = \App\Models\User::find($doctorId);
        ActivityLog::record(
            action: 'doctor_assigned', category: ActivityLog::CAT_PATIENT, subject: $visit,
            subjectLabel: $visit->patient->full_name . ' (' . $visit->patient->case_no . ')',
            newValues: ['assigned_doctor' => $doctor?->doctor_label], panel: 'nurse',
        );
        Notification::make()->title('Doctor assigned — ' . ($doctor?->doctor_label ?? ''))->success()->send();
    }

    public function getTriageCategoryMeta(): array
    {
        return self::TRIAGE_CATEGORIES[$this->triageCategory]
            ?? ['label' => 'Not set', 'color' => '#9ca3af', 'badge' => 'No category selected yet'];
    }

    private function resetTriageForm(): void
    {
        $this->reset([
            'searchFamilyName', 'searchFirstName', 'searchSex', 'searchBirthday', 'searchAge',
            'searchResults', 'hasSearched', 'selectedPatientId', 'showTriageForm',
            'confirmNoMatch', 'isUnknownMode',
            'chiefComplaint', 'complaintDuration', 'consciousness', 'breathing', 'mobility',
            'triageCategory', 'categoryManuallySet', 'triageNotes', 'assignedDepartment',
            'temperature', 'pulseRate', 'respiratoryRate', 'bloodPressure',
            'o2Saturation', 'painScale', 'weightKg', 'heightCm', 'vitalNotes',
        ]);
        $this->patientData = [
            'family_name' => '', 'first_name' => '', 'middle_name' => '',
            'birthday' => null, 'age' => null, 'sex' => null,
            'address' => '', 'contact_number' => '',
            'brought_by' => '', 'condition_on_arrival' => 'Ambulatory',
        ];
        $this->triageNurseOnDuty  = auth()->user()->full_name;
        $this->complaintDuration  = '< 1 day';
        $this->consciousness      = 'alert';
        $this->breathing          = 'normal';
        $this->mobility           = 'walking';
        $this->temperatureSite    = 'Axilla';
        $this->assignedDepartment = 'Emergency Room (ER)';
    }

    private function properName(string $name): string
    {
        return implode(' ', array_map(fn($w) => ucfirst(strtolower($w)), explode(' ', trim($name))));
    }
}