<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MarEntry — one medication row in the MAR for a visit.
 *
 * administration_data JSON structure:
 *   {
 *     "2026-04-01": { "7-3": "08:00", "3-11": "",   "11-7": "" },
 *     "2026-04-02": { "7-3": "08:15", "3-11": "16:00", "11-7": "23:45" }
 *   }
 */
class MarEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'created_by',
        'medication_name',
        'administration_data',
        'sort_order',
    ];

    protected $casts = [
        'administration_data' => 'array',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Get the administered time for a specific date and shift.
     * Returns empty string if nothing recorded.
     */
    public function getTime(string $date, string $shift): string
    {
        return $this->administration_data[$date][$shift] ?? '';
    }

    /**
     * Set a single date/shift time and persist.
     */
    public function setTime(string $date, string $shift, string $time): void
    {
        $data = $this->administration_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = ['7-3' => '', '3-11' => '', '11-7' => ''];
        }
        $data[$date][$shift] = $time;
        $this->administration_data = $data;
        $this->save();
    }
}