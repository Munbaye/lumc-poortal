<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class CompleteAdmission extends Page
{
    protected static ?string $navigationIcon           = 'heroicon-o-clipboard-document-check';
    protected static string  $view                     = 'filament.clerk.pages.complete-admission';
    protected static ?string $title                    = 'Complete Admission';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit   $visit   = null;
    public ?Patient $patient = null;

    // Read-only: when doctor made the admit decision
    public ?string $doctorAdmittedAtDisplay = null;

    // Admission form fields (all optional except paymentClass)
    public string $birthplace         = '';
    public string $religion           = '';
    public string $nationality        = 'Filipino';
    public string $employerName       = '';
    public string $employerAddress    = '';
    public string $employerPhone      = '';
    public string $fatherFullName     = '';
    public string $fatherAddress      = '';
    public string $fatherPhone        = '';
    public string $motherMaidenName   = '';
    public string $motherAddress      = '';
    public string $motherPhone        = '';
    public string $philhealthId       = '';
    public string $philhealthType     = '';
    public string $socialServiceClass = '';
    public string $paymentClass       = 'Charity';

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(\App\Filament\Clerk\Pages\PendingAdmissions::getUrl());
            return;
        }

        $this->visit = Visit::with(['patient', 'medicalHistory.doctor', 'latestVitals', 'doctorsOrders'])
            ->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(\App\Filament\Clerk\Pages\PendingAdmissions::getUrl());
            return;
        }

        // Guard: only allow visits where doctor has admitted but clerk hasn't completed
        if (!$this->visit->doctor_admitted_at) {
            Notification::make()
                ->title('This patient has not been admitted by a doctor yet.')
                ->warning()
                ->send();
            $this->redirect(\App\Filament\Clerk\Pages\PendingAdmissions::getUrl());
            return;
        }

        if ($this->visit->clerk_admitted_at) {
            Notification::make()
                ->title('Admission already completed for this patient.')
                ->info()
                ->send();
            $this->redirect(\App\Filament\Clerk\Pages\PendingAdmissions::getUrl());
            return;
        }

        $this->patient = $this->visit->patient;

        // Format doctor's admission timestamp for display
        $this->doctorAdmittedAtDisplay = $this->visit->doctor_admitted_at
            ->timezone('Asia/Manila')
            ->format('F j, Y \a\t h:i A');

        // Pre-fill from existing patient record
        $this->birthplace         = $this->patient->birthplace          ?? '';
        $this->religion           = $this->patient->religion            ?? '';
        $this->nationality        = $this->patient->nationality         ?? 'Filipino';
        $this->employerName       = $this->patient->employer_name       ?? '';
        $this->employerAddress    = $this->patient->employer_address    ?? '';
        $this->employerPhone      = $this->patient->employer_phone      ?? '';
        $this->fatherFullName     = $this->patient->father_full_name
                                 ?? $this->patient->father_name          ?? '';
        $this->fatherAddress      = $this->patient->father_address      ?? '';
        $this->fatherPhone        = $this->patient->father_phone        ?? '';
        $this->motherMaidenName   = $this->patient->mother_maiden_name
                                 ?? $this->patient->mother_name          ?? '';
        $this->motherAddress      = $this->patient->mother_address      ?? '';
        $this->motherPhone        = $this->patient->mother_phone        ?? '';
        $this->philhealthId       = $this->patient->philhealth_id       ?? '';
        $this->philhealthType     = $this->patient->philhealth_type     ?? '';
        $this->socialServiceClass = $this->patient->social_service_class ?? '';
        $this->paymentClass       = $this->visit->payment_class         ?? 'Charity';
    }

    public function save(): void
    {
        $this->validate(['paymentClass' => 'required|in:Charity,Private']);

        // Update patient record with admission details
        $this->patient->update(array_merge(
            // Reset all optional fields first (so empty form fields clear old data)
            [
                'birthplace' => null, 'religion' => null, 'nationality' => 'Filipino',
                'employer_name' => null, 'employer_address' => null, 'employer_phone' => null,
                'father_full_name' => null, 'father_address' => null, 'father_phone' => null,
                'mother_maiden_name' => null, 'mother_address' => null, 'mother_phone' => null,
                'philhealth_id' => null, 'philhealth_type' => null, 'social_service_class' => null,
            ],
            // Then apply whatever the clerk actually entered
            array_filter([
                'birthplace'           => $this->birthplace        ?: null,
                'religion'             => $this->religion          ?: null,
                'nationality'          => $this->nationality       ?: 'Filipino',
                'employer_name'        => $this->employerName      ?: null,
                'employer_address'     => $this->employerAddress   ?: null,
                'employer_phone'       => $this->employerPhone     ?: null,
                'father_full_name'     => $this->fatherFullName    ?: null,
                'father_address'       => $this->fatherAddress     ?: null,
                'father_phone'         => $this->fatherPhone       ?: null,
                'mother_maiden_name'   => $this->motherMaidenName  ?: null,
                'mother_address'       => $this->motherAddress     ?: null,
                'mother_phone'         => $this->motherPhone       ?: null,
                'philhealth_id'        => $this->philhealthId      ?: null,
                'philhealth_type'      => $this->philhealthType    ?: null,
                'social_service_class' => $this->socialServiceClass ?: null,
            ], fn ($v) => $v !== null)
        ));

        // Update visit — set clerk_admitted_at (this removes it from pending list)
        // NEVER reset doctor_admitted_at here
        $this->visit->update([
            'status'             => 'admitted',
            'disposition'        => 'Admitted',
            'payment_class'      => $this->paymentClass,
            'clerk_admitted_at'  => now(),   // ← the ONLY flag that removes from pending list
        ]);

        // Log
        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                action:       ActivityLog::ACT_ADMITTED_PATIENT,
                category:     'admission',
                subject:      $this->visit,
                subjectLabel: $this->patient->full_name . ' (' . $this->patient->case_no . ')',
                newValues: array_filter([
                    'payment_class'        => $this->paymentClass,
                    'nationality'          => $this->nationality ?: null,
                    'philhealth_id'        => $this->philhealthId        ?: null,
                    'philhealth_type'      => $this->philhealthType       ?: null,
                    'social_service_class' => $this->socialServiceClass   ?: null,
                    'clerk_admitted_at'    => now()->toDateTimeString(),
                    'completed_by'         => auth()->user()->name,
                ]),
                panel: 'clerk',
            );
        }

        Notification::make()
            ->title('Admission completed for ' . $this->patient->full_name)
            ->success()
            ->send();

        $this->redirect(\App\Filament\Clerk\Pages\PendingAdmissions::getUrl());
    }
}