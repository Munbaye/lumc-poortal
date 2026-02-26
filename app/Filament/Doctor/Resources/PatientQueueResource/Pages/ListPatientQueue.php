<?php

namespace App\Filament\Doctor\Resources\PatientQueueResource\Pages;

use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPatientQueue extends ListRecords
{
    protected static string $resource = PatientQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $doctorId = auth()->id();

        return [
            'charity' => Tab::make('Charity Queue')
                ->modifyQueryUsing(fn () => Visit::query()
                    ->whereDate('registered_at', today())
                    ->whereIn('status', ['vitals_done', 'assessed'])
                    ->where(function ($q) {
                        $q->where('payment_class', 'Charity')
                          ->orWhereNull('payment_class');
                    })
                    ->orderBy('registered_at', 'asc')
                )
                ->badge(
                    Visit::whereDate('registered_at', today())
                        ->whereIn('status', ['vitals_done', 'assessed'])
                        ->where(function ($q) {
                            $q->where('payment_class', 'Charity')
                              ->orWhereNull('payment_class');
                        })
                        ->count()
                ),

            'private' => Tab::make('Private Patients')
                ->modifyQueryUsing(fn () => Visit::query()
                    ->whereDate('registered_at', today())
                    ->whereIn('status', ['vitals_done', 'assessed'])
                    ->where('payment_class', 'Private')
                    ->where('assigned_doctor_id', $doctorId)
                    ->orderBy('registered_at', 'asc')
                )
                ->badge(
                    Visit::whereDate('registered_at', today())
                        ->whereIn('status', ['vitals_done', 'assessed'])
                        ->where('payment_class', 'Private')
                        ->where('assigned_doctor_id', $doctorId)
                        ->count()
                ),
        ];
    }

    // This must be public (matches parent class)
    public function getDefaultActiveTab(): ?string
    {
        return 'charity'; // Opens Charity tab by default
    }
}