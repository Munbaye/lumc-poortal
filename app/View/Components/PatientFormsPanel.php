<?php

namespace App\View\Components;

use App\Models\IvFluidEntry;
use App\Models\MarEntry;
use App\Models\NursesNote;
use App\Models\Visit;
use App\Models\Vital;
use Illuminate\View\Component;

/**
 * PatientFormsPanel — shared Blade component used by Clerk, Nurse, and Doctor.
 *
 * This is the SINGLE SOURCE OF TRUTH for what forms appear in "Patient Forms".
 * To add a new form in the future, add one entry to getFormDefinitions() here.
 * No other file needs to change.
 *
 * Usage:
 *   <x-patient-forms-panel :visitId="$visitId" panel="clerk" />
 *   <x-patient-forms-panel :visitId="$visit->id" panel="nurse" />
 *   <x-patient-forms-panel :visitId="$visit->id" panel="doctor" />
 *
 * Each form definition is an array:
 *   [
 *     'key'        => unique string identifier
 *     'icon'       => emoji
 *     'label'      => display label (with form code)
 *     'url'        => fn(int $visitId): string — URL for the iframe src
 *     'print_url'  => fn(int $visitId): string|null — URL for "Open / Print" button (null = same as url)
 *     'height'     => iframe min-height in px
 *     'badge'      => fn(int $visitId): string — badge text (e.g. "Saved", "3 entries", "Not yet filled")
 *     'badge_style'=> fn(int $visitId): string — inline CSS for the badge
 *     'show_if'    => fn(Visit $visit): bool — whether to show this section at all (optional, default true)
 *     'show_iframe'=> fn(int $visitId, Visit $visit): bool — whether to show the iframe or the placeholder
 *   ]
 */
class PatientFormsPanel extends Component
{
    public int    $visitId;
    public string $panel; // 'clerk' | 'nurse' | 'doctor'

    public ?Visit $visit = null;
    public array  $forms = [];

    public function __construct(int $visitId, string $panel = 'clerk')
    {
        $this->visitId = $visitId;
        $this->panel   = $panel;

        $this->visit = Visit::with([
            'erRecord',
            'admissionRecord',
            'consentRecord',
            'medicalHistory',
        ])->find($visitId);

        $this->forms = $this->buildForms();
    }

