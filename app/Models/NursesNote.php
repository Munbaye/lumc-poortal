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
    ];

    protected $casts = [
        'noted_at' => 'datetime',
    ];

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

    public function visit() { return $this->belongsTo(Visit::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}