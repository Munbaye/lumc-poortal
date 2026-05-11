<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\ObRecord;
use App\Models\AdmissionRecord;
use App\Models\DoctorsOrder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Vital;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

/**
 * ObAssessment — Doctor assessment & admission page for OB patients.
 * Mirrors NicuAssessment pattern exactly.
 *
 * Registered in DoctorPanelProvider.
 * Linked from ObPatientResource "Assess & Admit" action.
 */
class ObAssessment extends Page
{
    protected static ?string $navigationIcon         = 'heroicon-o-clipboard-document';
    protected static string  $view                   = 'filament.doctor.pages.ob-assessment';
    protected static ?string $title                  = 'OB Patient — Assessment & Admission';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit    $visit     = null;
    public ?ObRecord $obRecord  = null;
    public bool      $isAdmitted = false;

    // ── Read-only patient info ────────────────────────────────────────────────
    public ?string $patientName = null;
    public ?string $caseNo      = null;
    public ?string $aog         = null;
    public ?string $gptal       = null;
    public ?string $lmp         = null;
    public ?string $chiefComplaint = null;

    // ── Triage vitals (read-only from what nurse entered) ────────────────────
    public ?string $triageBp   = null;
    public ?string $triagePulse = null;
    public ?string $triageTemp  = null;
    public ?string $triageRr    = null;

    // ── Doctor fills during assessment ───────────────────────────────────────
    public ?string $diagnosisOnAdmission = null;
    public ?string $generalCondition     = null;

    // ── Internal Examination (IE) ─────────────────────────────────────────────
    public ?string $ieCervicalDilation = null;
    public ?string $ieEffacement       = null;
    public ?string $ieStation          = null;
    public ?string $iePresentation     = null;
    public ?string $ieMembranes        = null;
    public ?string $ieOtherFindings    = null;

    // ── Fetal Assessment ─────────────────────────────────────────────────────
    public ?string $fetalHeartTone    = null;
    public ?string $fetalPresentation = null;
    public ?string $fetalPosition     = null;
    public ?string $fundicHeight      = null;
    public ?string $engagement        = null;

    // ── Doctor's Orders ───────────────────────────────────────────────────────
    public string $orderText = '';