    /**
     * ══════════════════════════════════════════════════════════════════════════
     * CENTRALIZED FORMS REGISTRY
     * Add a new form here → it appears everywhere automatically.
     * ══════════════════════════════════════════════════════════════════════════
     */
    private function buildForms(): array
    {
        $visitId = $this->visitId;
        $visit   = $this->visit;

        $savedBadge   = 'background:#d1fae5;color:#065f46;';
        $missingBadge = 'background:#fef3c7;color:#92400e;';
        $infoBadge    = 'background:#dbeafe;color:#1e40af;';
        $grayBadge    = 'background:#f3f4f6;color:#6b7280;';
        $tealBadge    = 'background:#ccfbf1;color:#0f766e;';
        $purpleBadge  = 'background:#ede9fe;color:#5b21b6;';
        $roseBadge    = 'background:#fff1f2;color:#be123c;';

        return [

            // ── 1. Emergency Room Record ──────────────────────────────────────
            [
                'key'         => 'er_record',
                'icon'        => '🏥',
                'label'       => 'Emergency Room Record (ER-001)',
                'url'         => fn () => route('forms.er-record', ['visit' => $visitId]) . '?readonly=1',
                'print_url'   => fn () => route('forms.er-record', ['visit' => $visitId]) . '?readonly=1',
                'height'      => 1100,
                'show_if'     => fn () => $visit?->visit_type === 'ER',
                'show_iframe' => fn () => (bool) $visit?->erRecord,
                'badge'       => fn () => $visit?->erRecord ? 'Saved' : 'Not yet filled',
                'badge_style' => fn () => $visit?->erRecord ? $savedBadge : $missingBadge,
                'placeholder' => '📋 ER Record has not been filled out by the clerk yet.',
            ],

            // ── 2. Admission & Discharge Record ───────────────────────────────
            [
                'key'         => 'adm_record',
                'icon'        => '📋',
                'label'       => 'Admission &amp; Discharge Record (ADM-001)',
                'url'         => fn () => route('forms.adm-record', ['visit' => $visitId]) . '?readonly=1',
                'print_url'   => fn () => route('forms.adm-record', ['visit' => $visitId]) . '?readonly=1',
                'height'      => 1100,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => (bool) $visit?->admissionRecord,
                'badge'       => fn () => $visit?->admissionRecord ? 'Saved' : 'Not yet filled',
                'badge_style' => fn () => $visit?->admissionRecord ? $savedBadge : $missingBadge,
                'placeholder' => '📋 Admission &amp; Discharge Record has not been filled out by the clerk yet.',
            ],

            // ── 3. Consent to Care ────────────────────────────────────────────
            [
                'key'         => 'consent',
                'icon'        => '📄',
                'label'       => 'Consent to Care (NUR-002-1)',
                'url'         => fn () => route('forms.consent-to-care', ['visit' => $visitId]) . '?readonly=1',
                'print_url'   => fn () => route('forms.consent-to-care', ['visit' => $visitId]) . '?readonly=1',
                'height'      => 780,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => (bool) $visit?->consentRecord,
                'badge'       => fn () => $visit?->consentRecord ? 'Saved' : 'Not yet filled',
                'badge_style' => fn () => $visit?->consentRecord ? $savedBadge : $missingBadge,
                'placeholder' => '📄 Consent to Care has not been filled out by the clerk yet.',
            ],

            // ── 4. History Form ───────────────────────────────────────────────
            [
                'key'         => 'history_form',
                'icon'        => '📝',
                'label'       => 'History Form (NUR-006)',
                'url'         => fn () => route('forms.history-form', ['visit' => $visitId]) . '?readonly=1',
                'print_url'   => fn () => route('forms.history-form', ['visit' => $visitId]) . '?readonly=1',
                'height'      => 1200,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => (bool) $visit?->medicalHistory,
                'badge'       => fn () => $visit?->medicalHistory ? 'Filled' : 'Not yet assessed',
                'badge_style' => fn () => $visit?->medicalHistory ? $savedBadge : $missingBadge,
                'placeholder' => '📝 History Form will appear here once the patient has been assessed by a doctor.',
            ],

            // ── 5. Physical Examination Form ──────────────────────────────────
            [
                'key'         => 'physical_exam',
                'icon'        => '🩺',
                'label'       => 'Physical Examination Form (NUR-005)',
                'url'         => fn () => route('forms.physical-exam-form', ['visit' => $visitId]) . '?readonly=1',
                'print_url'   => fn () => route('forms.physical-exam-form', ['visit' => $visitId]) . '?readonly=1',
                'height'      => 1200,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => (bool) $visit?->medicalHistory,
                'badge'       => fn () => $visit?->medicalHistory ? 'Filled' : 'Not yet assessed',
                'badge_style' => fn () => $visit?->medicalHistory ? $savedBadge : $missingBadge,
                'placeholder' => '🩺 Physical Examination Form will appear here once the patient has been assessed by a doctor.',
            ],

            // ── 6. Vital Sign Monitoring Sheet ────────────────────────────────
            [
                'key'         => 'vitals_sheet',
                'icon'        => '📊',
                'label'       => 'Vital Sign Monitoring Sheet (NUR-014)',
                'url'         => fn () => route('forms.vital-sign-monitoring-sheet', ['visit' => $visitId]),
                'print_url'   => fn () => route('forms.vital-sign-monitoring-sheet', ['visit' => $visitId]),
                'height'      => 900,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => true, // always show — blank template is fine
                'badge'       => function () use ($visitId, $infoBadge, $grayBadge) {
                    $count = Vital::where('visit_id', $visitId)->count();
                    return $count > 0
                        ? $count . ' entr' . ($count === 1 ? 'y' : 'ies')
                        : 'No entries yet';
                },
                'badge_style' => function () use ($visitId, $infoBadge, $grayBadge) {
                    return Vital::where('visit_id', $visitId)->exists() ? $infoBadge : $grayBadge;
                },
                'placeholder' => '📊 No vital signs recorded yet.',
            ],

            // ── 7. IV / Blood Transfusion Sheet ──────────────────────────────
            [
                'key'         => 'iv_bt_sheet',
                'icon'        => '💧',
                'label'       => 'IV / Blood Transfusion Sheet (NUR-012)',
                'url'         => fn () => route('forms.iv-bt-sheet', ['visit' => $visitId]),
                'print_url'   => fn () => route('forms.iv-bt-sheet', ['visit' => $visitId]),
                'height'      => 900,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => true,
                'badge'       => function () use ($visitId, $tealBadge, $grayBadge) {
                    $count = IvFluidEntry::where('visit_id', $visitId)->count();
                    return $count > 0
                        ? $count . ' entr' . ($count === 1 ? 'y' : 'ies')
                        : 'No entries yet';
                },
                'badge_style' => function () use ($visitId, $tealBadge, $grayBadge) {
                    return IvFluidEntry::where('visit_id', $visitId)->exists() ? $tealBadge : $grayBadge;
                },
                'placeholder' => '💧 No IV / blood transfusion entries yet.',
            ],

            // ── 8. Nurse's Notes ──────────────────────────────────────────────
            [
                'key'         => 'nurses_notes',
                'icon'        => '📝',
                'label'       => "Nurse's Notes (NUR-010)",
                'url'         => fn () => route('forms.nurses-notes', ['visit' => $visitId]),
                'print_url'   => fn () => route('forms.nurses-notes', ['visit' => $visitId]),
                'height'      => 900,
                'show_if'     => fn () => true,
                'show_iframe' => fn () => true,
                'badge'       => function () use ($visitId, $purpleBadge, $grayBadge) {
                    $count = NursesNote::where('visit_id', $visitId)->count();
                    return $count > 0
                        ? $count . ' note' . ($count === 1 ? '' : 's')
                        : 'No notes yet';
                },
                'badge_style' => function () use ($visitId, $purpleBadge, $grayBadge) {
                    return NursesNote::where('visit_id', $visitId)->exists() ? $purpleBadge : $grayBadge;
                },
                'placeholder' => '📝 No nurse\'s notes yet.',
            ],

            // ── 9. Medication Administration Record ───────────────────────────
            [
                'key'         => 'mar',
                'icon'        => '💊',
                'label'       => 'Medication Administration Record (NUR-011)',
                'url'         => fn () => route('forms.medication-records', ['visit' => $visitId]),
                'print_url'   => fn () => route('forms.medication-records', ['visit' => $visitId]),
                'height'      => 900,
                'show_if'     => fn () => true,
                'show_iframe' => function () use ($visitId) {
                    return MarEntry::where('visit_id', $visitId)->exists();
                },
                'badge'       => function () use ($visitId, $roseBadge, $grayBadge) {
                    $count = MarEntry::where('visit_id', $visitId)->count();
                    return $count > 0
                        ? $count . ' medication' . ($count === 1 ? '' : 's')
                        : 'No entries yet';
                },
                'badge_style' => function () use ($visitId, $roseBadge, $grayBadge) {
                    return MarEntry::where('visit_id', $visitId)->exists() ? $roseBadge : $grayBadge;
                },
                'placeholder' => '💊 No medications recorded yet.',
            ],

            /*
             * ──────────────────────────────────────────────────────────────────
             * TO ADD A NEW FORM IN THE FUTURE, COPY ONE BLOCK ABOVE AND:
             *   1. Set a unique 'key'
             *   2. Set 'label', 'icon', 'url', 'height'
             *   3. Set 'show_if', 'show_iframe', 'badge', 'badge_style'
             *   4. Register the route in routes/web.php
             * That's it. Clerk, Nurse, and Doctor panels update automatically.
             * ──────────────────────────────────────────────────────────────────
             */
        ];
    }

    public function render()
    {
        return view('components.patient-forms-panel', [
            'visit' => $this->visit,
            'forms' => $this->forms,
        ]);
    }
}