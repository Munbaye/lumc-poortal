<?php

namespace App\Filament\Clerk\Pages;

use App\Models\ActivityLog;
use App\Models\AdmissionRecord;
use App\Models\ConsentRecord;
use App\Models\ErRecord;
use App\Models\Patient;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

use App\Filament\Clerk\Pages\PendingAdmissions;

/**
 * CompleteAdmission — 4-step wizard for the clerk after a doctor admits a patient.
 *
 * Step 1 → ER Record            (iframe — saves via fetch, reloads page)
 * Step 2 → ADM Record           (iframe — saves via fetch, reloads page)
 * Step 3 → Consent to Care      (iframe — saves via fetch, reloads page)
 * Step 4 → Review all 3 forms   (read-only iframes) + Complete Admission button
 *
 * Step detection: mount() reads DB state and auto-advances to the correct step.
 * Speed: iframes send postMessage → parent does location.reload() (no Livewire roundtrip).
 * Readonly: Step 4 iframes use ?readonly=1 to hide toolbars (clean paper view).
 * Consent: Fully integrated in Step 3 — no separate browser tab.
 */
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

    /** 1 | 2 | 3 | 4 — driven entirely by DB state on mount() */
    public int $step = 1;

    public bool $erRecordSaved      = false;
    public bool $admRecordSaved     = false;
    public bool $consentRecordSaved = false;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(PendingAdmissions::getUrl());
            return;
        }

        $this->visit = Visit::with([
            'patient', 'medicalHistory.doctor', 'latestVitals',
            'erRecord', 'admissionRecord', 'consentRecord',
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(PendingAdmissions::getUrl());
            return;
        }

        if (!$this->visit->doctor_admitted_at) {
            Notification::make()->title('Patient not yet admitted by a doctor.')->warning()->send();
            $this->redirect(PendingAdmissions::getUrl());
            return;
        }

        if ($this->visit->clerk_admitted_at) {
            Notification::make()->title('Admission already completed.')->info()->send();
            $this->redirect(PendingAdmissions::getUrl());
            return;
        }

        $this->patient = $this->visit->patient;

        // ── Auto-detect step from DB state ──────────────────────────────────
        if ($this->visit->erRecord)      { $this->erRecordSaved      = true; }
        if ($this->visit->admissionRecord){ $this->admRecordSaved    = true; }
        if ($this->visit->consentRecord) { $this->consentRecordSaved = true; }

        if ($this->erRecordSaved && $this->admRecordSaved && $this->consentRecordSaved) {
            $this->step = 4;
        } elseif ($this->erRecordSaved && $this->admRecordSaved) {
            $this->step = 3;
        } elseif ($this->erRecordSaved) {
            $this->step = 2;
        } else {
            $this->step = 1;
        }
    }

    // ── Step navigation ────────────────────────────────────────────────────────

    public function goToStep(int $s): void
    {
        match (true) {
            $s === 1                                        => $this->step = 1,
            $s === 2 && $this->erRecordSaved               => $this->step = 2,
            $s === 3 && $this->admRecordSaved              => $this->step = 3,
            $s === 4 && $this->consentRecordSaved          => $this->step = 4,
            default                                        => null,
        };
    }

    // ── Complete Admission ─────────────────────────────────────────────────────

    public function completeAdmission(): void
    {
        if (!$this->consentRecordSaved) {
            Notification::make()
                ->title('Please complete the Consent to Care form first.')
                ->warning()->send();
            return;
        }

        $adm     = $this->visit->admissionRecord;
        $er      = $this->visit->erRecord;
        $consent = $this->visit->consentRecord;

        $this->visit->update([
            'status'            => 'admitted',
            'disposition'       => 'Admitted',
            'payment_class'     => $adm?->payment_class ?? $this->visit->payment_class,
            'clerk_admitted_at' => now(),
        ]);

        // Back-fill patient demographics from ADM record
        if ($adm) {
            $this->patient->update(array_filter([
                'birthplace'           => $adm->birthplace           ?: null,
                'religion'             => $adm->religion             ?: null,
                'nationality'          => $adm->nationality          ?: null,
                'employer_name'        => $adm->employer_name        ?: null,
                'employer_address'     => $adm->employer_address     ?: null,
                'employer_phone'       => $adm->employer_phone       ?: null,
                'father_full_name'     => $adm->father_name          ?: null,
                'father_address'       => $adm->father_address       ?: null,
                'father_phone'         => $adm->father_phone         ?: null,
                'mother_maiden_name'   => $adm->mother_maiden_name   ?: null,
                'mother_address'       => $adm->mother_address       ?: null,
                'mother_phone'         => $adm->mother_phone         ?: null,
                'philhealth_id'        => $adm->philhealth_id        ?: null,
                'philhealth_type'      => $adm->philhealth_type      ?: null,
                'social_service_class' => $adm->social_service_class ?: null,
            ], fn ($v) => $v !== null));
        }

        // Back-fill visit from ER Record
        if ($er) {
            $upd = array_filter([
                'brought_by'               => $er->brought_by               ?: null,
                'condition_on_arrival'     => $er->condition_on_arrival     ?: null,
                'medico_legal'             => $er->medico_legal,
                'type_of_service'          => $er->type_of_service          ?: null,
                'notified_proper_authority'=> $er->notified_proper_authority ?: null,
            ]);
            if ($upd) {
                $this->visit->update($upd);
            }
        }

        ActivityLog::record(
            action:       ActivityLog::ACT_ADMITTED_PATIENT,
            category:     'admission',
            subject:      $this->visit,
            subjectLabel: $this->patient->full_name . ' (' . $this->patient->case_no . ')',
            newValues: [
                'clerk_admitted_at'  => now()->toDateTimeString(),
                'completed_by'       => auth()->user()->name,
                'er_record_id'       => $er?->id,
                'adm_record_id'      => $adm?->id,
                'consent_record_id'  => $consent?->id,
                'consent_section'    => $consent?->active_section,
            ],
            panel: 'clerk',
        );

        Notification::make()
            ->title('Admission completed for ' . $this->patient->full_name)
            ->success()->send();

        $this->redirect(PendingAdmissions::getUrl());
    }

    // ── URL helpers for iframes ────────────────────────────────────────────────

    /** Step 1 — editable ER Record */
    public function getErRecordFormUrl(): string
    {
        return route('forms.er-record', ['visit' => $this->visitId]);
    }

    /** Step 2 — editable ADM Record */
    public function getAdmRecordFormUrl(): string
    {
        return route('forms.adm-record', ['visit' => $this->visitId]);
    }

    /** Step 3 — editable Consent to Care */
    public function getConsentFormUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->visitId]);
    }

    /** Step 4 review — read-only (toolbar hidden) */
    public function getErRecordReadonlyUrl(): string
    {
        return route('forms.er-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getAdmRecordReadonlyUrl(): string
    {
        return route('forms.adm-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getConsentReadonlyUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->visitId]) . '?readonly=1';
    }
}