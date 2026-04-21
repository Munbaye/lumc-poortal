<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CurrentInpatientsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $currentAdmitted = Visit::where('status', 'admitted')
            ->where(function ($query) {
                $query->whereNull('discharged_at')
                      ->orWhereDate('discharged_at', '>=', now());
            })
            ->distinct('patient_id')
            ->count('patient_id');

        return [
            Stat::make('Current Inpatients', $currentAdmitted)
                ->description('Currently admitted patients')
                ->descriptionIcon('heroicon-m-home')
                ->color('warning')
                ->icon('heroicon-o-home-modern'),
        ];
    }
}
