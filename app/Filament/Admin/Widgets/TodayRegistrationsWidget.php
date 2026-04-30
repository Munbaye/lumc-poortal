<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class TodayRegistrationsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
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
        ];
    }
}
