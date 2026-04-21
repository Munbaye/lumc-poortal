<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceTrendWidget extends ChartWidget
{
    protected static ?int $contentHeight = 300;
    protected int | string | array $columnSpan = 'full';

    /**
     * Stable, high-contrast palette (hex).
     */
    private const SERVICE_PALETTE = [
        '#2563eb', // blue
        '#16a34a', // green
        '#f59e0b', // amber
        '#dc2626', // red
        '#7c3aed', // violet
        '#db2777', // pink
        '#0d9488', // teal
        '#ea580c', // orange
        '#4b5563', // slate
        '#0891b2', // cyan
        '#a16207', // brown-ish
        '#0f766e', // deep teal
    ];

    public function getHeading(): string
    {
        $currentMonth = Carbon::now();
        return 'Patient Admissions by Service — ' . $currentMonth->format('F Y');
    }

    private function colorForService(?string $service): string
    {
        $key = trim((string) $service);
        if ($key === '') {
            return '#6b7280'; // gray for unassigned
        }

        $idx = crc32(mb_strtolower($key)) % count(self::SERVICE_PALETTE);
        return self::SERVICE_PALETTE[$idx];
    }

    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6) {
            return "rgba(0,0,0,{$alpha})";
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $alpha = max(0, min(1, $alpha));
        $alpha = rtrim(rtrim(number_format($alpha, 2, '.', ''), '0'), '.');

        return "rgba({$r},{$g},{$b},{$alpha})";
    }

    protected function getData(): array
    {
        $now = Carbon::now();
        $startDate = $now->clone()->startOfMonth();
        $endDate = $now->clone()->endOfMonth();
        $daysInMonth = $endDate->day;

        $data = Visit::select(
            DB::raw('DATE(doctor_admitted_at) as date'),
            'admitted_service',
            DB::raw('COUNT(DISTINCT patient_id) as count')
        )
            ->whereBetween(DB::raw('DATE(doctor_admitted_at)'), [$startDate, $endDate])
            ->whereNotNull('doctor_admitted_at')
            ->groupBy(DB::raw('DATE(doctor_admitted_at)'), 'admitted_service')
            ->orderBy(DB::raw('DATE(doctor_admitted_at)'))
            ->get();

        // Get unique services
        $services = $data->pluck('admitted_service')->unique()->filter()->values()->toArray();

        // Build datasets for each service
        $datasets = [];
        $fillAlpha = 0.08; // lighter fill to differentiate overlaps

        foreach ($services as $service) {
            $serviceData = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $startDate->clone()->addDays($i - 1);
                $count = $data->where('date', $date->format('Y-m-d'))
                    ->where('admitted_service', $service)
                    ->first()
                    ?->count ?? 0;
                $serviceData[] = $count;
            }

            $borderHex = $this->colorForService($service);
            $datasets[] = [
                'label' => $service ?? 'Unassigned',
                'data' => $serviceData,
                'borderColor' => $borderHex,
                'backgroundColor' => $this->hexToRgba($borderHex, $fillAlpha),
                'tension' => 0.4,
                'fill' => true,
            ];
        }

        // Date labels for each day of the month
        $labels = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
