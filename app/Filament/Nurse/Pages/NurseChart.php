<?php

namespace App\Filament\Nurse\Pages;

use App\Models\ActivityLog;
use App\Models\DoctorsOrder;
use App\Models\NursesNote;
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

    public string $activeTab    = 'orders';
    public ?int   $confirmCarryId = null;

    // SOAP note form
    public bool   $addingNote = false;
    public string $soapS      = '';
    public string $soapO      = '';
    public string $soapA      = '';
    public string $soapP      = '';

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
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(PatientList::getUrl());
        }
    }

    // ── Tab navigation ────────────────────────────────────────────────────────

    public function setTab(string $tab): void
    {
        $this->activeTab      = $tab;
        $this->confirmCarryId = null;
        $this->addingNote     = false;
    }

    // ── Doctor's Orders — Mark as Carried (individual) ───────────────────────

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

    // ── Doctor's Orders — Mark ALL pending as Carried ────────────────────────

    /**
     * Marks every still-pending order for this visit as carried at once.
     * Useful for simple one-time order sets (e.g., "NPO, CBC, CXR").
     * Each order records the same nurse + same timestamp for traceability.
     */
    public function carryAllOrders(): void
    {
        $pendingOrders = DoctorsOrder::where('visit_id', $this->visitId)
            ->where('status', DoctorsOrder::STATUS_PENDING)
            ->get();

        if ($pendingOrders->isEmpty()) {
            Notification::make()
                ->title('No pending orders to carry.')
                ->info()->send();
            return;
        }

        $now = now();
        $count = 0;

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
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')',
            newValues: [
                'orders_carried' => $count,
                'carried_by'     => auth()->user()->name,
                'carried_at'     => $now->toDateTimeString(),
            ],
            panel: 'nurse',
        );

        $this->loadVisit();
        Notification::make()
            ->title($count . ' order' . ($count > 1 ? 's' : '') . ' marked as carried.')
            ->success()->send();
    }

    // ── SOAP Notes ────────────────────────────────────────────────────────────

    public function toggleAddNote(): void
    {
        $this->addingNote = !$this->addingNote;
        if ($this->addingNote) {
            $this->soapS = '';
            $this->soapO = '';
            $this->soapA = '';
            $this->soapP = '';
        }
    }

    public function saveNote(): void
    {
        if (!filled($this->soapS) && !filled($this->soapO)
            && !filled($this->soapA) && !filled($this->soapP)) {
            Notification::make()
                ->title('Please fill in at least one SOAP field.')
                ->warning()->send();
            return;
        }

        $note = NursesNote::create([
            'visit_id'   => $this->visitId,
            'nurse_id'   => auth()->id(),
            'subjective' => trim($this->soapS) ?: null,
            'objective'  => trim($this->soapO) ?: null,
            'assessment' => trim($this->soapA) ?: null,
            'plan'       => trim($this->soapP) ?: null,
            'noted_at'   => now(),
        ]);

        ActivityLog::record(
            action:       'added_nurses_note',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $note,
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')',
            newValues: [
                'note_id'    => $note->id,
                'subjective' => $note->subjective ? \Str::limit($note->subjective, 80) : null,
                'objective'  => $note->objective  ? \Str::limit($note->objective,  80) : null,
                'assessment' => $note->assessment ? \Str::limit($note->assessment, 80) : null,
                'plan'       => $note->plan       ? \Str::limit($note->plan,       80) : null,
                'nurse'      => auth()->user()->name,
            ],
            panel: 'nurse',
        );

        $this->addingNote = false;
        $this->soapS = $this->soapO = $this->soapA = $this->soapP = '';
        $this->loadVisit();
        Notification::make()->title('Nurse\'s note saved.')->success()->send();
    }

    public function goBack(): void
    {
        $this->redirect(PatientList::getUrl());
    }
}