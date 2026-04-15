<?php

namespace App\Filament\Nurse\Pages;

use App\Models\ActivityLog;
use App\Models\DoctorsOrder;
use App\Models\IvFluidEntry;
use App\Models\MarDateColumn;
use App\Models\MarEntry;
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

    public string $activeTab      = 'orders';
    public ?int   $confirmCarryId = null;

    // ── FDAR note form ─────────────────────────────────────────────────────────
    public bool   $addingNote = false;
    public string $fdarF      = '';
    public string $fdarD      = '';
    public string $fdarA      = '';
    public string $fdarR      = '';
    public string $fdarShift  = '';

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

    // ── IV Fluid / Blood Transfusion form ──────────────────────────────────────
    public bool   $addingIv         = false;
    public string $ivDateStarted    = '';
    public string $ivTimeStarted    = '';
    public int    $ivBottleNumber   = 1;
    public string $ivSolution       = '';

    // Edit mode
    public ?int   $editingIvId      = null;
    public string $ivConsumedAt     = '';
    public string $ivRemarks        = '';

    // ── MAR state ─────────────────────────────────────────────────────────────
    /**
     * Inline-edit buffer: keyed as "entryId|date|shift" => time string.
     * Populated on the fly as the nurse types; saved per-cell on blur.
     */
    public array  $marCells        = [];
    /** Date being added to the column set. */
    public string $marNewDate      = '';
    /** Medication name for a new row being added. */
    public string $marNewMedName   = '';
    /** Whether the "add medication row" mini-form is open. */
    public bool   $marAddingMed    = false;

    // ── TPR Urine & Stool state ───────────────────────────────────────────────────
    public bool   $tprAddingIo    = false;
    public string $tprIoDate      = '';
    public string $tprIoShift     = '';
    public ?int   $tprIoUrine     = null;
    public ?int   $tprIoStool     = null;
    public string $tprIoStoolType = '';
    public string $tprIoNotes     = '';
    public ?int   $tprIoEditId    = null;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(PatientList::getUrl());
            return;
        }
        $this->loadVisit();

        if ($this->visit && $this->isReadonly) {
            $this->activeTab = 'forms';
        }
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

    // ── Computed: IV fluid entries ─────────────────────────────────────────────

    public function getAllIvEntriesProperty()
    {
        return IvFluidEntry::where('visit_id', $this->visitId)
            ->orderBy('date_started', 'asc')
            ->orderBy('time_started', 'asc')
            ->orderBy('bottle_number', 'asc')
            ->get();
    }

    public function getIvEntriesCountProperty(): int
    {
        return IvFluidEntry::where('visit_id', $this->visitId)->count();
    }

    public function getMarDateColumnsProperty(): MarDateColumn
    {
        return MarDateColumn::forVisit($this->visitId);
    }

    public function getMarEntriesProperty()
    {
        return MarEntry::where('visit_id', $this->visitId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getMarEntriesCountProperty(): int
    {
        return MarEntry::where('visit_id', $this->visitId)->count();
    }

    public function getTprIoEntriesProperty()
    {
        return \App\Models\TprIoEntry::where('visit_id', $this->visitId)
            ->orderBy('date')
            ->orderByRaw("FIELD(shift, '7-3', '3-11', '11-7')")
            ->get();
    }

    // ── Readonly mode (past / completed visits) ───────────────────────────────

    public function getIsReadonlyProperty(): bool
    {
        if (!$this->visit) return true;
        return !($this->visit->status === 'admitted'
            && $this->visit->clerk_admitted_at !== null
            && $this->visit->discharged_at === null);
    }

    // ── Tab navigation ─────────────────────────────────────────────────────────

    public function setTab(string $tab): void
    {
        $this->activeTab      = $tab;
        $this->confirmCarryId = null;
        $this->addingNote     = false;
        $this->addingVital    = false;
        $this->addingIv       = false;
        $this->editingIvId    = null;
        $this->marAddingMed   = false;
        $this->marNewDate     = '';
        $this->marNewMedName  = '';
        $this->tprAddingIo = false;
        $this->tprIoEditId = null;
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
            $this->fdarF     = '';
            $this->fdarD     = '';
            $this->fdarA     = '';
            $this->fdarR     = '';
            $this->fdarShift = '';
        }
    }

    public function saveNote(): void
    {
        if (!filled($this->fdarF) && !filled($this->fdarD)
            && !filled($this->fdarA) && !filled($this->fdarR)) {
            Notification::make()->title('Please fill in at least one FDAR field.')->warning()->send();
            return;
        }

        if (!filled($this->fdarShift) || !in_array($this->fdarShift, NursesNote::SHIFTS)) {
            Notification::make()->title('Please select your shift (7-3, 3-11, or 11-7).')->warning()->send();
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
            'shift'    => $this->fdarShift,
        ]);

        ActivityLog::record(
            action:       'added_nurses_note',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $note,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: [
                'note_id' => $note->id,
                'shift'   => $note->shift,
                'F'       => $note->focus    ? \Str::limit($note->focus,    80) : null,
                'D'       => $note->data     ? \Str::limit($note->data,     80) : null,
                'A'       => $note->action   ? \Str::limit($note->action,   80) : null,
                'R'       => $note->response ? \Str::limit($note->response, 80) : null,
                'nurse'   => auth()->user()->name,
            ],
            panel: 'nurse',
        );

        $this->addingNote = false;
        $this->fdarF      = '';
        $this->fdarD      = '';
        $this->fdarA      = '';
        $this->fdarR      = '';
        $this->fdarShift  = '';
        $this->loadVisit();
        Notification::make()->title("Nurse's note saved.")->success()->send();
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

    // ── IV Fluid / Blood Transfusion ───────────────────────────────────────────

    public function openAddIv(): void
    {
        $this->addingIv      = true;
        $this->editingIvId   = null;
        $this->ivDateStarted = now()->timezone('Asia/Manila')->format('Y-m-d');
        $this->ivTimeStarted = now()->timezone('Asia/Manila')->format('H:i');

        // Auto-suggest next bottle number
        $lastBottle = IvFluidEntry::where('visit_id', $this->visitId)->max('bottle_number');
        $this->ivBottleNumber = $lastBottle ? ($lastBottle + 1) : 1;

        $this->ivSolution   = '';
        $this->ivConsumedAt = '';
        $this->ivRemarks    = '';
    }

    public function cancelAddIv(): void
    {
        $this->addingIv    = false;
        $this->editingIvId = null;
    }

    public function saveIvEntry(): void
    {
        if (!filled($this->ivDateStarted)) {
            Notification::make()->title('Date Started is required.')->warning()->send();
            return;
        }
        if (!filled($this->ivTimeStarted)) {
            Notification::make()->title('Time Started is required.')->warning()->send();
            return;
        }
        if (!filled($this->ivSolution)) {
            Notification::make()->title('IV Solution / Blood Product is required.')->warning()->send();
            return;
        }
        if (!$this->ivBottleNumber || $this->ivBottleNumber < 1) {
            Notification::make()->title('Bottle/Bag Number must be at least 1.')->warning()->send();
            return;
        }

        $consumedAt = null;
        if (filled($this->ivConsumedAt)) {
            $consumedAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $this->ivConsumedAt, 'Asia/Manila');
        }

        $entry = IvFluidEntry::create([
            'visit_id'      => $this->visitId,
            'patient_id'    => $this->visit->patient_id,
            'recorded_by'   => auth()->id(),
            'date_started'  => $this->ivDateStarted,
            'time_started'  => $this->ivTimeStarted . ':00',
            'bottle_number' => $this->ivBottleNumber,
            'iv_solution'   => trim($this->ivSolution),
            'consumed_at'   => $consumedAt,
            'remarks'       => trim($this->ivRemarks) ?: null,
            'nurse_name'    => auth()->user()->name,
        ]);

        ActivityLog::record(
            action:       'added_iv_fluid_entry',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $entry,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: array_filter([
                'entry_id'      => $entry->id,
                'bottle_number' => $entry->bottle_number,
                'iv_solution'   => $entry->iv_solution,
                'date_started'  => $entry->date_started->format('Y-m-d'),
                'time_started'  => $entry->time_started,
                'consumed_at'   => $consumedAt?->toDateTimeString(),
                'remarks'       => $entry->remarks,
                'nurse'         => auth()->user()->name,
            ]),
            panel: 'nurse',
        );

        $this->addingIv = false;
        Notification::make()->title('IV / Blood transfusion entry saved.')->success()->send();
    }

    public function openEditIv(int $entryId): void
    {
        $entry = IvFluidEntry::where('visit_id', $this->visitId)->find($entryId);
        if (!$entry) {
            Notification::make()->title('Entry not found.')->danger()->send();
            return;
        }

        $this->editingIvId  = $entryId;
        $this->addingIv     = false;

        // Pre-fill only the editable fields
        $this->ivConsumedAt = $entry->consumed_at
            ? $entry->consumed_at->timezone('Asia/Manila')->format('Y-m-d\TH:i')
            : '';
        $this->ivRemarks    = $entry->remarks ?? '';
    }

    public function saveIvEdit(): void
    {
        $entry = IvFluidEntry::where('visit_id', $this->visitId)->find($this->editingIvId);
        if (!$entry) {
            Notification::make()->title('Entry not found.')->danger()->send();
            $this->editingIvId = null;
            return;
        }

        $consumedAt = null;
        if (filled($this->ivConsumedAt)) {
            $consumedAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $this->ivConsumedAt, 'Asia/Manila');
        }

        $old = [
            'consumed_at' => $entry->consumed_at?->toDateTimeString(),
            'remarks'     => $entry->remarks,
        ];

        $entry->update([
            'consumed_at' => $consumedAt,
            'remarks'     => trim($this->ivRemarks) ?: null,
            'edited_by'   => auth()->id(),
            'editor_name' => auth()->user()->name,
            'edited_at'   => now(),
        ]);

        ActivityLog::record(
            action:       'edited_iv_fluid_entry',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $entry,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            oldValues: $old,
            newValues: [
                'consumed_at' => $consumedAt?->toDateTimeString(),
                'remarks'     => $entry->remarks,
                'edited_by'   => auth()->user()->name,
            ],
            panel: 'nurse',
        );

        $this->editingIvId = null;
        Notification::make()->title('Entry updated.')->success()->send();
    }

    public function cancelEditIv(): void
    {
        $this->editingIvId = null;
    }

    // ── MAR ────────────────────────────────────────────────────────────────────

    /** Add a new date column to this visit's MAR. */
    public function marAddDate(): void
    {
        if (!filled($this->marNewDate)) {
            Notification::make()->title('Please pick a date.')->warning()->send();
            return;
        }
        $cols = MarDateColumn::forVisit($this->visitId);
        if (count($cols->dates ?? []) >= 31) {
            Notification::make()->title('Maximum 31 date columns reached.')->warning()->send();
            return;
        }
        if (in_array($this->marNewDate, $cols->dates ?? [])) {
            Notification::make()->title('That date column already exists.')->warning()->send();
            return;
        }
        $cols->addDate($this->marNewDate);
        $this->marNewDate = '';
        Notification::make()->title('Date column added.')->success()->send();
    }

    /** Remove a date column (and its data from all entries). */
    public function marRemoveDate(string $date): void
    {
        MarDateColumn::forVisit($this->visitId)->removeDate($date);
        // Also scrub the date key from all entry data
        MarEntry::where('visit_id', $this->visitId)->each(function (MarEntry $entry) use ($date) {
            $data = $entry->administration_data ?? [];
            unset($data[$date]);
            $entry->administration_data = $data;
            $entry->save();
        });
        Notification::make()->title('Date column removed.')->success()->send();
    }

    /** Save a single cell (date + shift) for a given entry. Called on blur. */
    public function marSaveCell(int $entryId, string $date, string $shift, string $time): void
    {
        $entry = MarEntry::where('visit_id', $this->visitId)->find($entryId);
        if (!$entry) return;

        // Validate time format — allow empty or HH:MM
        $time = trim($time);
        if (filled($time) && !preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            Notification::make()->title('Invalid time format. Use HH:MM (e.g. 08:30).')->warning()->send();
            return;
        }

        $data = $entry->administration_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = ['7-3' => '', '3-11' => '', '11-7' => ''];
        }
        $data[$date][$shift] = $time;
        $entry->administration_data = $data;
        $entry->save();
    }

    /** Add a new medication row. */
    public function marAddMedication(): void
    {
        $maxOrder = MarEntry::where('visit_id', $this->visitId)->max('sort_order') ?? 0;

        MarEntry::create([
            'visit_id'            => $this->visitId,
            'patient_id'          => $this->visit->patient_id,
            'created_by'          => auth()->id(),
            'medication_name'     => '',          // blank — nurse types inline
            'administration_data' => [],
            'sort_order'          => $maxOrder + 1,
        ]);
    }

    /** Update a medication name inline. */
    public function marUpdateMedName(int $entryId, string $name): void
    {
        $entry = MarEntry::where('visit_id', $this->visitId)->find($entryId);
        if (!$entry) return;
        $entry->medication_name = trim($name);
        $entry->save();
    }

    /** Delete a medication row. */
    public function marDeleteMed(int $entryId): void
    {
        $entry = MarEntry::where('visit_id', $this->visitId)->find($entryId);
        if (!$entry) return;
        $entry->delete();
        Notification::make()->title('Medication row removed.')->success()->send();
    }

    // ── TPR Urine & Stool ─────────────────────────────────────────────────────────

    public function tprOpenAddIo(): void
    {
        $this->tprAddingIo    = true;
        $this->tprIoEditId    = null;
        $this->tprIoDate      = now()->timezone('Asia/Manila')->toDateString();
        $this->tprIoShift     = '';
        $this->tprIoUrine     = null;
        $this->tprIoStool     = null;
        $this->tprIoStoolType = '';
        $this->tprIoNotes     = '';
    }

    public function tprSaveIo(): void
    {
        if (!filled($this->tprIoDate)) {
            Notification::make()->title('Date is required.')->warning()->send();
            return;
        }
        if (!filled($this->tprIoShift) || !in_array($this->tprIoShift, \App\Models\TprIoEntry::SHIFTS)) {
            Notification::make()->title('Please select a shift.')->warning()->send();
            return;
        }

        $data = [
            'visit_id'     => $this->visitId,
            'patient_id'   => $this->visit->patient_id,
            'recorded_by'  => auth()->id(),
            'nurse_name'   => auth()->user()->name,
            'date'         => $this->tprIoDate,
            'shift'        => $this->tprIoShift,
            'urine_ml'     => $this->tprIoUrine  ?: null,
            'stool_count'  => $this->tprIoStool  ?: null,
            'stool_type'   => filled($this->tprIoStoolType) ? $this->tprIoStoolType : null,
            'notes'        => trim($this->tprIoNotes) ?: null,
        ];

        if ($this->tprIoEditId) {
            \App\Models\TprIoEntry::where('visit_id', $this->visitId)
                ->find($this->tprIoEditId)
                ?->update($data);
            Notification::make()->title('Entry updated.')->success()->send();
        } else {
            \App\Models\TprIoEntry::create($data);
            Notification::make()->title('I&O entry saved.')->success()->send();
        }

        $this->tprAddingIo = false;
        $this->tprIoEditId = null;
    }

    public function tprOpenEditIo(int $id): void
    {
        $entry = \App\Models\TprIoEntry::where('visit_id', $this->visitId)->find($id);
        if (!$entry) return;

        $this->tprIoEditId    = $id;
        $this->tprAddingIo    = false;
        $this->tprIoDate      = $entry->date->toDateString();
        $this->tprIoShift     = $entry->shift;
        $this->tprIoUrine     = $entry->urine_ml;
        $this->tprIoStool     = $entry->stool_count;
        $this->tprIoStoolType = $entry->stool_type ?? '';
        $this->tprIoNotes     = $entry->notes ?? '';
    }

    public function tprDeleteIo(int $id): void
    {
        \App\Models\TprIoEntry::where('visit_id', $this->visitId)->find($id)?->delete();
        Notification::make()->title('Entry removed.')->success()->send();
    }

    public function tprCancelIo(): void
    {
        $this->tprAddingIo = false;
        $this->tprIoEditId = null;
    }

    // ── Patient Forms URL helpers ──────────────────────────────────────────────

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

    public function getNursesNotesUrl(): string
    {
        return route('forms.nurses-notes', ['visit' => $this->visitId]);
    }

    /**
     * URL to the dedicated Patient History page for this patient.
     */
    public function getPatientHistoryUrl(): string
    {
        return \App\Filament\Nurse\Pages\PatientHistory::getUrl([
            'patientId' => $this->visit?->patient_id,
        ]);
    }

    public function goBack(): void { $this->redirect(PatientList::getUrl()); }
}