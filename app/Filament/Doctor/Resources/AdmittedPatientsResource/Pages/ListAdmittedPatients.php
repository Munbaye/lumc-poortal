<?php

namespace App\Filament\Doctor\Resources\AdmittedPatientsResource\Pages;

use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAdmittedPatients extends ListRecords
{
    protected static string $resource = AdmittedPatientsResource::class;

    // ── View toggle: 'admitted' (default) | 'all' ─────────────────────────────
    public string $viewFilter = 'admitted';

    public function updatedViewFilter(): void
    {
        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        // Start from the resource's base query (applies private patient guard etc.)
        $query = parent::getTableQuery();

        if ($this->viewFilter === 'admitted') {
            $query->whereNotNull('doctor_admitted_at')
                  ->orderBy('doctor_admitted_at', 'asc');
        } else {
            // All patients — remove the admitted-only constraint that the resource base query adds,
            // then re-apply only the private-patient guard.
            // We rebuild from scratch to avoid double-applying constraints.
            $query = Visit::query()
                ->with(['patient', 'medicalHistory.doctor']);

            // Re-apply private patient guard (mirrors AdmittedPatientsResource::getEloquentQuery)
            $user = auth()->user();
            if ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('payment_class', '!=', 'Private')
                      ->orWhere('assigned_doctor_id', $user->id);
                });
            }

            $query->orderBy('registered_at', 'desc');
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}