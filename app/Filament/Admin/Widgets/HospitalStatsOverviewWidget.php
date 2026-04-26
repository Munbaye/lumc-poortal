<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use App\Services\HospitalMetricsService;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HospitalStatsOverviewWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        // 3 columns -> 9 cards renders as a 3x3 grid.
        return 4;
    }

    protected function getStats(): array
    {
        $today = Carbon::today();

        $opdCount = Visit::where('visit_type', 'OPD')
            ->whereDate('registered_at', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        $erCount = Visit::where('visit_type', 'ER')
            ->whereDate('registered_at', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        $totalToday = $opdCount + $erCount;

        $currentAdmitted = Visit::where('status', 'admitted')
            ->where(function ($query) {
                $query->whereNull('discharged_at')
                    ->orWhereDate('discharged_at', '>=', now());
            })
            ->distinct('patient_id')
            ->count('patient_id');

        $totalBeds = HospitalMetricsService::getTotalBedCapacity(); // operational beds (excludes maintenance)
        $occupiedBeds = HospitalMetricsService::getOccupiedBedsCount();
        $occupancyRate = $totalBeds > 0
            ? round(($occupiedBeds / $totalBeds) * 100, 2)
            : 0;

        $occupancyColor = match (true) {
            $occupancyRate >= 80 => 'danger',
            $occupancyRate >= 60 => 'warning',
            default => 'success',
        };

        $privateCount = Visit::where('payment_class', 'Private')
            ->where('status', 'admitted')
            ->distinct('patient_id')
            ->count('patient_id');

        $charityCount = Visit::where('payment_class', 'Charity')
            ->where('status', 'admitted')
            ->distinct('patient_id')
            ->count('patient_id');

        $totalPaymentClassCount = $privateCount + $charityCount;
        $privatePercentage = $totalPaymentClassCount > 0 ? round(($privateCount / $totalPaymentClassCount) * 100, 1) : 0;
        $charityPercentage = $totalPaymentClassCount > 0 ? round(($charityCount / $totalPaymentClassCount) * 100, 1) : 0;

        $dischargedToday = Visit::whereDate('discharged_at', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        return [
            Stat::make('Total Registrations Today', $totalToday)
                ->description('OPD & ER combined')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info')
                ->icon('heroicon-o-user-plus'),

            Stat::make('OPD Patients Today', $opdCount)
                ->description('Outpatient department')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('ER Patients Today', $erCount)
                ->description('Emergency room')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($erCount > 0 ? 'danger' : 'gray')
                ->icon('heroicon-o-heart'),

            Stat::make('Current Inpatients', $currentAdmitted)
                ->description('Currently admitted patients')
                ->descriptionIcon('heroicon-m-home')
                ->color('warning')
                ->icon('heroicon-o-home-modern'),

            Stat::make('Bed Occupancy Rate', $occupancyRate . '%')
                ->description($occupiedBeds . ' of ' . $totalBeds . ' beds occupied')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($occupancyColor)
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Private Patients', $privateCount)
                ->description($privatePercentage . '% of total')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('info')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Charity Patients', $charityCount)
                ->description($charityPercentage . '% of total')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('success')
                ->icon('heroicon-o-heart'),

            Stat::make('Discharged Today', $dischargedToday)
                ->description('Patients discharged today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-arrow-right-end-on-rectangle'),


        ];
    }
}
