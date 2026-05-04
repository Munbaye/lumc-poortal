<?php

namespace App\Filament\Doctor\Resources\AdmittedPatientsResource\Pages;

use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAdmittedPatients extends ListRecords
{
    protected static string $resource = AdmittedPatientsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $doctorId = auth()->id();

        $admittedCount = Visit::whereNotNull('doctor_admitted_at')
            ->where('status', 'admitted')
            ->where(function ($q) use ($doctorId) {
                $q->where('payment_class', 'Charity')
                  ->orWhereNull('payment_class')
                  ->orWhere(function ($q2) use ($doctorId) {
                      $q2->where('payment_class', 'Private')
                         ->where('assigned_doctor_id', $doctorId);
                  });
            })
            ->count();

        $dischargedCount = Visit::whereNotNull('doctor_admitted_at')
            ->where('status', 'discharged')
            ->where(function ($q) use ($doctorId) {
                $q->where('payment_class', 'Charity')
                  ->orWhereNull('payment_class')
                  ->orWhere(function ($q2) use ($doctorId) {
                      $q2->where('payment_class', 'Private')
                         ->where('assigned_doctor_id', $doctorId);
                  });
            })
            ->count();

        return [
            'admitted' => Tab::make('Admitted Patients')
                ->icon('heroicon-o-building-office-2')
                ->badge($admittedCount ?: null)
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'admitted')
                          ->orderBy('doctor_admitted_at', 'asc')
                ),

            'discharged' => Tab::make('Discharged')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->badge($dischargedCount ?: null)
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'discharged')
                          ->orderBy('discharged_at', 'desc')
                ),

            'all' => Tab::make('All Visits')
                ->icon('heroicon-o-clipboard-document-list')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->orderBy('doctor_admitted_at', 'desc')
                ),
        ];
    }
}