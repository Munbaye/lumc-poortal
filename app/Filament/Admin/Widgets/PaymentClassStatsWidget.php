<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentClassStatsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $privateCount = Visit::where('payment_class', 'Private')
            ->distinct('patient_id')
            ->count('patient_id');

        $charityCount = Visit::where('payment_class', 'Charity')
            ->distinct('patient_id')
            ->count('patient_id');

        $totalCount = $privateCount + $charityCount;
        
        $privatePercentage = $totalCount > 0 ? round(($privateCount / $totalCount) * 100, 1) : 0;
        $charityPercentage = $totalCount > 0 ? round(($charityCount / $totalCount) * 100, 1) : 0;

        return [
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
        ];
    }
}
