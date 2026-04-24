<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use App\Services\HospitalMetricsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BedOccupancyRateWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $activeAdmissions = Visit::where('status', 'admitted')
            ->where(function ($query) {
                $query->whereNull('discharged_at')
                      ->orWhereDate('discharged_at', '>=', now());
            })
            ->distinct('patient_id')
            ->count('patient_id');

        $totalBeds = HospitalMetricsService::getTotalBedCapacity();
        $occupancyRate = $totalBeds > 0 
            ? round(($activeAdmissions / $totalBeds) * 100, 2)
            : 0;

        $color = match (true) {
            $occupancyRate >= 80 => 'danger',
            $occupancyRate >= 60 => 'warning',
            default => 'success',
        };

        return [
            Stat::make('Bed Occupancy Rate', $occupancyRate . '%')
                ->description($activeAdmissions . ' of ' . $totalBeds . ' beds occupied')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($color)
                ->icon('heroicon-o-rectangle-stack'),
        ];
    }
}
