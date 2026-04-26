<?php

namespace App\Helpers;

class WHOGrowthChart
{
    // ============================================================
    // WHO GROWTH DATA (0-24 months)
    // ============================================================
    
    public static function getLengthBoys(): array
    {
        return [
            'months' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
            'sd3neg' => [44.2, 48.9, 52.4, 55.3, 57.6, 59.6, 61.2, 62.7, 64.0, 65.2, 66.4, 67.6, 68.6, 69.6, 70.6, 71.6, 72.5, 73.3, 74.2, 75.0, 75.8, 76.5, 77.2, 78.0, 78.7],
            'sd2neg' => [46.1, 50.8, 54.4, 57.3, 59.7, 61.7, 63.3, 64.8, 66.2, 67.5, 68.7, 69.9, 71.0, 72.1, 73.1, 74.1, 75.0, 76.0, 76.9, 77.7, 78.6, 79.4, 80.2, 81.0, 81.7],
            'sd0'    => [49.9, 54.7, 58.4, 61.4, 63.9, 65.9, 67.6, 69.2, 70.6, 72.0, 73.3, 74.5, 75.7, 76.9, 78.0, 79.1, 80.2, 81.2, 82.3, 83.2, 84.2, 85.1, 86.0, 86.9, 87.8],
            'sd2'    => [53.7, 58.6, 62.4, 65.5, 68.0, 70.1, 71.9, 73.5, 75.0, 76.5, 77.9, 79.2, 80.5, 81.8, 83.0, 84.2, 85.4, 86.5, 87.7, 88.8, 89.8, 90.9, 91.9, 92.9, 93.9],
            'sd3'    => [55.6, 60.6, 64.4, 67.6, 70.1, 72.2, 74.0, 75.7, 77.2, 78.7, 80.1, 81.5, 82.9, 84.2, 85.5, 86.7, 88.0, 89.2, 90.4, 91.5, 92.6, 93.8, 94.9, 95.9, 97.0],
        ];
    }
    
    public static function getLengthGirls(): array
    {
        return [
            'months' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
            'sd3neg' => [43.6, 47.8, 51.0, 53.5, 55.6, 57.4, 58.9, 60.3, 61.7, 62.9, 64.1, 65.2, 66.3, 67.3, 68.3, 69.3, 70.2, 71.1, 72.0, 72.8, 73.7, 74.5, 75.2, 76.0, 76.7],
            'sd2neg' => [45.4, 49.8, 53.0, 55.6, 57.8, 59.6, 61.2, 62.7, 64.0, 65.3, 66.5, 67.7, 68.9, 70.0, 71.0, 72.0, 73.0, 74.0, 74.9, 75.8, 76.7, 77.5, 78.4, 79.2, 80.0],
            'sd0'    => [49.1, 53.7, 57.1, 59.8, 62.1, 64.0, 65.7, 67.3, 68.7, 70.1, 71.5, 72.8, 74.0, 75.2, 76.4, 77.5, 78.6, 79.7, 80.7, 81.7, 82.7, 83.7, 84.6, 85.5, 86.4],
            'sd2'    => [52.9, 57.6, 61.1, 64.0, 66.4, 68.5, 70.3, 71.9, 73.5, 75.0, 76.4, 77.8, 79.2, 80.5, 81.7, 83.0, 84.2, 85.4, 86.5, 87.6, 88.7, 89.8, 90.8, 91.9, 92.9],
            'sd3'    => [54.7, 59.5, 63.2, 66.1, 68.6, 70.7, 72.5, 74.2, 75.8, 77.4, 78.9, 80.3, 81.7, 83.1, 84.4, 85.7, 87.0, 88.2, 89.4, 90.6, 91.7, 92.9, 94.0, 95.0, 96.1],
        ];
    }
    
    public static function getWeightBoys(): array
    {
        return [
            'months' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
            'sd3neg' => [2.1, 2.9, 3.8, 4.4, 4.9, 5.3, 5.7, 5.9, 6.2, 6.4, 6.6, 6.8, 6.9, 7.1, 7.2, 7.4, 7.5, 7.7, 7.8, 8.0, 8.1, 8.2, 8.4, 8.5, 8.6],
            'sd2neg' => [2.5, 3.4, 4.3, 5.0, 5.6, 6.0, 6.4, 6.7, 6.9, 7.1, 7.4, 7.6, 7.7, 7.9, 8.1, 8.3, 8.4, 8.6, 8.8, 8.9, 9.1, 9.2, 9.4, 9.5, 9.7],
            'sd0'    => [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5, 10.7, 10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2],
            'sd2'    => [4.4, 5.8, 7.1, 8.0, 8.7, 9.3, 9.8, 10.3, 10.7, 11.0, 11.4, 11.7, 12.0, 12.3, 12.6, 12.8, 13.1, 13.4, 13.7, 13.9, 14.2, 14.5, 14.7, 15.0, 15.3],
            'sd3'    => [5.0, 6.6, 8.0, 9.0, 9.7, 10.4, 10.9, 11.4, 11.9, 12.3, 12.7, 13.0, 13.3, 13.7, 14.0, 14.3, 14.6, 14.9, 15.3, 15.6, 15.9, 16.2, 16.5, 16.8, 17.1],
        ];
    }
    
