<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'ward_id',
        'room_number',
        'classification',
        'is_aisle',
        'is_under_maintenance',
        'maintenance_notes',
        'bed_capacity',
        'is_active',
    ];

    protected $casts = [
        'is_aisle'             => 'boolean',
        'is_under_maintenance' => 'boolean',
        'is_active'            => 'boolean',
    ];

    // ── Classification constants ──────────────────────────────────────────────
    const CLASSIFICATIONS = [
        'service'  => 'Service',
        'pay_ward' => 'Pay Ward',
        'private'  => 'Private',
        'aisle'    => 'Aisle',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function activeBeds()
    {
        return $this->hasMany(Bed::class)->where('is_active', true);
    }

    public function availableBeds()
    {
        return $this->hasMany(Bed::class)
            ->where('is_active', true)
            ->where('status', 'available');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getClassificationLabelAttribute(): string
    {
        return self::CLASSIFICATIONS[$this->classification] ?? ucfirst($this->classification);
    }

    /**
     * Private rooms are always 1 bed — enforce on save.
     */
    protected static function booted(): void
    {
        static::saving(function (Room $room) {
            if ($room->classification === 'private') {
                $room->bed_capacity = 1;
                $room->is_aisle     = false;
            }
            if ($room->classification === 'aisle') {
                $room->is_aisle = true;
            }
        });
    }
}