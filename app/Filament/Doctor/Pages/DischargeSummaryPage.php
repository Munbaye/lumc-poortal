<?php

namespace App\Filament\Doctor\Pages;

use App\Models\ActivityLog;
use App\Models\DischargeSummary;
use App\Models\Visit;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

/**
 * DischargeSummaryPage — doctor fills and submits the discharge summary.
 *
 * URL:  /doctor/discharge-summary?visitId=XXX
 *
 * Registration:  add this class to the ->pages([]) list in DoctorPanelProvider.
 */
class DischargeSummaryPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static string $view = 'filament.doctor.pages.discharge-summary';
    protected static ?string $title = 'Discharge Summary';
    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;
    public ?DischargeSummary $dischargeSummary = null;

    // ── Form fields ───────────────────────────────────────────────────────────

    // Read-only demographics
    public string $patientName = '';
    public string $hospitalCaseNo = '';
    public string $wardService = '';
    public string $permanentAddress = '';
    public string $telephoneNo = '';
    public string $sex = '';
    public string $civilStatus = '';
    public string $dateAdmitted = '';

    // Editable
    public string $dateDischarged = '';
    public string $attendingPhysician = '';
    public string $admittingDiagnosis = '';
    public string $finalDiagnosis = '';
    public string $chiefComplaints = '';
    public string $briefClinicalHistory = '';
    public string $laboratoryFindings = '';
    public string $courseInWard = '';
    public string $disposition = '';

    // ── Mount ─────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (!$this->visitId)
        {
            $this->redirect('/doctor');
            return;
        }

        $this->visit = Visit::with([
            'patient',
            'medicalHistory.doctor',
            'dischargeSummary',
        ])->find($this->visitId);

        if (!$this->visit)
        {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor');
            return;
        }

        // Load existing summary or build a fresh prefilled one
        $this->dischargeSummary = $this->visit->dischargeSummary;

        if ($this->dischargeSummary)
        {
            $this->hydrateFromModel($this->dischargeSummary);
        }
        else
        {
            $prefilled = DischargeSummary::fromVisit($this->visit);
            $this->hydrateFromModel($prefilled);
        }
    }

    // ── Hydration ─────────────────────────────────────────────────────────────

    private function hydrateFromModel(DischargeSummary $ds): void
    {
        // Read-only
        $this->patientName = $ds->patient_full_name;
        $this->hospitalCaseNo = $ds->hospital_case_no ?? '';
        $this->wardService = $ds->ward_service ?? '';
        $this->permanentAddress = $ds->permanent_address ?? '';
        $this->telephoneNo = $ds->telephone_no ?? '';
        $this->sex = $ds->sex ?? '';
        $this->civilStatus = $ds->civil_status ?? '';
        $this->dateAdmitted = $ds->date_admitted
            ? Carbon::parse($ds->date_admitted)->timezone('Asia/Manila')->format('F j, Y g:i A')
            : '—';

        // Editable
        $this->dateDischarged = $ds->date_discharged
            ? Carbon::parse($ds->date_discharged)->timezone('Asia/Manila')->format('Y-m-d\TH:i')
            : now()->timezone('Asia/Manila')->format('Y-m-d\TH:i');
        $this->attendingPhysician = $ds->attending_physician ?? '';
        $this->admittingDiagnosis = $ds->admitting_diagnosis ?? '';
        $this->finalDiagnosis = $ds->final_diagnosis ?? '';
        $this->chiefComplaints = $ds->chief_complaints ?? '';
        $this->briefClinicalHistory = $ds->brief_clinical_history ?? '';
        $this->laboratoryFindings = $ds->laboratory_findings ?? '';
        $this->courseInWard = $ds->course_in_ward ?? '';
        $this->disposition = $ds->disposition ?? '';
    }

    // ── Save (draft — does NOT discharge yet) ────────────────────────────────

    public function saveDraft(): void
    {
        $this->persistSummary(finalize: false);

        Notification::make()
            ->title('Discharge summary saved as draft.')
            ->success()
            ->send();
    }

    // ── Finalize & Discharge ──────────────────────────────────────────────────

    public function finalizeAndDischarge(): void
    {
        // Validate required narrative fields
        if (empty(trim($this->finalDiagnosis)))
        {
            Notification::make()->title('Please enter the Final Diagnosis.')->warning()->send();
            return;
        }
        if (empty(trim($this->disposition)))
        {
            Notification::make()->title('Please enter the Disposition / instructions.')->warning()->send();
            return;
        }

        DB::beginTransaction();

        try
        {
            $summary = $this->persistSummary(finalize: true);

            // Mark visit as discharged
            $this->visit->update([
                'status' => 'discharged',
                'discharged_at' => Carbon::parse($this->dateDischarged)->timezone('UTC'),
                'disposition' => 'discharged',
            ]);

            // Update the AdmissionRecord discharge date if it exists
            if ($this->visit->admissionRecord)
            {
                $dischargeDt = Carbon::parse($this->dateDischarged)->timezone('Asia/Manila');
                $this->visit->admissionRecord->update([
                    'discharge_date' => $dischargeDt->toDateString(),
                    'discharge_time' => $dischargeDt->format('H:i:s'),
                    'final_diagnosis' => $this->finalDiagnosis,
                    'disposition' => $this->disposition,
                ]);
            }

            ActivityLog::record(
                action: 'patient_discharged',
                category: ActivityLog::CAT_CLINICAL,
                subject: $this->visit,
                subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
                newValues: [
                    'final_diagnosis' => $this->finalDiagnosis,
                    'discharged_at' => $this->dateDischarged,
                    'written_by' => auth()->user()->name,
                ],
                panel: 'doctor',
            );

            DB::commit();

            Notification::make()
                ->title('Patient discharged successfully.')
                ->body('Discharge summary has been finalized.')
                ->success()
                ->send();

            // Redirect to the patient chart (read-only after discharge)
            $this->redirect('/doctor/patient-chart?visitId=' . $this->visitId);

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Notification::make()
                ->title('Error finalizing discharge')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // ── Shared persistence ────────────────────────────────────────────────────

    private function persistSummary(bool $finalize): DischargeSummary
    {
        $data = [
            'visit_id' => $this->visitId,
            'patient_id' => $this->visit->patient_id,
            'written_by' => auth()->id(),

            // Demographics (re-snapshot in case they changed)
            'patient_family_name' => $this->visit->patient->family_name,
            'patient_first_name' => $this->visit->patient->first_name,
            'patient_middle_name' => $this->visit->patient->middle_name,
            'permanent_address' => $this->permanentAddress,
            'telephone_no' => $this->telephoneNo,
            'sex' => $this->sex,
            'civil_status' => $this->civilStatus,
            'hospital_case_no' => $this->hospitalCaseNo,
            'ward_service' => $this->wardService,

            // Dates
            'date_admitted' => $this->visit->clerk_admitted_at ?? $this->visit->doctor_admitted_at,
            'date_discharged' => Carbon::parse($this->dateDischarged)->timezone('UTC'),

            // Clinical
            'attending_physician' => $this->attendingPhysician,
            'admitting_diagnosis' => $this->admittingDiagnosis,
            'final_diagnosis' => $this->finalDiagnosis,
            'chief_complaints' => $this->chiefComplaints,
            'brief_clinical_history' => $this->briefClinicalHistory,
            'laboratory_findings' => $this->laboratoryFindings,
            'course_in_ward' => $this->courseInWard,
            'disposition' => $this->disposition,

            // Status
            'is_finalized' => $finalize,
            'finalized_at' => $finalize ? now() : null,
        ];

        if ($this->dischargeSummary)
        {
            $this->dischargeSummary->update($data);
            $summary = $this->dischargeSummary;
        }
        else
        {
            $summary = DischargeSummary::create($data);
            $this->dischargeSummary = $summary;
        }

        return $summary;
    }

    // ── Computed ──────────────────────────────────────────────────────────────

    public function getIsReadonlyProperty(): bool
    {
        return $this->dischargeSummary?->is_finalized === true
            || $this->visit?->status === 'discharged';
    }
}