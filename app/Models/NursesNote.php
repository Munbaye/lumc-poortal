<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * NursesNote — stores a single FDAR-format nursing note for a visit.
 *
 * FDAR:
 *   F — Focus    : the nursing diagnosis / patient problem / concern
 *   D — Data     : subjective and objective data supporting the focus
 *   A — Action   : nursing interventions performed
 *   R — Response : patient's response to the interventions
 *
 * Shift options: 7-3 | 3-11 | 11-7
 */
class NursesNote extends Model
{
    protected $fillable = [
        'visit_id',
        'nurse_id',
        'focus',
        'data',
        'action',
        'response',
        'noted_at',
        'shift',
    ];

    protected $casts = [
        'noted_at' => 'datetime',
    ];

    /** Valid shift values. */
    const SHIFTS = ['7-3', '3-11', '11-7'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (NursesNote $note) {
            if (empty($note->noted_at)) {
                $note->noted_at = now();
            }
        });
    }

    /**
     * True if at least one FDAR field has content.
     */
    public function hasContent(): bool
    {
        return filled($this->focus)
            || filled($this->data)
            || filled($this->action)
            || filled($this->response);
    }

    /**
     * Human-readable shift label, e.g. "7AM – 3PM".
     */
    public function getShiftLabelAttribute(): string
    {
        return match ($this->shift) {
            '7-3'   => '7AM – 3PM',
            '3-11'  => '3PM – 11PM',
            '11-7'  => '11PM – 7AM',
            default => $this->shift ?? '—',
        };
    }

    public function visit() { return $this->belongsTo(Visit::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}