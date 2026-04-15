<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = [
        'room_id',
        'ward_id',
        'bed_label',
        'status',
        'visit_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const STATUSES = [
        'available'   => 'Available',
        'occupied'    => 'Occupied',
        'maintenance' => 'Maintenance',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }
}