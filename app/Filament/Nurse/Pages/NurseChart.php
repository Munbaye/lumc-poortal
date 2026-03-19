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
 * Tabs:
 *   orders    — Doctor's Orders with "Mark as Carried" per pending order
 *   notes     — SOAP Nurse's Notes (add + list)
 *   mar       — Medication Administration Record (placeholder)
 *   vitals    — Vital Signs Monitoring Sheet (placeholder)
 *   iv        — IV Fluid Monitoring Sheet (placeholder)
 *   blood     — Blood Transfusion Sheet (placeholder)
 *   io        — Intake & Output (placeholder)
 *   handover  — Nursing Handover / Endorsement (placeholder)
 */
class NurseChart extends Page
{
    protected static ?string $navigationIcon           = 'heroicon-o-document-text';
    protected static string  $view                     = 'filament.nurse.pages.nurse-chart';
    protected static ?string $title                    = 'Patient Chart';
    protected static bool    $shouldRegisterNavigation = false;

    // ── URL parameter ─────────────────────────────────────────────────────────
    #[Url]
    public ?int $visitId = null;

    // ── Loaded data ───────────────────────────────────────────────────────────
    public ?Visit $visit = null;

    // ── Tab state ─────────────────────────────────────────────────────────────
    public string $activeTab = 'orders';

    // ── Carry order confirmation ──────────────────────────────────────────────
    public ?int $confirmCarryId = null;

    // ── SOAP note form ────────────────────────────────────────────────────────
    public bool   $addingNote   = false;
    public string $soapS        = '';   // Subjective
    public string $soapO        = '';   // Objective
    public string $soapA        = '';   // Assessment
    public string $soapP        = '';   // Plan

    // ─────────────────────────────────────────────────────────────────────────

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
        $this->activeTab     = $tab;
        $this->confirmCarryId = null;
        $this->addingNote    = false;
    }

    // ── Doctor's Orders — Mark as Carried ─────────────────────────────────────

    public function carryOrder(int $orderId): void
    {
        $order = DoctorsOrder::where('visit_id', $this->visitId)->find($orderId);

        if (!$order) {
            Notification::make()->title('Order not found.')->danger()->send();
            return;
        }

        if (!$order->isPending()) {
            Notification::make()->title('Order is already ' . $order->status_label . '.')->warning()->send();
            $this->confirmCarryId = null;
            return;
        }

        $order->update([
            'status'       => DoctorsOrder::STATUS_CARRIED,
            'is_completed' => true,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        // Log the action
        ActivityLog::record(
            action:       'carried_doctors_order',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $order,
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')'
                . ' — Order: ' . \Str::limit($order->order_text, 60),
            newValues: [
                'order_id'     => $order->id,
                'order_text'   => $order->order_text,
                'carried_by'   => auth()->user()->name,
                'carried_at'   => now()->toDateTimeString(),
            ],
            panel: 'nurse',
        );

        $this->confirmCarryId = null;
        $this->loadVisit();

        Notification::make()->title('Order marked as carried.')->success()->send();
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
        // At least one SOAP field must have content
        if (!filled($this->soapS) && !filled($this->soapO)
            && !filled($this->soapA) && !filled($this->soapP)) {
            Notification::make()
                ->title('Please fill in at least one SOAP field.')
                ->warning()
                ->send();
            return;
        }

        $note = NursesNote::create([
            'visit_id'    => $this->visitId,
            'nurse_id'    => auth()->id(),
            'subjective'  => trim($this->soapS) ?: null,
            'objective'   => trim($this->soapO) ?: null,
            'assessment'  => trim($this->soapA) ?: null,
            'plan'        => trim($this->soapP) ?: null,
            'noted_at'    => now(),
        ]);

        // Log the action
        ActivityLog::record(
            action:       'added_nurses_note',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $note,
            subjectLabel: $this->visit->patient->full_name
                . ' (' . $this->visit->patient->case_no . ')',
            newValues: [
                'note_id'      => $note->id,
                'subjective'   => $note->subjective ? \Str::limit($note->subjective, 80) : null,
                'objective'    => $note->objective  ? \Str::limit($note->objective,  80) : null,
                'assessment'   => $note->assessment ? \Str::limit($note->assessment, 80) : null,
                'plan'         => $note->plan       ? \Str::limit($note->plan,       80) : null,
                'nurse'        => auth()->user()->name,
            ],
            panel: 'nurse',
        );

        // Reset and reload
        $this->addingNote = false;
        $this->soapS = $this->soapO = $this->soapA = $this->soapP = '';
        $this->loadVisit();

        Notification::make()->title('Nurse\'s note saved.')->success()->send();
    }

    // ── Back to list ─────────────────────────────────────────────────────────

    public function goBack(): void
    {
        $this->redirect(PatientList::getUrl());
    }
}