    public static function getWeightGirls(): array
    {
        return [
            'months' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
            'sd3neg' => [2.0, 2.7, 3.4, 4.0, 4.4, 4.8, 5.1, 5.3, 5.6, 5.8, 5.9, 6.1, 6.3, 6.4, 6.6, 6.7, 6.9, 7.0, 7.2, 7.3, 7.5, 7.6, 7.8, 7.9, 8.1],
            'sd2neg' => [2.4, 3.2, 3.9, 4.5, 5.0, 5.4, 5.7, 6.0, 6.3, 6.5, 6.7, 6.9, 7.0, 7.2, 7.4, 7.6, 7.7, 7.9, 8.1, 8.2, 8.4, 8.6, 8.7, 8.9, 9.0],
            'sd0'    => [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2, 10.4, 10.6, 10.9, 11.1, 11.3, 11.5],
            'sd2'    => [4.2, 5.5, 6.6, 7.5, 8.2, 8.8, 9.3, 9.8, 10.2, 10.5, 10.9, 11.2, 11.5, 11.8, 12.1, 12.4, 12.6, 12.9, 13.2, 13.5, 13.7, 14.0, 14.3, 14.6, 14.8],
            'sd3'    => [4.8, 6.2, 7.5, 8.5, 9.3, 10.0, 10.6, 11.1, 11.6, 12.0, 12.4, 12.8, 13.1, 13.5, 13.8, 14.1, 14.5, 14.8, 15.1, 15.4, 15.7, 16.0, 16.4, 16.7, 17.0],
        ];
    }
    
    // ============================================================
    // CHART RENDERING (SVG)
    // ============================================================
    
