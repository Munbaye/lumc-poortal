<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TprIoEntry — Urine & Stool recording per shift per date for a visit.
 * Used by the TPR Graphic Record tab in the Nurse Chart.
 */
class TprIoEntry extends Model
{
    protected $fillable = [
        'visit_id',
        'patient_id',
        'recorded_by',
        'nurse_name',
        'date',
        'shift',         // 7-3 | 3-11 | 11-7
        'urine_count',
        'stool_count',
        'stool_type',    // formed | loose | watery | bloody | none
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /** Valid shift values — mirrors NursesNote::SHIFTS */
    const SHIFTS = ['7-3', '3-11', '11-7'];

    const STOOL_TYPES = [
        'formed'  => 'Formed',
        'loose'   => 'Loose',
        'watery'  => 'Watery',
        'bloody'  => 'Bloody',
        'none'    => 'None',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function getShiftLabelAttribute(): string
    {
        return match ($this->shift) {
            '7-3'   => '7AM – 3PM',
            '3-11'  => '3PM – 11PM',
            '11-7'  => '11PM – 7AM',
            default => $this->shift ?? '—',
        };
    }

    public function getStoolTypeLabelAttribute(): string
    {
        return self::STOOL_TYPES[$this->stool_type] ?? ($this->stool_type ?? '—');
    }
}