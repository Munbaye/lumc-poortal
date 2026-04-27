<?php

namespace App\Filament\Admin\Widgets;

use App\Services\HospitalMetricsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BedOccupancyRateWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $totalBeds = HospitalMetricsService::getTotalBedCapacity(); // operational beds (excludes maintenance)
        $occupiedBeds = HospitalMetricsService::getOccupiedBedsCount();
        $occupancyRate = $totalBeds > 0 
            ? round(($occupiedBeds / $totalBeds) * 100, 2)
            : 0;

        $color = match (true) {
            $occupancyRate >= 80 => 'danger',
            $occupancyRate >= 60 => 'warning',
            default => 'success',
        };

        return [
            Stat::make('Bed Occupancy Rate', $occupancyRate . '%')
                ->description($occupiedBeds . ' of ' . $totalBeds . ' beds occupied')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($color)
                ->icon('heroicon-o-rectangle-stack'),
        ];
    }
}
