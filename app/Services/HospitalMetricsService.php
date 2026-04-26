<?php

namespace App\Services;

use App\Models\Bed;
use App\Models\SiteSetting;

class HospitalMetricsService
{
    /**
     * Get total hospital bed capacity
     * Can be configured via SiteSetting with key 'total_hospital_beds'
     * Default: 100 beds
     */
    public static function getTotalBedCapacity(): int
    {
        // Prefer the actual Bed Management records.
        // This makes dashboard metrics update immediately when admin adds/removes beds
        // or toggles a bed into/out of maintenance.
        $operationalBeds = Bed::query()
            ->where('is_active', true)
            ->whereIn('status', ['available', 'occupied'])
            ->count();

        if ($operationalBeds > 0) {
            return $operationalBeds;
        }

        // Fallback for fresh installs before beds are configured.
        return (int) SiteSetting::get('total_hospital_beds', 100);
    }

    /**
     * Set total hospital bed capacity
     */
    public static function setTotalBedCapacity(int $capacity): void
    {
        SiteSetting::set('total_hospital_beds', $capacity);
    }

    public static function getOccupiedBedsCount(): int
    {
        return Bed::query()
            ->where('is_active', true)
            ->where('status', 'occupied')
            ->count();
    }
}
