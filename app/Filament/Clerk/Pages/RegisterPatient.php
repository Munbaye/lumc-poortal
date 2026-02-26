<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\ActivityLog;
use App\Services\PatientSearchService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RegisterPatient extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static string  $view           = 'filament.clerk.pages.register-patient';
    protected static ?string $title          = 'Register Patient';
    protected static ?int    $navigationSort = 1;

    // Search fields
    public string  $searchFamilyName = '';
    public string  $searchFirstName  = '';
    public ?string $searchSex        = null;
    public ?string $searchBirthday   = null;

    // Search results & state
    public array $searchResults     = [];
    public bool  $hasSearched       = false;
    public ?int  $selectedPatientId = null;
    public bool  $showCreateForm    = false;
    public bool  $confirmNoMatch    = false;

    // Registration form data
    public array $formData = [
        'family_name'          => '',
        'first_name'           => '',
        'middle_name'          => '',
        'birthday'             => null,
        'sex'                  => null,
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
    ];

    public function mount(): void
    {
        if (auth()->user()->hasRole('clerk-er')) {
            $this->formData['registration_type'] = 'ER';
        }
    }

    public function updatedSearchFamilyName(): void { $this->runSearch(); }
    public function updatedSearchFirstName(): void   { if (strlen($this->searchFamilyName) >= 3) $this->runSearch(); }
    public function updatedSearchSex(): void         { if (strlen($this->searchFamilyName) >= 3) $this->runSearch(); }

    public function runSearch(): void
    {
        if (strlen($this->searchFamilyName) < 3) {
            $this->searchResults = [];
            $this->hasSearched   = false;
            return;
        }

        $results = (new PatientSearchService())->search(
            $this->searchFamilyName,
            $this->searchFirstName ?: null,
            $this->searchSex,
            $this->searchBirthday
        );

        $this->searchResults = $results->map(fn ($p) => [
            'id'          => $p->id,
            'case_no'     => $p->case_no,
            'full_name'   => $p->full_name,
            'age_display' => $p->age_display,
            'sex'         => $p->sex,
            'birthday'    => $p->birthday?->format('M d, Y'),
            'address'     => substr($p->address ?? '', 0, 50),
            'last_visit'  => $p->latestVisit?->registered_at?->format('M d, Y'),
        ])->toArray();

        $this->hasSearched       = true;
        $this->showCreateForm    = false;
        $this->selectedPatientId = null;
    }

    public function selectPatient(int $patientId): void
    {
        $patient                 = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;

        $this->formData = array_merge($this->formData, $patient->only([
            'family_name', 'first_name', 'middle_name', 'birthday',
            'sex', 'address', 'contact_number', 'occupation',
            'civil_status', 'spouse_name', 'father_name', 'mother_name',
        ]));

        if ($patient->birthday) {
            $this->formData['birthday'] = $patient->birthday->format('Y-m-d');
        }

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
        $this->showCreateForm          = true;
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

        $patientFields = array_filter(
            $this->formData,
            fn ($v, $k) => !in_array($k, [
                'registration_type', 'brought_by', 'condition_on_arrival', 'chief_complaint',
            ]) && $v !== null && $v !== '',
            ARRAY_FILTER_USE_BOTH
        );

        if ($this->selectedPatientId) {
            $patient = Patient::findOrFail($this->selectedPatientId);
            $patient->update($patientFields);
            $action  = 'updated_patient';
        } else {
            $patient = Patient::create($patientFields);
            $action  = 'created_patient';
        }

        $visit = Visit::create([
            'patient_id'           => $patient->id,
            'clerk_id'             => auth()->id(),
            'visit_type'           => $this->formData['registration_type'],
            'chief_complaint'      => $this->formData['chief_complaint'],
            'brought_by'           => $this->formData['brought_by']           ?? null,
            'condition_on_arrival' => $this->formData['condition_on_arrival'] ?? null,
            'status'               => 'registered',
            'registered_at'        => now(),
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => 'Patient',
            'subject_id'   => $patient->id,
            'new_values'   => $patient->fresh()->toArray(),
            'ip_address'   => request()->ip(),
        ]);

        Notification::make()
            ->title('Patient registered! Case No: ' . $patient->case_no)
            ->success()
            ->send();

        // ✅ Correct: use 'visitId' to match #[Url] property name in RecordVitals
        $this->redirect(
            RecordVitals::getUrl(['visitId' => $visit->id])
        );
    }
}