<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\UnknownPatientSequence;
use App\Services\PatientSearchService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class RegisterPatient extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static string  $view           = 'filament.clerk.pages.register-patient';
    protected static ?string $title          = 'Register Patient';
    protected static ?int    $navigationSort = 1;

    // ── Search ──────────────────────────────────────────────────────────────
    public string  $searchFamilyName = '';
    public string  $searchFirstName  = '';
    public ?string $searchSex        = null;
    public ?string $searchBirthday   = null;
    public ?int    $searchAge        = null;

    public array $searchResults     = [];
    public bool  $hasSearched       = false;
    public ?int  $selectedPatientId = null;
    public bool  $showCreateForm    = false;
    public bool  $confirmNoMatch    = false;

    // ── Credentials modal ────────────────────────────────────────────────────
    public bool    $showCredentialsModal = false;
    public ?string $credUsername         = null;
    public ?string $credPassword         = null;
    public ?string $credRedirectUrl      = null;

    // ── Unknown mode ─────────────────────────────────────────────────────────
    public bool  $isUnknownMode   = false;
    public array $unknownFormData = [
        'chief_complaint'      => '',
        'age_range_min'        => null,
        'age_range_max'        => null,
        'brought_by'           => null,
        'condition_on_arrival' => null,
        'sex'                  => 'Male',
        'notes'                => '',
    ];

    // ── Registration form ────────────────────────────────────────────────────
    public array $formData = [
        'family_name'          => '',
        'first_name'           => '',
        'middle_name'          => '',
        'birthday'             => null,
        'age'                  => null,
        'sex'                  => null,      // FIX: default null, not Male
        'address'              => '',
        'contact_number'       => '',
        'occupation'           => '',
        'civil_status'         => null,
        'spouse_name'          => '',
        'father_name'          => '',
        'mother_name'          => '',
        'registration_type'    => 'OPD',
        'brought_by'           => null,
        'condition_on_arrival' => null,
        'chief_complaint'      => '',
        'has_incomplete_info'  => false,
    ];

    public function mount(): void
    {
        if (auth()->user()->hasRole('clerk-er')) {
            $this->formData['registration_type'] = 'ER';
        }
    }

    public function dismissCredentialsModal(): void
    {
        if ($this->credRedirectUrl) {
            $this->redirect($this->credRedirectUrl);
        }
        $this->showCredentialsModal = false;
        $this->credUsername         = null;
        $this->credPassword         = null;
        $this->credRedirectUrl      = null;
    }

    // ── Search wiring ────────────────────────────────────────────────────────
    public function updatedSearchFamilyName(): void { $this->runSearch(); }
    public function updatedSearchFirstName(): void  { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }
    public function updatedSearchSex(): void        { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }
    public function updatedSearchAge(): void        { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }
    public function updatedSearchBirthday(): void   { if (strlen($this->searchFamilyName) >= 2) $this->runSearch(); }

    // FIX: Auto-calculate age when birthday is entered
    public function updatedFormDataBirthday(): void
    {
        if ($this->formData['birthday']) {
            $this->formData['age'] = \Carbon\Carbon::parse($this->formData['birthday'])->age;
        } else {
            $this->formData['age'] = null;
        }
    }

    public function runSearch(): void
    {
        if (strlen($this->searchFamilyName) < 2) {
            $this->searchResults = [];
            $this->hasSearched   = false;
            return;
        }

        $results = (new PatientSearchService())->search(
            $this->searchFamilyName,
            $this->searchFirstName ?: null,
            $this->searchSex,
            $this->searchBirthday,
            $this->searchAge,
        );

        $this->searchResults = $results->map(fn ($p) => [
            'id'             => $p->id,
            'case_no'        => $p->case_no,
            'full_name'      => $p->full_name,
            'age_display'    => $p->age_display,
            'sex'            => $p->sex,
            'birthday'       => $p->birthday?->format('M d, Y'),
            'address'        => substr($p->address ?? '', 0, 50),
            'last_visit'     => $p->latestVisit?->registered_at?->format('M d, Y'),
            'has_incomplete' => $p->has_incomplete_info,
        ])->toArray();

        $this->hasSearched       = true;
        $this->showCreateForm    = false;
        $this->selectedPatientId = null;
    }

    public function selectPatient(int $patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;

        $this->formData = array_merge($this->formData, $patient->only([
            'family_name', 'first_name', 'middle_name',
            'sex', 'address', 'contact_number', 'occupation',
            'civil_status', 'spouse_name', 'father_name', 'mother_name',
        ]));

        if ($patient->birthday) {
            $this->formData['birthday'] = $patient->birthday->format('Y-m-d');
            $this->formData['age']      = $patient->current_age;
        } else {
            $this->formData['age'] = $patient->age;
        }

        $this->formData['has_incomplete_info'] = $patient->has_incomplete_info;
        $this->showCreateForm = true;
    }

    public function showNewPatientForm(): void
    {
        if (!$this->confirmNoMatch) {
            Notification::make()->title('Please tick the confirmation checkbox first.')->warning()->send();
            return;
        }

        $this->formData['family_name'] = $this->searchFamilyName;
        $this->formData['first_name']  = $this->searchFirstName;
        $this->formData['sex']         = $this->searchSex;
        $this->formData['birthday']    = $this->searchBirthday;

        // FIX: calculate age from birthday if present, otherwise use search age
        if ($this->searchBirthday) {
            $this->formData['age'] = \Carbon\Carbon::parse($this->searchBirthday)->age;
        } else {
            $this->formData['age'] = $this->searchAge;
        }

        $this->showCreateForm = true;
    }

    // ── Unknown mode ─────────────────────────────────────────────────────────
    public function activateUnknownMode(): void
    {
        $this->isUnknownMode  = true;
        $this->showCreateForm = false;
        $this->hasSearched    = false;
        $this->searchResults  = [];
    }

    public function cancelUnknownMode(): void
    {
        $this->isUnknownMode  = false;
        $this->unknownFormData = [
            'chief_complaint'      => '',
            'age_range_min'        => null,
            'age_range_max'        => null,
            'brought_by'           => null,
            'condition_on_arrival' => null,
            'sex'                  => 'Male',
            'notes'                => '',
        ];
    }

    public function saveUnknown(): void
    {
        $this->validate([
            'unknownFormData.chief_complaint' => 'required|string|min:3',
            'unknownFormData.brought_by'      => 'required|string',
        ], [
            'unknownFormData.chief_complaint.required' => 'Chief complaint is required.',
            'unknownFormData.brought_by.required'      => 'Please indicate who brought the patient.',
        ]);

        $year     = now()->year;
        $seq      = UnknownPatientSequence::nextForYear($year);
        $seqLabel = str_pad($seq, 3, '0', STR_PAD_LEFT);

        $ageMin = $this->unknownFormData['age_range_min'];
        $ageMax = $this->unknownFormData['age_range_max'];
        $estAge = ($ageMin !== null && $ageMax !== null)
            ? (int) round(($ageMin + $ageMax) / 2)
            : null;

        $sex = $this->unknownFormData['sex'] ?? 'Male';
        if (!in_array($sex, ['Male', 'Female'])) {
            $sex = 'Male';
        }

        $firstName  = ($sex === 'Female' ? 'Jane' : 'John') . ' #' . $seqLabel;
        $familyName = 'Doe';

        $patient = Patient::create([
            'family_name'          => $familyName,
            'first_name'           => $firstName,
            'sex'                  => $sex,
            'address'              => 'Unknown',
            'age'                  => $estAge,
            'brought_by'           => $this->unknownFormData['brought_by'],
            'condition_on_arrival' => $this->unknownFormData['condition_on_arrival'],
            'registration_type'    => 'ER',
            'is_unknown'           => true,
            'has_incomplete_info'  => true,
        ]);

        $visit = Visit::create([
            'patient_id'           => $patient->id,
            'clerk_id'             => auth()->id(),
            'visit_type'           => 'ER',
            'chief_complaint'      => $this->unknownFormData['chief_complaint'],
            'brought_by'           => $this->unknownFormData['brought_by'],
            'condition_on_arrival' => $this->unknownFormData['condition_on_arrival'],
            'status'               => 'registered',
            'payment_class'        => null,
            'registered_at'        => now(),
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                action:       ActivityLog::ACT_CREATED_PATIENT,
                category:     ActivityLog::CAT_PATIENT,
                subject:      $patient,
                subjectLabel: $familyName . ', ' . $firstName . ' (' . $patient->case_no . ')',
                newValues: [
                    'type'            => 'UNKNOWN',
                    'chief_complaint' => $this->unknownFormData['chief_complaint'],
                    'brought_by'      => $this->unknownFormData['brought_by'],
                    'age_range'       => ($ageMin && $ageMax) ? "{$ageMin}-{$ageMax} y/o" : 'Unknown',
                ],
                panel: 'clerk',
            );
        }

        Notification::make()
            ->title('Unknown patient registered — ' . $patient->case_no)
            ->success()
            ->send();

        $this->redirect(\App\Filament\Clerk\Pages\RecordVitals::getUrl(['visitId' => $visit->id]));
    }

    public function save(): void
    {
        $this->validate([
            'formData.family_name'     => 'required|string|max:100',
            'formData.first_name'      => 'required|string|max:100',
            'formData.sex'             => 'required|in:Male,Female',
            'formData.address'         => 'required|string|min:5',
            'formData.chief_complaint' => 'required|string|min:3',
        ], [
            'formData.family_name.required'     => 'Family name is required.',
            'formData.first_name.required'      => 'First name is required.',
            'formData.sex.required'             => 'Sex is required.',
            'formData.address.required'         => 'Address is required.',
            'formData.address.min'              => 'Please enter a complete address.',
            'formData.chief_complaint.required' => 'Chief complaint is required.',
            'formData.chief_complaint.min'      => 'Please describe the chief complaint.',
        ]);

        $familyName = $this->properName($this->formData['family_name']);
        $firstName  = $this->properName($this->formData['first_name']);
        $middleName = $this->formData['middle_name']
                      ? $this->properName($this->formData['middle_name'])
                      : null;

        $patientFields = [
            'family_name'         => $familyName,
            'first_name'          => $firstName,
            'middle_name'         => $middleName,
            'sex'                 => $this->formData['sex'],
            'address'             => $this->formData['address'],
            'contact_number'      => $this->formData['contact_number'] ?: null,
            'occupation'          => $this->formData['occupation'] ?: null,
            'civil_status'        => $this->formData['civil_status'] ?: null,
            'spouse_name'         => $this->formData['spouse_name'] ?: null,
            'father_name'         => $this->formData['father_name'] ?: null,
            'mother_name'         => $this->formData['mother_name'] ?: null,
            'has_incomplete_info' => (bool) ($this->formData['has_incomplete_info'] ?? false),
        ];

        if ($this->formData['birthday']) {
            $patientFields['birthday'] = $this->formData['birthday'];
        } elseif ($this->formData['age']) {
            $patientFields['age'] = (int) $this->formData['age'];
        }

        if (
            !empty($this->formData['birthday']) &&
            !empty($this->formData['address']) &&
            !empty($this->formData['contact_number'])
        ) {
            $patientFields['has_incomplete_info'] = false;
        }

        $isNewPatient = !$this->selectedPatientId;
        $wasUnknown   = false;

        if ($this->selectedPatientId) {
            $patient    = Patient::findOrFail($this->selectedPatientId);
            $wasUnknown = (bool) $patient->is_unknown;
            $oldValues  = $patient->toArray();

            if ($wasUnknown) {
                $patientFields['is_unknown'] = false;
            }

            $patient->update($patientFields);
            $action = 'updated_patient';
        } else {
            $oldValues = [];
            $patient   = Patient::create($patientFields);
            $action    = 'created_patient';
        }

        if ($this->selectedPatientId && $wasUnknown) {
            $existingVisit = Visit::where('patient_id', $patient->id)
                ->orderByDesc('registered_at')
                ->first();

            if ($existingVisit) {
                $existingVisit->update([
                    'visit_type'           => $this->formData['registration_type'],
                    'chief_complaint'      => $this->formData['chief_complaint'],
                    'brought_by'           => $this->formData['brought_by'] ?? null,
                    'condition_on_arrival' => $this->formData['condition_on_arrival'] ?? null,
                ]);
                $visit = $existingVisit;
            } else {
                $visit = $this->createVisit($patient);
            }
        } else {
            $visit = $this->createVisit($patient);
        }

        $credentials = null;
        if ($isNewPatient || ($wasUnknown && !$patient->is_unknown)) {
            if (!User::where('patient_id', $patient->id)->exists()) {
                $credentials = $this->createPatientAccount($patient);
            }
        }

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                action:       $action,
                category:     ActivityLog::CAT_PATIENT,
                subject:      $patient,
                subjectLabel: $patient->full_name . ' (' . $patient->case_no . ')',
                oldValues:    $oldValues,
                newValues:    array_filter([
                    'family_name'     => $patient->family_name,
                    'first_name'      => $patient->first_name,
                    'sex'             => $patient->sex,
                    'birthday'        => $patient->birthday?->format('Y-m-d'),
                    'address'         => $patient->address,
                    'contact_number'  => $patient->contact_number,
                    'visit_type'      => $this->formData['registration_type'],
                    'chief_complaint' => $this->formData['chief_complaint'],
                    'has_incomplete'  => $patientFields['has_incomplete_info'] ? 'YES' : 'No',
                ]),
                panel: 'clerk',
            );
        }

        $redirectUrl = \App\Filament\Clerk\Pages\RecordVitals::getUrl(['visitId' => $visit->id]);

        if ($credentials) {
            $this->credUsername        = $credentials['username'];
            $this->credPassword        = $credentials['password'];
            $this->credRedirectUrl     = $redirectUrl;
            $this->showCredentialsModal = true;
        } else {
            Notification::make()
                ->title('Patient registered! Case No: ' . $patient->case_no)
                ->success()
                ->send();
            $this->redirect($redirectUrl);
        }
    }

    private function properName(string $name): string
    {
        return implode(' ', array_map(
            fn ($word) => ucfirst(strtolower($word)),
            explode(' ', trim($name))
        ));
    }

    private function createVisit(Patient $patient): Visit
    {
        return Visit::create([
            'patient_id'           => $patient->id,
            'clerk_id'             => auth()->id(),
            'visit_type'           => $this->formData['registration_type'],
            'chief_complaint'      => $this->formData['chief_complaint'],
            'brought_by'           => $this->formData['brought_by'] ?? null,
            'condition_on_arrival' => $this->formData['condition_on_arrival'] ?? null,
            'status'               => 'registered',
            'payment_class'        => null,
            'registered_at'        => now(),
        ]);
    }

    protected function createPatientAccount(Patient $patient): ?array
    {
        if (User::where('patient_id', $patient->id)->exists()) {
            return null;
        }

        $firstName = preg_replace('/[^a-zA-Z]/', '', $patient->first_name);
        $lastName  = preg_replace('/[^a-zA-Z]/', '', $patient->family_name);
        $age       = $patient->current_age ?? $patient->age ?? 0;

        $baseUsername = ucfirst(strtolower($firstName)) . ucfirst(strtolower($lastName)) . $age;

        $username = $baseUsername;
        $counter  = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $email    = 'patient_' . $patient->id . '_' . time() . '@internal';
        $password = $username;

        $user = User::create([
            'name'                  => $patient->full_name,
            'username'              => $username,
            'email'                 => $email,
            'password'              => Hash::make($password),
            'panel'                 => 'patient',
            'is_active'             => true,
            'patient_id'            => $patient->id,
            'force_password_change' => true,
        ]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'patient', 'guard_name' => 'web']
        );
        $user->assignRole($role);

        return [
            'username' => $username,
            'password' => $password,
        ];
    }
}