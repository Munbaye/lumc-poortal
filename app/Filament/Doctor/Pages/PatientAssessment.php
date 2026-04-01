<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\MedicalHistory;
use App\Models\DoctorsOrder;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class PatientAssessment extends Page
{
    protected static ?string $navigationIcon           = 'heroicon-o-clipboard-document';
    protected static string  $view                     = 'filament.doctor.pages.patient-assessment';
    protected static ?string $title                    = 'Patient Assessment';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    // NUR-006 History
    public string $chiefComplaint          = '';
    public string $historyOfPresentIllness = '';
    public string $pastMedicalHistory      = '';
    public string $familyHistory           = '';
    public string $occupationEnvironment   = '';
    public string $drugAllergies           = '';
    public string $drugTherapy             = '';
    public string $otherAllergies          = '';

    // NUR-005 Physical Exam
    public string $peSkin            = '';
    public string $peHeadEent        = '';
    public string $peLymphNodes      = '';
    public string $peChest           = '';
    public string $peLungs           = '';
    public string $peCardiovascular  = '';
    public string $peBreast          = '';
    public string $peAbdomen         = '';
    public string $peRectum          = '';
    public string $peGenitalia       = '';
    public string $peMusculoskeletal = '';
    public string $peExtremities     = '';
    public string $peNeurology       = '';
    public string $admittingImpression = '';

    // Diagnosis
    public string $diagnosis             = '';
    public string $differentialDiagnosis = '';
    public string $plan                  = '';

    // Disposition
    public ?bool   $willAdmit             = null;
    public ?string $outpatientDisposition = null;

    // Admitting service
    public string $admittingService = '';

    public array $serviceOptions = [
        'Internal Medicine',
        'Pediatrics',
        'Surgical',
        'Obstetrics and Gynecology',
        'ICU',
        'NICU',
    ];

    // ── Doctor’s Orders (New Big Free-Text Design) ─────────────────────
    public string $orderText = '';

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor/patient-queues');
            return;
        }

        $this->visit = Visit::with(['patient', 'latestVitals', 'medicalHistory', 'doctorsOrders'])
            ->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor/patient-queues');
            return;
        }

        $this->chiefComplaint = $this->visit->chief_complaint ?? '';

        if ($this->visit->doctor_admitted_at !== null) {
            $this->willAdmit = true;
        } elseif ($this->visit->disposition !== null && $this->visit->disposition !== 'Admitted') {
            $this->willAdmit             = false;
            $this->outpatientDisposition = $this->visit->disposition;
        }

        if ($h = $this->visit->medicalHistory) {
            $this->chiefComplaint          = $h->chief_complaint             ?? $this->chiefComplaint;
            $this->historyOfPresentIllness = $h->history_of_present_illness  ?? '';
            $this->pastMedicalHistory      = $h->past_medical_history        ?? '';
            $this->familyHistory           = $h->family_history              ?? '';
            $this->occupationEnvironment   = $h->occupation_environment      ?? '';
            $this->drugAllergies           = $h->drug_allergies              ?? '';
            $this->drugTherapy             = $h->drug_therapy                ?? '';
            $this->otherAllergies          = $h->other_allergies             ?? '';
            $this->peSkin                  = $h->pe_skin                     ?? '';
            $this->peHeadEent              = $h->pe_head_eent                ?? '';
            $this->peLymphNodes            = $h->pe_lymph_nodes              ?? '';
            $this->peChest                 = $h->pe_chest                    ?? '';
            $this->peLungs                 = $h->pe_lungs                    ?? '';
            $this->peCardiovascular        = $h->pe_cardiovascular           ?? '';
            $this->peBreast                = $h->pe_breast                   ?? '';
            $this->peAbdomen               = $h->pe_abdomen                  ?? '';
            $this->peRectum                = $h->pe_rectum                   ?? '';
            $this->peGenitalia             = $h->pe_genitalia                ?? '';
            $this->peMusculoskeletal       = $h->pe_musculoskeletal          ?? '';
            $this->peExtremities           = $h->pe_extremities              ?? '';
            $this->peNeurology             = $h->pe_neurology                ?? '';
            $this->admittingImpression     = $h->admitting_impression        ?? '';
            $this->diagnosis               = $h->diagnosis                   ?? '';
            $this->differentialDiagnosis   = $h->differential_diagnosis      ?? '';
            $this->plan                    = $h->plan                        ?? '';
            $this->admittingService        = $h->service                     ?? '';
        }

        if (!$this->admittingService && $this->visit->admitted_service) {
            $this->admittingService = $this->visit->admitted_service;
        }

        // Load existing orders as multiline text
        $this->orderText = $this->visit->doctorsOrders
            ->pluck('order_text')
            ->implode("\n");
    }

    public function updatedWillAdmit(): void
    {
        $this->outpatientDisposition = null;
        if (!$this->willAdmit) {
            $this->admittingService = '';
        }
    }

    public function save(): void
    {
        if ($this->willAdmit === null) {
            Notification::make()->title('Please make a disposition decision in Section 4.')->warning()->send();
            return;
        }

        $disposition = $this->willAdmit ? 'Admitted' : $this->outpatientDisposition;

        if (!$disposition) {
            Notification::make()->title('Please select a specific outcome before saving.')->warning()->send();
            return;
        }

        if ($this->willAdmit && !$this->admittingService) {
            Notification::make()->title('Please select a service type before admitting.')->warning()->send();
            return;
        }

        // 1. Save Medical History
        MedicalHistory::updateOrCreate(
            ['visit_id' => $this->visitId],
            [
                'patient_id'                 => $this->visit->patient_id,
                'doctor_id'                  => auth()->id(),
                'chief_complaint'            => $this->chiefComplaint,
                'history_of_present_illness' => $this->historyOfPresentIllness,
                'past_medical_history'       => $this->pastMedicalHistory,
                'family_history'             => $this->familyHistory,
                'occupation_environment'     => $this->occupationEnvironment,
                'drug_allergies'             => $this->drugAllergies,
                'drug_therapy'               => $this->drugTherapy,
                'other_allergies'            => $this->otherAllergies,
                'pe_skin'                    => $this->peSkin,
                'pe_head_eent'               => $this->peHeadEent,
                'pe_lymph_nodes'             => $this->peLymphNodes,
                'pe_chest'                   => $this->peChest,
                'pe_lungs'                   => $this->peLungs,
                'pe_cardiovascular'          => $this->peCardiovascular,
                'pe_breast'                  => $this->peBreast,
                'pe_abdomen'                 => $this->peAbdomen,
                'pe_rectum'                  => $this->peRectum,
                'pe_genitalia'               => $this->peGenitalia,
                'pe_musculoskeletal'         => $this->peMusculoskeletal,
                'pe_extremities'             => $this->peExtremities,
                'pe_neurology'               => $this->peNeurology,
                'admitting_impression'       => $this->admittingImpression,
                'diagnosis'                  => $this->diagnosis,
                'differential_diagnosis'     => $this->differentialDiagnosis,
                'plan'                       => $this->plan,
                'disposition'                => $disposition,
                'service'                    => $this->willAdmit ? $this->admittingService : null,
            ]
        );

        // 2. Save Doctor's Orders (New free-text method)
        if ($this->willAdmit && trim($this->orderText) !== '') {
            $lines = collect(explode("\n", $this->orderText))
                ->map(fn ($line) => trim($line))
                ->filter(fn ($line) => $line !== '')
                ->values();

            foreach ($lines as $text) {
                // Avoid creating duplicate orders
                $exists = DoctorsOrder::where('visit_id', $this->visitId)
                    ->where('order_text', $text)
                    ->exists();

                if (!$exists) {
                    DoctorsOrder::create([
                        'visit_id'     => $this->visitId,
                        'doctor_id'    => auth()->id(),
                        'order_text'   => $text,
                        'status'       => DoctorsOrder::STATUS_PENDING,
                        'order_date'   => now(),
                        'is_completed' => false,
                    ]);
                }
            }
        }

        // 3. Update Visit
        $status = match ($disposition) {
            'Admitted'                      => 'admitted',
            'Discharged', 'HAMA', 'Expired' => 'discharged',
            'Referred'                      => 'referred',
            default                         => 'assessed',
        };

        $visitUpdate = ['status' => $status, 'disposition' => $disposition];

        if ($this->willAdmit) {
            $visitUpdate['admitting_diagnosis'] = $this->diagnosis ?: $this->admittingImpression ?: $this->chiefComplaint;
            $visitUpdate['admitted_service']    = $this->admittingService;
            $visitUpdate['doctor_admitted_at']  = now();
            $visitUpdate['clerk_admitted_at']   = null;
        }

        if (in_array($disposition, ['Discharged', 'HAMA', 'Expired'])) {
            $visitUpdate['discharged_at'] = now();
        }

        $this->visit->update($visitUpdate);

        // 4. Activity Log
        ActivityLog::record(
            action: match (true) {
                $disposition === 'Admitted'   => ActivityLog::ACT_ADMITTED_PATIENT,
                $disposition === 'Discharged' => ActivityLog::ACT_DISCHARGED_PATIENT,
                default                       => ActivityLog::ACT_ASSESSED_PATIENT,
            },
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->visit,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: array_filter([
                'status'            => $status,
                'disposition'       => $disposition,
                'diagnosis'         => $this->diagnosis ?: null,
                'admitting_service' => $this->willAdmit ? $this->admittingService : null,
            ]),
            panel: 'doctor',
        );

        // 5. Notify clerks when admitting
        if ($disposition === 'Admitted') {
            $clerks = User::where('is_active', true)
                ->whereHas('roles', fn ($q) => $q->whereIn('name', ['clerk','clerk-opd','clerk-er']))
                ->get();

            foreach ($clerks as $clerk) {
                Notification::make()
                    ->title('Patient Ready for Admission — ' . $this->admittingService)
                    ->body($this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ') — ' . ($this->diagnosis ?: $this->chiefComplaint))
                    ->icon('heroicon-o-arrow-right-circle')
                    ->iconColor('success')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('complete_admission')
                            ->label('Complete Admission')
                            ->url(\App\Filament\Clerk\Pages\CompleteAdmission::getUrl(['visitId' => $this->visit->id], panel: 'clerk'))
                            ->button(),
                    ])
                    ->sendToDatabase($clerk);
            }

            Notification::make()->title('Admission order sent — clerk notified.')->success()->send();
            $this->redirect(\App\Filament\Doctor\Resources\PatientQueueResource::getUrl('index'));
        } else {
            Notification::make()->title('Assessment saved.')->success()->send();
            $this->redirect(\App\Filament\Doctor\Resources\PatientQueueResource::getUrl('index'));
        }
    }
}