<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentClassWidget extends ChartWidget
{
    protected static ?string $heading = 'Patient Payment Class Distribution';
    protected static ?int $contentHeight = 300;

    protected function getData(): array
    {
        $data = Visit::select('payment_class', DB::raw('COUNT(DISTINCT patient_id) as count'))
            ->whereNotNull('payment_class')
            ->groupBy('payment_class')
            ->get();

        $labels = $data->pluck('payment_class')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Patient Count',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#3b82f6', // blue for Private
                        '#10b981', // green for Charity
                    ],
                    'borderColor' => '#fff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