    // ── Admission decision ────────────────────────────────────────────────────
    public bool $admitToOb = false;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor/ob-patients');
            return;
        }

        $this->visit = Visit::with(['patient', 'obRecord', 'doctorsOrders'])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor/ob-patients');
            return;
        }

        if ($this->visit->visit_type !== 'OB') {
            Notification::make()->title('This is not an OB visit.')->warning()->send();
            $this->redirect('/doctor/ob-patients');
            return;
        }

        $this->obRecord   = $this->visit->obRecord;
        $this->isAdmitted = $this->visit->status === 'admitted';

        $patient               = $this->visit->patient;
        $this->patientName     = $patient->display_name ?? $patient->full_name;
        $this->caseNo          = $patient->case_no ?? $patient->temporary_case_no;
        $this->chiefComplaint  = $this->visit->chief_complaint;

        // Triage vitals: load the most recent vital record for this visit
        $latestVital = Vital::where('visit_id', $this->visitId)->latest('taken_at')->first();
        if ($latestVital) {
            $this->triageBp    = $latestVital->blood_pressure ?? null;
            $this->triagePulse = $latestVital->pulse_rate          ?? null;
            $this->triageTemp  = $latestVital->temperature    ?? null;
            $this->triageRr    = $latestVital->respiratory_rate ?? null;
        }

        // Load OB record fields set by nurse
        if ($this->obRecord) {
            $this->aog   = $this->obRecord->aog;
            $this->gptal = $this->obRecord->gptal;
            $this->lmp   = $this->obRecord->lmp?->format('M d, Y');

            // Load previously saved doctor data
            $this->ieCervicalDilation = $this->obRecord->ie_cervical_dilation;
            $this->ieEffacement       = $this->obRecord->ie_effacement;
            $this->ieStation          = $this->obRecord->ie_station;
            $this->iePresentation     = $this->obRecord->ie_presentation;
            $this->ieMembranes        = $this->obRecord->ie_membranes;
            $this->ieOtherFindings    = $this->obRecord->ie_other_findings;
            $this->fetalHeartTone     = $this->obRecord->fetal_heart_tone;
            $this->fetalPresentation  = $this->obRecord->fetal_presentation;
            $this->fetalPosition      = $this->obRecord->fetal_position;
            $this->fundicHeight       = $this->obRecord->fundic_height;
            $this->engagement         = $this->obRecord->engagement;
            $this->diagnosisOnAdmission = $this->obRecord->diagnosis_on_admission;
        }

        // Load existing orders as multiline text
        $this->orderText = $this->visit->doctorsOrders
            ->pluck('order_text')
            ->implode("\n");
    }

    public function save(): void
    {
        if (!$this->diagnosisOnAdmission) {
            Notification::make()->title('Please enter a diagnosis on admission.')->warning()->send();
            return;
        }

        DB::beginTransaction();

        try {
            // ── 1. Update ObRecord with IE, fetal assessment, and diagnosis ──
            $obData = [
                'ie_cervical_dilation'   => $this->ieCervicalDilation,
                'ie_effacement'          => $this->ieEffacement,
                'ie_station'             => $this->ieStation,
                'ie_presentation'        => $this->iePresentation,
                'ie_membranes'           => $this->ieMembranes,
                'ie_other_findings'      => $this->ieOtherFindings,
                'fetal_heart_tone'       => $this->fetalHeartTone,
                'fetal_presentation'     => $this->fetalPresentation,
                'fetal_position'         => $this->fetalPosition,
                'fundic_height'          => $this->fundicHeight,
                'engagement'             => $this->engagement,
                'diagnosis_on_admission' => $this->diagnosisOnAdmission,
            ];

            if ($this->obRecord) {
                $this->obRecord->update($obData);
            } else {
                $this->obRecord = ObRecord::create(array_merge($obData, [
                    'visit_id'   => $this->visitId,
                    'patient_id' => $this->visit->patient_id,
                    'filled_by'  => auth()->id(),
                ]));
            }

            // ── 2. Save Doctor's Orders ───────────────────────────────────────
            if (trim($this->orderText) !== '') {
                $lines = collect(explode("\n", $this->orderText))
                    ->map(fn ($l) => trim($l))
                    ->filter(fn ($l) => $l !== '')
                    ->values();

                foreach ($lines as $text) {
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

            // ── 3. Admit patient if checkbox is ticked ────────────────────────
            if ($this->admitToOb && !$this->isAdmitted) {
                $now = now();

                $this->visit->update([
                    'status'              => 'admitted',
                    'admitted_service'    => 'OB',
                    'admitting_diagnosis' => $this->diagnosisOnAdmission,
                    'doctor_admitted_at'  => $now,
                    'clerk_admitted_at'   => null,
                ]);

                // ── 4. Create AdmissionRecord (ADM-001 stub) ──────────────────
                // This follows the same pattern as PatientAssessment.
                AdmissionRecord::updateOrCreate(
                    ['visit_id' => $this->visitId],
                    [
                        'patient_id'           => $this->visit->patient_id,
                        'filled_by'            => auth()->id(),
                        'admission_date'       => $now->toDateString(),
                        'admission_time'       => $now->format('H:i'),
                        'ward_service'         => 'OB',
                        'admission_diagnosis'  => $this->diagnosisOnAdmission,
                        'patient_family_name'  => $this->visit->patient->family_name,
                        'patient_first_name'   => $this->visit->patient->first_name,
                        'patient_middle_name'  => $this->visit->patient->middle_name,
                        'sex'                  => 'Female',
                        'birthdate'            => $this->visit->patient->birthday?->toDateString(),
                        'age'                  => $this->visit->patient->age,
                        'permanent_address'    => $this->visit->patient->address,
                        'type_of_admission'    => 'New',
                    ]
                );

                // ── 5. Activity log ───────────────────────────────────────────
                if (class_exists(\App\Models\ActivityLog::class)) {
                    ActivityLog::record(
                        action:       'ob_patient_admitted',
                        category:     ActivityLog::CAT_CLINICAL,
                        subject:      $this->visit,
                        subjectLabel: ($this->visit->patient->full_name ?? $this->patientName) .
                                      ' (' . $this->caseNo . ')',
                        newValues: [
                            'status'           => 'admitted',
                            'admitted_service' => 'OB',
                            'diagnosis'        => $this->diagnosisOnAdmission,
                        ],
                        panel: 'doctor',
                    );
                }

                // ── 6. Notify clerks ──────────────────────────────────────────
                $clerks = User::where('is_active', true)
                    ->whereHas('roles', fn ($q) => $q->whereIn('name', ['clerk', 'clerk-opd', 'clerk-er']))
                    ->get();

                foreach ($clerks as $clerk) {
                    Notification::make()
                        ->title('New OB Admission — ' . $this->patientName)
                        ->body($this->diagnosisOnAdmission)
                        ->icon('heroicon-o-heart')
                        ->iconColor('success')
                        ->sendToDatabase($clerk);
                }

                $this->isAdmitted = true;
            }

            DB::commit();

            $isNewAdmission = $this->admitToOb && !$this->isAdmitted;

            Notification::make()
                ->title($isNewAdmission ? 'OB Patient Admitted' : 'Assessment Saved')
                ->icon($isNewAdmission ? 'heroicon-o-check-circle' : 'heroicon-o-clipboard-document-check')
                ->success()
                ->send();

            $this->redirect('/doctor/ob-patients');

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving Assessment')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}