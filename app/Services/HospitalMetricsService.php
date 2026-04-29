<?php

namespace App\Services;

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
        return (int) SiteSetting::get('total_hospital_beds', 100);
    }

    /**
     * Set total hospital bed capacity
     */
    public static function setTotalBedCapacity(int $capacity): void
    {
        SiteSetting::set('total_hospital_beds', $capacity);
    }
}
