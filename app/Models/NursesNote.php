<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * NursesNote — stores a single SOAP-format nursing note for a visit.
 *
 * SOAP:
 *   S — Subjective : what the patient / family reports
 *   O — Objective  : measurable / observable data
 *   A — Assessment : nurse's clinical judgment
 *   P — Plan       : nursing interventions and next steps
 */
class NursesNote extends Model
{
    protected $fillable = [
        'visit_id',
        'nurse_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'noted_at',
    ];

    protected $casts = [
        'noted_at' => 'datetime',
    ];

    // ── Boot: set noted_at to now if not provided ─────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (NursesNote $note) {
            if (empty($note->noted_at)) {
                $note->noted_at = now();
            }
        });
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * True if at least one SOAP field has content.
     */
    public function hasContent(): bool
    {
        return filled($this->subjective)
            || filled($this->objective)
            || filled($this->assessment)
            || filled($this->plan);
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit() { return $this->belongsTo(Visit::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}