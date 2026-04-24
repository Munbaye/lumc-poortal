<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DischargedCountWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $today = Carbon::today();

        $dischargedToday = Visit::whereDate('discharged_at', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        $dischargedTotal = Visit::whereNotNull('discharged_at')
            ->distinct('patient_id')
            ->count('patient_id');

        return [
            Stat::make('Discharged Today', $dischargedToday)
                ->description('Patients discharged today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-arrow-right-end-on-rectangle'),

            Stat::make('Total Discharged', $dischargedTotal)
                ->description('Overall discharged patients')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info')
                ->icon('heroicon-o-document-check'),
        ];
    }
}
