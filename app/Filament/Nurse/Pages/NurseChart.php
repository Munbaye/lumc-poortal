<?php

namespace App\Filament\Nurse\Pages;

use App\Models\ActivityLog;
use App\Models\DoctorsOrder;
use App\Models\NursesNote;
use App\Models\Vital;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/**
 * NurseChart — per-patient chart page for nurses.
 *
 * URL: /nurse/nurse-chart?visitId={id}
 *
 * Doctor's Orders redesign:
 *   - Each DoctorsOrder row = one line the doctor typed.
 *   - Nurse marks each line individually (shift-safe).
 *   - carryAllOrders() marks every still-pending order at once.
 */
class NurseChart extends Page
{
    protected static ?string $navigationIcon           = 'heroicon-o-document-text';
    protected static string  $view                     = 'filament.nurse.pages.nurse-chart';
    protected static ?string $title                    = 'Patient Chart';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    public string $activeTab     = 'orders';
    public ?int   $confirmCarryId = null;

    // ── FDAR note form ─────────────────────────────────────────────────────────
    public bool   $addingNote = false;
    public string $fdarF      = '';
    public string $fdarD      = '';
    public string $fdarA      = '';
    public string $fdarR      = '';

    // ── Vitals monitoring sheet entry form ─────────────────────────────────────
    public bool   $addingVital    = false;
    public string $vitalTakenAt  = '';
    public ?float $vitalTemp     = null;
    public string $vitalTempSite = 'Axilla';
    public ?int   $vitalSpO2     = null;
    public ?int   $vitalCR       = null;
    public ?int   $vitalPR       = null;
    public ?int   $vitalRR       = null;
    public string $vitalNeuroVS  = '';
    public string $vitalOthers   = '';
    public string $vitalRemarks  = '';

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(PatientList::getUrl());
            return;
        }
        $this->loadVisit();
    }

    private function loadVisit(): void
    {
        $this->visit = Visit::with([
            'patient',
            'medicalHistory.doctor',
            'doctorsOrders' => fn ($q) => $q
                ->with(['doctor', 'completedBy'])
                ->orderBy('order_date', 'desc'),
            'nursesNotes' => fn ($q) => $q
                ->with('nurse')
                ->orderBy('noted_at', 'desc'),
            'latestVitals',
            'erRecord',
            'admissionRecord',
            'consentRecord',
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(PatientList::getUrl());
        }
    }

    // ── Computed: vitals for monitoring sheet ──────────────────────────────────

    public function getAllVitalsProperty()
    {
        return Vital::where('visit_id', $this->visitId)
            ->with('recorder')
            ->orderBy('taken_at', 'asc')
            ->get();
    }

    public function getVitalsCountProperty(): int
    {
        return Vital::where('visit_id', $this->visitId)->count();
    }

    // ── Tab navigation ─────────────────────────────────────────────────────────

    public function setTab(string $tab): void
    {
        $this->activeTab      = $tab;
        $this->confirmCarryId = null;
        $this->addingNote     = false;
        $this->addingVital    = false;
    }

    // ── Doctor's Orders — Mark as Carried (individual) ────────────────────────

    public function carryOrder(int $orderId): void
    {
        $order = DoctorsOrder::where('visit_id', $this->visitId)->find($orderId);

        if (!$order) {
            Notification::make()->title('Order not found.')->danger()->send();
            return;
        }

        if (!$order->isPending()) {
            Notification::make()
                ->title('Order is already ' . $order->status_label . '.')
                ->warning()->send();
            $this->confirmCarryId = null;
            return;
        }

        $order->update([
            'status'       => DoctorsOrder::STATUS_CARRIED,
            'is_completed' => true,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        ActivityLog::record(
            action:       'carried_doctors_order',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $order,
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')'
                . ' — Order: ' . \Str::limit($order->order_text, 60),
            newValues: [
                'order_id'   => $order->id,
                'order_text' => $order->order_text,
                'carried_by' => auth()->user()->name,
                'carried_at' => now()->toDateTimeString(),
            ],
            panel: 'nurse',
        );

        $this->confirmCarryId = null;
        $this->loadVisit();
        Notification::make()->title('Order marked as carried.')->success()->send();
    }

    // ── Doctor's Orders — Mark ALL pending as Carried ─────────────────────────

    public function carryAllOrders(): void
    {
        $pendingOrders = DoctorsOrder::where('visit_id', $this->visitId)
            ->where('status', DoctorsOrder::STATUS_PENDING)
            ->get();

        if ($pendingOrders->isEmpty()) {
            Notification::make()->title('No pending orders to carry.')->info()->send();
            return;
        }

        $now = now(); $count = 0;

        foreach ($pendingOrders as $order) {
            $order->update([
                'status'       => DoctorsOrder::STATUS_CARRIED,
                'is_completed' => true,
                'completed_by' => auth()->id(),
                'completed_at' => $now,
            ]);
            $count++;
        }

        ActivityLog::record(
            action:       'carried_all_doctors_orders',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->visit,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: ['orders_carried' => $count, 'carried_by' => auth()->user()->name, 'carried_at' => $now->toDateTimeString()],
            panel: 'nurse',
        );

        $this->loadVisit();
        Notification::make()
            ->title($count . ' order' . ($count > 1 ? 's' : '') . ' marked as carried.')
            ->success()->send();
    }

    // ── FDAR Notes ─────────────────────────────────────────────────────────────

    public function toggleAddNote(): void
    {
        $this->addingNote = !$this->addingNote;
        if ($this->addingNote) {
            $this->fdarF = $this->fdarD = $this->fdarA = $this->fdarR = '';
        }
    }

    public function saveNote(): void
    {
        if (!filled($this->fdarF) && !filled($this->fdarD)
            && !filled($this->fdarA) && !filled($this->fdarR)) {
            Notification::make()->title('Please fill in at least one FDAR field.')->warning()->send();
            return;
        }

        $note = NursesNote::create([
            'visit_id' => $this->visitId,
            'nurse_id' => auth()->id(),
            'focus'    => trim($this->fdarF) ?: null,
            'data'     => trim($this->fdarD) ?: null,
            'action'   => trim($this->fdarA) ?: null,
            'response' => trim($this->fdarR) ?: null,
            'noted_at' => now(),
        ]);

        ActivityLog::record(
            action:       'added_nurses_note',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $note,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: [
                'note_id'  => $note->id,
                'F'        => $note->focus    ? \Str::limit($note->focus,    80) : null,
                'D'        => $note->data     ? \Str::limit($note->data,     80) : null,
                'A'        => $note->action   ? \Str::limit($note->action,   80) : null,
                'R'        => $note->response ? \Str::limit($note->response, 80) : null,
                'nurse'    => auth()->user()->name,
            ],
            panel: 'nurse',
        );

        $this->addingNote = false;
        $this->fdarF = $this->fdarD = $this->fdarA = $this->fdarR = '';
        $this->loadVisit();
        Notification::make()->title('Nurse\'s note saved.')->success()->send();
    }

    // ── Vitals Monitoring Sheet ────────────────────────────────────────────────

    public function openAddVital(): void
    {
        $this->addingVital   = true;
        $this->vitalTakenAt  = now()->timezone('Asia/Manila')->format('Y-m-d\TH:i');
        $this->vitalTemp     = null;
        $this->vitalTempSite = 'Axilla';
        $this->vitalSpO2     = null;
        $this->vitalCR       = null;
        $this->vitalPR       = null;
        $this->vitalRR       = null;
        $this->vitalNeuroVS  = '';
        $this->vitalOthers   = '';
        $this->vitalRemarks  = '';
    }

    public function cancelAddVital(): void
    {
        $this->addingVital = false;
    }

    public function saveVital(): void
    {
        $hasData = $this->vitalTemp || $this->vitalSpO2 || $this->vitalCR
            || $this->vitalPR || $this->vitalRR
            || filled($this->vitalNeuroVS)
            || filled($this->vitalOthers)
            || filled($this->vitalRemarks);

        if (!$hasData) {
            Notification::make()
                ->title('Please enter at least one measurement or observation.')
                ->warning()->send();
            return;
        }

        if (!$this->vitalTakenAt) {
            Notification::make()->title('Date and time is required.')->warning()->send();
            return;
        }

        $takenAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $this->vitalTakenAt, 'Asia/Manila');

        Vital::create([
            'visit_id'         => $this->visitId,
            'patient_id'       => $this->visit->patient_id,
            'recorded_by'      => auth()->id(),
            'nurse_name'       => auth()->user()->name,
            'taken_at'         => $takenAt,
            'temperature'      => $this->vitalTemp     ?: null,
            'temperature_site' => $this->vitalTempSite ?: 'Axilla',
            'o2_saturation'    => $this->vitalSpO2     ?: null,
            'cardiac_rate'     => $this->vitalCR       ?: null,
            'pulse_rate'       => $this->vitalPR       ?: null,
            'respiratory_rate' => $this->vitalRR       ?: null,
            'neurological_vs'  => trim($this->vitalNeuroVS) ?: null,
            'others_vs'        => trim($this->vitalOthers)  ?: null,
            'notes'            => trim($this->vitalRemarks) ?: null,
        ]);

        ActivityLog::record(
            action:       ActivityLog::ACT_RECORDED_VITALS,
            category:     ActivityLog::CAT_VITALS,
            subject:      $this->visit,
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')',
            newValues: array_filter([
                'recorded_by'      => auth()->user()->name,
                'taken_at'         => $takenAt->toDateTimeString(),
                'temperature'      => $this->vitalTemp  ? $this->vitalTemp . '°C' : null,
                'spo2'             => $this->vitalSpO2  ? $this->vitalSpO2 . '%'  : null,
                'cardiac_rate'     => $this->vitalCR    ? $this->vitalCR   . ' bpm' : null,
                'pulse_rate'       => $this->vitalPR    ? $this->vitalPR   . ' bpm' : null,
                'respiratory_rate' => $this->vitalRR    ? $this->vitalRR   . '/min' : null,
                'neurological_vs'  => $this->vitalNeuroVS ?: null,
                'others'           => $this->vitalOthers  ?: null,
                'remarks'          => $this->vitalRemarks ?: null,
            ]),
            panel: 'nurse',
        );

        $this->addingVital = false;
        Notification::make()->title('Vital signs recorded.')->success()->send();
    }

    // ── Patient Forms tab — URL helpers ───────────────────────────────────────

    public function getErRecordUrl(): string
    {
        return route('forms.er-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getAdmRecordUrl(): string
    {
        return route('forms.adm-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getConsentUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getHistoryFormUrl(): string
    {
        return route('forms.history-form', ['visit' => $this->visitId]);
    }

    public function getPhysicalExamFormUrl(): string
    {
        return route('forms.physical-exam-form', ['visit' => $this->visitId]);
    }

    public function goBack(): void
    {
        $this->redirect(PatientList::getUrl());
    }
}