<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Ward;
use Filament\Pages\Page;

class PatientBedView extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Ward & Bed View';
    protected static ?string $title           = 'Ward & Bed View';
    protected static string  $view            = 'filament.nurse.pages.patient-bed-view';
    protected static ?int    $navigationSort  = 5;

    public string $wardFilter           = '';
    public string $classificationFilter = '';
    public string $search               = '';

    public function getWardsProperty()
    {
        return Ward::where('is_active', true)->orderBy('name')->get();
    }

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
}