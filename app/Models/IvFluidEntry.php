<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class IvFluidEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'recorded_by',
        'date_started',
        'time_started',
        'bottle_number',
        'iv_solution',
        'consumed_at',
        'remarks',
        'nurse_name',
        'edited_by',
        'editor_name',
        'edited_at',
    ];

    protected $casts = [
        'date_started' => 'date',
        'consumed_at'  => 'datetime',
        'edited_at'    => 'datetime',
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

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}