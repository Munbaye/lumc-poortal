<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\ActivityLog;
use App\Services\PatientSearchService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\On;

class RegisterPatient extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static string $view = 'filament.clerk.pages.register-patient';
    protected static ?string $title = 'Register Patient';
    protected static ?int $navigationSort = 1;

    // Search fields (bound to form)
    public string  $searchFamilyName = '';
    public string  $searchFirstName  = '';
    public ?string $searchSex        = null;
    public ?string $searchBirthday   = null;

    // Search results
    public array $searchResults = [];
    public bool  $hasSearched   = false;

    // Flow control
    public ?int  $selectedPatientId = null;
    public bool  $showCreateForm    = false;
    public bool  $confirmNoMatch    = false;

    // Registration form data
    public array $formData = [
        'family_name'        => '',
        'first_name'         => '',
        'middle_name'        => '',
        'birthday'           => null,
        'sex'                => null,
        'address'            => '',
        'contact_number'     => '',
        'occupation'         => '',
        'civil_status'       => null,
        'spouse_name'        => '',
        'father_name'        => '',
        'mother_name'        => '',
        'registration_type'  => 'OPD',
        'brought_by'         => null,
        'condition_on_arrival' => null,
        'chief_complaint'    => '',
    ];

    // When searchFamilyName changes, run search automatically
    public function updatedSearchFamilyName(): void
    {
        $this->runSearch();
    }

    public function updatedSearchFirstName(): void
    {
        if (strlen($this->searchFamilyName) >= 3) $this->runSearch();
    }

    public function updatedSearchSex(): void
    {
        if (strlen($this->searchFamilyName) >= 3) $this->runSearch();
    }

    public function runSearch(): void
    {
        if (strlen($this->searchFamilyName) < 3) {
            $this->searchResults = [];
            $this->hasSearched   = false;
            return;
        }

        $service = new PatientSearchService();
        $results = $service->search(
            $this->searchFamilyName,
            $this->searchFirstName ?: null,
            $this->searchSex,
            $this->searchBirthday
        );

        $this->searchResults = $results->map(fn($p) => [
            'id'           => $p->id,
            'case_no'      => $p->case_no,
            'full_name'    => $p->full_name,
            'age_display'  => $p->age_display,
            'sex'          => $p->sex,
            'birthday'     => $p->birthday?->format('M d, Y'),
            'address'      => substr($p->address, 0, 50),
            'last_visit'   => $p->latestVisit?->registered_at?->format('M d, Y'),
        ])->toArray();

        $this->hasSearched   = true;
        $this->showCreateForm = false;
        $this->selectedPatientId = null;
    }

    // Clerk selects an existing patient
    public function selectPatient(int $patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;

        // Pre-fill form with existing data
        $this->formData = array_merge($this->formData, $patient->only([
            'family_name','first_name','middle_name','birthday',
            'sex','address','contact_number','occupation',
            'civil_status','spouse_name','father_name','mother_name',
        ]));

        if ($patient->birthday) {
            $this->formData['birthday'] = $patient->birthday->format('Y-m-d');
        }

        $this->showCreateForm = true;
    }

    // Show the new patient creation form
    public function showNewPatientForm(): void
    {
        if (!$this->confirmNoMatch) {
            Notification::make()
                ->title('Please confirm that no match was found')
                ->warning()
                ->send();
            return;
        }

        // Pre-fill from search inputs
        $this->formData['family_name'] = $this->searchFamilyName;
        $this->formData['first_name']  = $this->searchFirstName;
        $this->formData['sex']         = $this->searchSex;
        $this->formData['birthday']    = $this->searchBirthday;
        $this->showCreateForm = true;
    }

    // Save — create or update patient, then create visit
    public function save(): void
    {
        // Basic validation
        $this->validate([
            'formData.family_name'      => 'required|string|max:100',
            'formData.first_name'       => 'required|string|max:100',
            'formData.sex'              => 'required|in:Male,Female',
            'formData.address'          => 'required|string',
            'formData.chief_complaint'  => 'required|string',
        ], [
            'formData.family_name.required'     => 'Family name is required.',
            'formData.first_name.required'      => 'First name is required.',
            'formData.sex.required'             => 'Sex is required.',
            'formData.address.required'         => 'Address is required.',
            'formData.chief_complaint.required' => 'Chief complaint is required.',
        ]);

        if ($this->selectedPatientId) {
            // UPDATE existing patient
            $patient = Patient::findOrFail($this->selectedPatientId);
            $oldValues = $patient->toArray();
            $patient->update(array_filter($this->formData, fn($v, $k) =>
                !in_array($k, ['registration_type','brought_by','condition_on_arrival','chief_complaint'])
                && $v !== null && $v !== '',
                ARRAY_FILTER_USE_BOTH
            ));
            $action = 'updated_patient';
        } else {
            // CREATE new patient
            $patient = Patient::create(array_filter(
                $this->formData,
                fn($v, $k) => !in_array($k, ['registration_type','brought_by','condition_on_arrival','chief_complaint'])
                && $v !== null && $v !== '',
                ARRAY_FILTER_USE_BOTH
            ));
            $action = 'created_patient';
        }

        // Create visit
        $visit = Visit::create([
            'patient_id'         => $patient->id,
            'clerk_id'           => auth()->id(),
            'visit_type'         => $this->formData['registration_type'],
            'chief_complaint'    => $this->formData['chief_complaint'],
            'brought_by'         => $this->formData['brought_by'] ?? null,
            'condition_on_arrival'=> $this->formData['condition_on_arrival'] ?? null,
            'status'             => 'registered',
            'registered_at'      => now(),
        ]);

        // Log
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => 'Patient',
            'subject_id'   => $patient->id,
            'new_values'   => $patient->toArray(),
            'ip_address'   => request()->ip(),
        ]);

        Notification::make()
            ->title('Patient registered! Case No: ' . $patient->case_no)
            ->success()
            ->send();

    $this->redirect(
        \App\Filament\Clerk\Pages\RecordVitals::getUrl(['visit' => $visit->id])
    );
    }

    public function mount(): void
    {
        // Set default registration type based on clerk role
        if (auth()->user()->hasRole('clerk-er')) {
            $this->formData['registration_type'] = 'ER';
        }
    }
}