    public static function renderChart(string $chartType, string $gender, array $measurements = []): string
    {
        // Get the correct data
        $data = match([$chartType, $gender]) {
            ['length', 'boy'] => self::getLengthBoys(),
            ['length', 'girl'] => self::getLengthGirls(),
            ['weight', 'boy'] => self::getWeightBoys(),
            ['weight', 'girl'] => self::getWeightGirls(),
            default => self::getLengthBoys(),
        };
        
        $width = 900;
        $height = 550;
        $paddingTop = 40;
        $paddingBottom = 60;
        $paddingLeft = 60;
        $paddingRight = 40;
        
        $chartWidth = $width - $paddingLeft - $paddingRight;
        $chartHeight = $height - $paddingTop - $paddingBottom;
        
        $months = $data['months'];
        $yMin = $chartType === 'length' ? 40 : 0;
        $yMax = $chartType === 'length' ? 100 : 20;
        $yLabel = $chartType === 'length' ? 'Length (cm)' : 'Weight (kg)';
        
        // Scale functions
        $xScale = function($month) use ($months, $paddingLeft, $chartWidth) {
            $minMonth = $months[0];
            $maxMonth = $months[count($months) - 1];
            $ratio = ($month - $minMonth) / ($maxMonth - $minMonth);
            return $paddingLeft + ($ratio * $chartWidth);
        };
        
        $yScale = function($value) use ($yMin, $yMax, $paddingTop, $chartHeight) {
            $ratio = ($value - $yMin) / ($yMax - $yMin);
            return $paddingTop + $chartHeight - ($ratio * $chartHeight);
        };
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg" style="font-family: Arial, sans-serif;">';
        
        // Background
        $svg .= '<rect width="100%" height="100%" fill="#ffffff" rx="8"/>';
        
        // Horizontal grid lines
        for ($i = 0; $i <= 10; $i++) {
            $value = $yMin + ($i * (($yMax - $yMin) / 10));
            $y = $yScale($value);
            $svg .= '<line x1="' . $paddingLeft . '" y1="' . $y . '" x2="' . ($width - $paddingRight) . '" y2="' . $y . '" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="4,4"/>';
            $svg .= '<text x="' . ($paddingLeft - 8) . '" y="' . ($y + 4) . '" text-anchor="end" font-size="10" fill="#9ca3af">' . round($value, 1) . '</text>';
        }
        
        // Vertical grid lines (every 3 months)
        foreach ([0, 3, 6, 9, 12, 15, 18, 21, 24] as $month) {
            $x = $xScale($month);
            $svg .= '<line x1="' . $x . '" y1="' . $paddingTop . '" x2="' . $x . '" y2="' . ($height - $paddingBottom) . '" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="4,4"/>';
            $svg .= '<text x="' . $x . '" y="' . ($height - $paddingBottom + 20) . '" text-anchor="middle" font-size="11" fill="#6b7280">' . $month . '</text>';
        }
        
        // Axis labels
        $svg .= '<text x="' . ($width / 2) . '" y="' . ($height - 15) . '" text-anchor="middle" font-size="12" font-weight="bold" fill="#374151">Age (months)</text>';
        $svg .= '<text x="15" y="' . ($height / 2) . '" text-anchor="middle" font-size="12" font-weight="bold" fill="#374151" transform="rotate(-90, 15, ' . ($height / 2) . ')">' . $yLabel . '</text>';
        
        // Plot curves
        $lines = [
            ['data' => $data['sd3neg'], 'color' => '#dc2626', 'width' => 1.5],
            ['data' => $data['sd2neg'], 'color' => '#f97316', 'width' => 1.5],
            ['data' => $data['sd0'], 'color' => '#10b981', 'width' => 3],
            ['data' => $data['sd2'], 'color' => '#f97316', 'width' => 1.5],
            ['data' => $data['sd3'], 'color' => '#dc2626', 'width' => 1.5],
        ];
        
        foreach ($lines as $line) {
            $points = [];
            foreach ($months as $i => $month) {
                $value = $line['data'][$i];
                if ($value !== null) {
                    $x = $xScale($month);
                    $y = $yScale($value);
                    $points[] = "$x,$y";
                }
            }
            if (count($points) > 1) {
                $svg .= '<polyline points="' . implode(' ', $points) . '" fill="none" stroke="' . $line['color'] . '" stroke-width="' . $line['width'] . '" stroke-linecap="round" stroke-linejoin="round"/>';
            }
        }
        
        // Plot baby measurements
        if (!empty($measurements)) {
            $points = [];
            foreach ($measurements as $m) {
                $x = $xScale($m['age_months']);
                $y = $yScale($m['value']);
                $points[] = ['x' => $x, 'y' => $y];
                
                $svg .= '<circle cx="' . $x . '" cy="' . $y . '" r="7" fill="' . ($m['color'] ?? '#3b82f6') . '" stroke="#fff" stroke-width="2"/>';
                $tooltip = "Date: {$m['date']}\nAge: {$m['age_months']} months\nValue: {$m['value']} " . ($chartType === 'length' ? 'cm' : 'kg') . "\nZ-Score: " . ($m['z_score'] ?? '—');
                $svg .= '<title>' . htmlspecialchars($tooltip) . '</title>';
            }
            
            if (count($points) > 1) {
                $linePoints = [];
                foreach ($points as $p) {
                    $linePoints[] = $p['x'] . ',' . $p['y'];
                }
                $svg .= '<polyline points="' . implode(' ', $linePoints) . '" fill="none" stroke="#3b82f6" stroke-width="2" stroke-dasharray="6,4"/>';
            }
        }
        
        // Draw axes
        $svg .= '<line x1="' . $paddingLeft . '" y1="' . $paddingTop . '" x2="' . $paddingLeft . '" y2="' . ($height - $paddingBottom) . '" stroke="#cbd5e1" stroke-width="2"/>';
        $svg .= '<line x1="' . $paddingLeft . '" y1="' . ($height - $paddingBottom) . '" x2="' . ($width - $paddingRight) . '" y2="' . ($height - $paddingBottom) . '" stroke="#cbd5e1" stroke-width="2"/>';
        
        $svg .= '</svg>';
        
        return $svg;
    }
    
    public static function renderLegend(): string
    {
        return '
        <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb; justify-content: center;">
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 30px; height: 3px; background: #dc2626; border-radius: 3px;"></span> -3 SD (Severe under)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 30px; height: 3px; background: #f97316; border-radius: 3px;"></span> -2 SD (Under)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 40px; height: 3px; background: #10b981; border-radius: 3px;"></span> 0 SD (Median)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 30px; height: 3px; background: #f97316; border-radius: 3px;"></span> +2 SD (Over)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 30px; height: 3px; background: #dc2626; border-radius: 3px;"></span> +3 SD (Severe over)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem; font-weight: 600;">
                <span style="width: 12px; height: 12px; background: #3b82f6; border-radius: 50%;"></span> Baby\'s measurements
            </span>
        </div>';
    }
    
    public static function calculateZScore($value, $ageMonths, $gender, $measurementType): ?float
    {
        $data = match([$measurementType, $gender]) {
            ['length', 'boy'] => self::getLengthBoys(),
            ['length', 'girl'] => self::getLengthGirls(),
            ['weight', 'boy'] => self::getWeightBoys(),
            ['weight', 'girl'] => self::getWeightGirls(),
            default => null,
        };

        if (!$data || $ageMonths < 0 || $ageMonths > 24) {
            return null;
        }

        // Find closest month index
        $months = $data['months'];
        $index = 0;
        $minDiff = abs($months[0] - $ageMonths);
        for ($i = 1; $i < count($months); $i++) {
            $diff = abs($months[$i] - $ageMonths);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $index = $i;
            }
        }

        $median = $data['sd0'][$index];
        $sd2neg = $data['sd2neg'][$index];
        
        $sd = ($median - $sd2neg) / 2;
        
        if ($sd == 0) return 0;
        
        $zScore = ($value - $median) / $sd;
        return round($zScore, 2);
    }
}