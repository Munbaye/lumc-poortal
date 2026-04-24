<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\HospitalStatsOverviewWidget;
use App\Filament\Admin\Widgets\ServiceTrendWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getWidgets(): array
    {
        return [
            HospitalStatsOverviewWidget::class,
            ServiceTrendWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 3;
    }

    public function getWidgetGrid(): array | int | string
    {
        return 3;
    }
}
