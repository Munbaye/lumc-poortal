<?php
namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\MedicalHistory;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class PatientAssessment extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-clipboard-document';
    protected static string  $view                  = 'filament.doctor.pages.patient-assessment';
    protected static ?string $title                 = 'Patient Assessment';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    /** Doctors grouped by specialty for Private assignment dropdown */
    public array $availableDoctors = [];

    // ── NUR-006: Medical History ───────────────────────────────────────────────
    public string $chiefComplaint          = '';
    public string $historyOfPresentIllness = '';
    public string $pastMedicalHistory      = '';
    public string $familyHistory           = '';
    public string $occupationEnvironment   = '';
    public string $drugAllergies           = '';
    public string $drugTherapy             = '';
    public string $otherAllergies          = '';

    // ── NUR-005: Physical Examination ─────────────────────────────────────────
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

    // ── Diagnosis ─────────────────────────────────────────────────────────────
    public string $diagnosis             = '';
    public string $differentialDiagnosis = '';
    public string $plan                  = '';

    // ── Disposition — two-step ────────────────────────────────────────────────
    // Step 1: null = not decided, true = admit, false = not admitting
    public ?bool $willAdmit = null;

    // Step 2a (not admitting): Discharged | Referred | HAMA | Expired
    public ?string $outpatientDisposition = null;

    // Step 2b (admitting): ward, service, payment, doctor
    public string  $admittedWard     = '';
    public string  $admittedService  = '';
    public string  $paymentClass     = 'Charity'; // default Charity
    public ?int    $assignedDoctorId = null;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor/patient-queues');
            return;
        }

        $this->visit = Visit::with(['patient', 'latestVitals', 'medicalHistory'])
            ->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor/patient-queues');
            return;
        }

        // Load doctors grouped by specialty
        $this->availableDoctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('specialty')
            ->orderBy('name')
            ->get(['id', 'name', 'specialty'])
            ->toArray();

        // Pre-fill chief complaint from visit
        $this->chiefComplaint = $this->visit->chief_complaint ?? '';

        // Restore willAdmit state from saved disposition
        if ($this->visit->disposition === 'Admitted') {
            $this->willAdmit      = true;
            $this->admittedWard   = $this->visit->admitted_ward    ?? '';
            $this->admittedService= $this->visit->admitted_service ?? '';
            $this->paymentClass   = $this->visit->payment_class    ?? 'Charity';
            $this->assignedDoctorId = $this->visit->assigned_doctor_id;
        } elseif ($this->visit->disposition !== null) {
            $this->willAdmit              = false;
            $this->outpatientDisposition  = $this->visit->disposition;
        }

        // Pre-fill from existing medical history record
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
        }
    }

    public function updatedPaymentClass(): void
    {
        if ($this->paymentClass === 'Charity') {
            $this->assignedDoctorId = null;
        }
    }

    public function updatedWillAdmit(): void
    {
        // Reset step-2 fields when the admit decision changes
        $this->outpatientDisposition = null;
        if (!$this->willAdmit) {
            $this->admittedWard      = '';
            $this->admittedService   = '';
            $this->paymentClass      = 'Charity';
            $this->assignedDoctorId  = null;
        }
    }

    public function save(): void
    {
        // Guard: must have made an admit decision
        if ($this->willAdmit === null) {
            Notification::make()->title('Please make an admit decision (Section 4) before saving.')->warning()->send();
            return;
        }

        // Guard: must have a specific outcome
        $disposition = $this->willAdmit ? 'Admitted' : $this->outpatientDisposition;
        if (!$disposition) {
            Notification::make()->title('Please select a specific disposition outcome.')->warning()->send();
            return;
        }

        // Guard: Private admission must have assigned doctor
        if ($this->willAdmit && $this->paymentClass === 'Private' && !$this->assignedDoctorId) {
            Notification::make()->title('Please assign a doctor for Private patients.')->warning()->send();
            return;
        }

        // Save medical history
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
                'admitted_ward'              => $this->willAdmit ? ($this->admittedWard   ?: null) : null,
                'service'                    => $this->willAdmit ? ($this->admittedService ?: null) : null,
                'payment_type'               => $this->willAdmit ? $this->paymentClass : null,
            ]
        );

        // Map disposition → visit status
        $status = match ($disposition) {
            'Discharged', 'HAMA', 'Expired' => 'discharged',
            'Admitted'                       => 'admitted',
            'Referred'                       => 'referred',
            default                          => 'assessed',
        };

        $visitUpdate = [
            'status'        => $status,
            'disposition'   => $disposition,
            'discharged_at' => in_array($disposition, ['Discharged', 'HAMA', 'Expired']) ? now() : null,
        ];

        if ($this->willAdmit) {
            $visitUpdate['payment_class']      = $this->paymentClass;
            $visitUpdate['admitted_ward']      = $this->admittedWard    ?: null;
            $visitUpdate['admitted_service']   = $this->admittedService ?: null;
            $visitUpdate['assigned_doctor_id'] = $this->paymentClass === 'Private'
                ? $this->assignedDoctorId : null;
        } else {
            // Clear admission fields if not admitting (e.g., re-assessment)
            $visitUpdate['payment_class']      = null;
            $visitUpdate['admitted_ward']      = null;
            $visitUpdate['admitted_service']   = null;
            $visitUpdate['assigned_doctor_id'] = null;
        }

        $this->visit->update($visitUpdate);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'assessed_patient',
            'subject_type' => 'Visit',
            'subject_id'   => $this->visitId,
            'new_values'   => ['disposition' => $disposition, 'status' => $status],
            'ip_address'   => request()->ip(),
        ]);

        Notification::make()->title('Assessment saved successfully!')->success()->send();
        $this->redirect(\App\Filament\Doctor\Resources\PatientQueueResource::getUrl('index'));
    }
}