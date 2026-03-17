<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorsOrder extends Model
{
    protected $fillable = [
        'visit_id', 'doctor_id',
        'order_text',
        'status',       // pending | carried | discontinued
        'order_date',
        'notes',
        // legacy compat
        'is_completed', 'completed_by', 'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'order_date'   => 'datetime',
    ];

    // ── Status constants ───────────────────────────────────────────────────────
    const STATUS_PENDING       = 'pending';
    const STATUS_CARRIED       = 'carried';
    const STATUS_DISCONTINUED  = 'discontinued';

    // ── Status helpers ─────────────────────────────────────────────────────────

    public function isPending(): bool      { return $this->status === self::STATUS_PENDING; }
    public function isCarried(): bool      { return $this->status === self::STATUS_CARRIED; }
    public function isDiscontinued(): bool { return $this->status === self::STATUS_DISCONTINUED; }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CARRIED      => 'Carried',
            self::STATUS_DISCONTINUED => 'Discontinued',
            default                   => 'Pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CARRIED      => '#059669',  // green
            self::STATUS_DISCONTINUED => '#dc2626',  // red
            default                   => '#d97706',  // amber
        };
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function visit()       { return $this->belongsTo(Visit::class); }
    public function doctor()      { return $this->belongsTo(User::class, 'doctor_id'); }
    public function completedBy() { return $this->belongsTo(User::class, 'completed_by'); }
}