<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Visit;
use App\Models\Ward;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithPagination;

/**
 * BedManagement — Nurse panel page.
 *
 * Nurses see all wards and rooms, can:
 *  - Add / remove beds per room
 *  - Toggle bed maintenance status
 *  - Toggle ROOM maintenance status (nurse can also set/clear room maintenance)
 *  - Assign an admitted patient to a bed
 *  - Unassign a patient from a bed
 *  - Transfer a bed from one room to another
 *  - Search patients live with highlight
 */
class BedManagement extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon  = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Bed Management';
    protected static ?string $title           = 'Bed Management';
    protected static string  $view            = 'filament.nurse.pages.bed-management';
    protected static ?int    $navigationSort  = 5;

    // ── Filter state ──────────────────────────────────────────────────────────
    public string $wardFilter           = '';
    public string $classificationFilter = '';
    public string $search               = '';

    // ── Add Bed modal ─────────────────────────────────────────────────────────
    public ?int   $selectedRoomId  = null;
    public string $newBedLabel     = '';
    public bool   $showAddBedModal = false;

    // ── Assign Patient modal ──────────────────────────────────────────────────
    public ?int    $assignBedId      = null;
    public ?int    $assignVisitId    = null;
    public string  $patientSearch    = '';
    public bool    $showAssignModal  = false;

    // ── Unassign modal ────────────────────────────────────────────────────────
    public ?int   $unassignBedId     = null;
    public bool   $showUnassignModal = false;

    // ── Bed Maintenance toggle modal ──────────────────────────────────────────
    public ?int   $maintenanceBedId     = null;
    public bool   $showMaintenanceModal = false;

    // ── Remove Bed modal ──────────────────────────────────────────────────────
    public ?int   $removeBedId     = null;
    public bool   $showRemoveModal = false;

    // ── Transfer Bed modal ────────────────────────────────────────────────────
    public ?int   $transferBedId     = null;
    public ?int   $transferRoomId    = null;
    public bool   $showTransferModal = false;

    // ── Room Maintenance toggle modal ─────────────────────────────────────────
    public ?int   $maintenanceRoomId       = null;
    public string $maintenanceRoomNotes    = '';
    public bool   $showRoomMaintenanceModal = false;

    // ── Listeners ─────────────────────────────────────────────────────────────
    public function updatedSearch(): void               { $this->resetPage(); }
    public function updatedWardFilter(): void           { $this->resetPage(); }
    public function updatedClassificationFilter(): void { $this->resetPage(); }
    public function updatedPatientSearch(): void        { $this->assignVisitId = null; }

    // ── Computed: wards list ──────────────────────────────────────────────────
    public function getWardsProperty()
    {
        return Ward::where('is_active', true)->orderBy('name')->get();
    }

    // ── Computed: rooms grouped by ward ──────────────────────────────────────
    public function getRoomsProperty()
    {
        return Room::query()
            ->with(['ward', 'beds.visit.patient'])
            ->where('is_active', true)
            ->when($this->wardFilter, fn ($q) => $q->where('ward_id', $this->wardFilter))
            ->when($this->classificationFilter, fn ($q) => $q->where('classification', $this->classificationFilter))
            ->when($this->search, function ($q) {
                $q->where('room_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('ward', fn ($wq) =>
                      $wq->where('name', 'like', '%' . $this->search . '%')
                  );
            })
            ->orderBy('ward_id')
            ->orderBy('room_number')
            ->get()
            ->groupBy('ward_id');
    }

    // ── Computed: all rooms for transfer dropdown ─────────────────────────────
    public function getAllRoomsProperty()
    {
        return Room::with('ward')
            ->where('is_active', true)
            ->where('is_under_maintenance', false)
            ->orderBy('ward_id')
            ->orderBy('room_number')
            ->get();
    }

    // ── Computed: admitted patients for search ────────────────────────────────
    public function getSearchedPatientsProperty()
    {
        if (trim($this->patientSearch) === '') {
            return collect();
        }

        $assignedVisitIds = Bed::whereNotNull('visit_id')
            ->where('status', 'occupied')
            ->pluck('visit_id')
            ->toArray();

        return Visit::with('patient')
            ->whereNotNull('clerk_admitted_at')
            ->whereNull('discharged_at')
            ->whereNotIn('id', $assignedVisitIds)
            ->whereHas('patient', function ($q) {
                $q->where('family_name', 'like', '%' . $this->patientSearch . '%')
                  ->orWhere('first_name', 'like', '%' . $this->patientSearch . '%')
                  ->orWhere('case_no', 'like', '%' . $this->patientSearch . '%');
            })
            ->get();
    }

    // ── Computed: all admitted+unassigned patients (for unassigned count) ─────
    public function getUnassignedPatientsProperty()
    {
        $assignedVisitIds = Bed::whereNotNull('visit_id')
            ->where('status', 'occupied')
            ->pluck('visit_id')
            ->toArray();

        return Visit::with('patient')
            ->whereNotNull('clerk_admitted_at')
            ->whereNull('discharged_at')
            ->whereNotIn('id', $assignedVisitIds)
            ->get();
    }

    // ── Stats ─────────────────────────────────────────────────────────────────
    public function getTotalBedsProperty(): int
    {
        return Bed::where('is_active', true)->count();
    }

    public function getAvailableBedsProperty(): int
    {
        return Bed::where('is_active', true)->where('status', 'available')->count();
    }

    public function getOccupiedBedsProperty(): int
    {
        return Bed::where('is_active', true)->where('status', 'occupied')->count();
    }

    public function getMaintenanceRoomsProperty(): int
    {
        return Room::where('is_active', true)->where('is_under_maintenance', true)->count();
    }

    // =========================================================================
    // ADD BED
    // =========================================================================

    public function openAddBed(int $roomId): void
    {
        $this->selectedRoomId  = $roomId;
        $this->newBedLabel     = '';
        $this->showAddBedModal = true;
    }

    public function addBed(): void
    {
        $this->validate(['newBedLabel' => 'required|string|max:50']);

        $room = Room::find($this->selectedRoomId);

        if (! $room) {
            Notification::make()->danger()->title('Room not found.')->send();
            return;
        }

        if ($room->is_under_maintenance) {
            Notification::make()->danger()->title('Room is under maintenance.')->send();
            return;
        }

        $currentCount = $room->beds()->where('is_active', true)->count();
        if ($currentCount >= $room->bed_capacity) {
            Notification::make()
                ->danger()
                ->title('Bed capacity reached.')
                ->body("This room allows a maximum of {$room->bed_capacity} bed(s).")
                ->send();
            return;
        }

        $exists = Bed::where('room_id', $room->id)
            ->where('bed_label', trim($this->newBedLabel))
            ->exists();

        if ($exists) {
            Notification::make()
                ->danger()
                ->title('Duplicate bed label.')
                ->body("A bed labeled '{$this->newBedLabel}' already exists in this room.")
                ->send();
            return;
        }

        Bed::create([
            'room_id'   => $room->id,
            'ward_id'   => $room->ward_id,
            'bed_label' => trim($this->newBedLabel),
            'status'    => 'available',
            'is_active' => true,
        ]);

        Notification::make()
            ->success()
            ->title("Bed '{$this->newBedLabel}' added to Room {$room->room_number}.")
            ->send();

        $this->showAddBedModal = false;
        $this->selectedRoomId  = null;
        $this->newBedLabel     = '';
    }

    // =========================================================================
    // REMOVE BED
    // =========================================================================

    public function openRemoveModal(int $bedId): void
    {
        $this->removeBedId     = $bedId;
        $this->showRemoveModal = true;
    }

    public function removeBed(int $bedId): void
    {
        $bed = Bed::find($bedId);
        if (! $bed) return;

        if ($bed->status === 'occupied') {
            Notification::make()
                ->danger()
                ->title('Cannot remove an occupied bed.')
                ->body('Unassign the patient first.')
                ->send();
            return;
        }

        if ($bed->room->classification === 'private') {
            Notification::make()
                ->danger()
                ->title('Cannot remove the bed from a private room.')
                ->send();
            return;
        }

        $label = $bed->bed_label;
        $bed->delete();

        Notification::make()->success()->title("Bed '{$label}' removed.")->send();

        $this->showRemoveModal = false;
        $this->removeBedId     = null;
    }

    // =========================================================================
    // TOGGLE BED MAINTENANCE
    // =========================================================================

    public function openMaintenanceModal(int $bedId): void
    {
        $this->maintenanceBedId     = $bedId;
        $this->showMaintenanceModal = true;
    }

    public function toggleBedMaintenance(int $bedId): void
    {
        $bed = Bed::find($bedId);
        if (! $bed) return;

        if ($bed->status === 'occupied') {
            Notification::make()->danger()->title('Cannot change status of an occupied bed.')->send();
            return;
        }

        $bed->update([
            'status' => $bed->status === 'maintenance' ? 'available' : 'maintenance',
        ]);

        Notification::make()->success()->title('Bed status updated.')->send();

        $this->showMaintenanceModal = false;
        $this->maintenanceBedId     = null;
    }

    // =========================================================================
    // TOGGLE ROOM MAINTENANCE (nurse can also set/clear)
    // =========================================================================

    public function openRoomMaintenanceModal(int $roomId): void
    {
        $room = Room::find($roomId);
        if (! $room) return;

        $this->maintenanceRoomId        = $roomId;
        $this->maintenanceRoomNotes     = $room->maintenance_notes ?? '';
        $this->showRoomMaintenanceModal = true;
    }

    public function toggleRoomMaintenance(): void
    {
        $room = Room::find($this->maintenanceRoomId);
        if (! $room) return;

        if ($room->is_under_maintenance) {
            // Clear maintenance
            $room->update([
                'is_under_maintenance' => false,
                'maintenance_notes'    => null,
            ]);
            Notification::make()
                ->success()
                ->title("Room {$room->room_number} marked as Operational.")
                ->send();
        } else {
            // Set maintenance
            $room->update([
                'is_under_maintenance' => true,
                'maintenance_notes'    => trim($this->maintenanceRoomNotes) ?: null,
            ]);
            Notification::make()
                ->success()
                ->title("Room {$room->room_number} set Under Maintenance.")
                ->send();
        }

        $this->showRoomMaintenanceModal = false;
        $this->maintenanceRoomId        = null;
        $this->maintenanceRoomNotes     = '';
    }

    // =========================================================================
    // ASSIGN PATIENT TO BED
    // =========================================================================

    public function openAssignModal(int $bedId): void
    {
        $this->assignBedId     = $bedId;
        $this->assignVisitId   = null;
        $this->patientSearch   = '';
        $this->showAssignModal = true;
    }

    public function selectPatient(int $visitId): void
    {
        $this->assignVisitId = $visitId;
        $visit = Visit::with('patient')->find($visitId);
        if ($visit?->patient) {
            $this->patientSearch = $visit->patient->full_name;
        }
    }

    public function assignPatient(): void
    {
        $this->validate([
            'assignVisitId' => 'required|integer|exists:visits,id',
        ]);

        $bed = Bed::find($this->assignBedId);
        if (! $bed) {
            Notification::make()->danger()->title('Bed not found.')->send();
            return;
        }

        if ($bed->status !== 'available') {
            Notification::make()->danger()->title('Bed is not available.')->send();
            return;
        }

        $alreadyAssigned = Bed::where('visit_id', $this->assignVisitId)
            ->where('status', 'occupied')
            ->exists();

        if ($alreadyAssigned) {
            Notification::make()
                ->danger()
                ->title('Patient already assigned to a bed.')
                ->body('Unassign the patient from their current bed first.')
                ->send();
            return;
        }

        $bed->update([
            'visit_id' => $this->assignVisitId,
            'status'   => 'occupied',
        ]);

        $visit = Visit::with('patient')->find($this->assignVisitId);
        $name  = $visit?->patient?->full_name ?? 'Patient';

        Notification::make()
            ->success()
            ->title("{$name} assigned to Bed {$bed->bed_label}.")
            ->send();

        $this->showAssignModal = false;
        $this->assignBedId     = null;
        $this->assignVisitId   = null;
        $this->patientSearch   = '';
    }

    // =========================================================================
    // UNASSIGN PATIENT FROM BED
    // =========================================================================

    public function openUnassignModal(int $bedId): void
    {
        $this->unassignBedId     = $bedId;
        $this->showUnassignModal = true;
    }

    public function unassignPatient(): void
    {
        $bed = Bed::with('visit.patient')->find($this->unassignBedId);
        if (! $bed) return;

        $name = $bed->visit?->patient?->full_name ?? 'Patient';

        $bed->update([
            'visit_id' => null,
            'status'   => 'available',
        ]);

        Notification::make()
            ->success()
            ->title("{$name} unassigned from Bed {$bed->bed_label}.")
            ->send();

        $this->showUnassignModal = false;
        $this->unassignBedId     = null;
    }

    // =========================================================================
    // TRANSFER BED
    // =========================================================================

    public function openTransferModal(int $bedId): void
    {
        $this->transferBedId     = $bedId;
        $this->transferRoomId    = null;
        $this->showTransferModal = true;
    }

    public function transferBed(): void
    {
        $this->validate([
            'transferRoomId' => 'required|integer|exists:rooms,id',
        ]);

        $bed = Bed::with('room')->find($this->transferBedId);
        if (! $bed) {
            Notification::make()->danger()->title('Bed not found.')->send();
            return;
        }

        if ((int) $bed->room_id === (int) $this->transferRoomId) {
            Notification::make()->warning()->title('Bed is already in that room.')->send();
            return;
        }

        $targetRoom = Room::find($this->transferRoomId);
        if (! $targetRoom) {
            Notification::make()->danger()->title('Target room not found.')->send();
            return;
        }

        if ($targetRoom->is_under_maintenance) {
            Notification::make()->danger()->title('Target room is under maintenance.')->send();
            return;
        }

        $currentCount = $targetRoom->beds()->where('is_active', true)->count();
        if ($currentCount >= $targetRoom->bed_capacity) {
            Notification::make()
                ->danger()
                ->title('Target room is at full capacity.')
                ->body("Room {$targetRoom->room_number} cannot accept more beds.")
                ->send();
            return;
        }

        $labelExists = Bed::where('room_id', $this->transferRoomId)
            ->where('bed_label', $bed->bed_label)
            ->exists();

        if ($labelExists) {
            Notification::make()
                ->danger()
                ->title('Duplicate bed label in target room.')
                ->body("A bed labeled '{$bed->bed_label}' already exists in Room {$targetRoom->room_number}.")
                ->send();
            return;
        }

        $fromRoom = $bed->room->room_number;

        $bed->update([
            'room_id' => $this->transferRoomId,
            'ward_id' => $targetRoom->ward_id,
        ]);

        Notification::make()
            ->success()
            ->title("Bed '{$bed->bed_label}' transferred.")
            ->body("Moved from Room {$fromRoom} to Room {$targetRoom->room_number}.")
            ->send();

        $this->showTransferModal = false;
        $this->transferBedId     = null;
        $this->transferRoomId    = null;
    }
}