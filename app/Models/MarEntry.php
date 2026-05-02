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
     * Get all administered times for a specific date and shift.
     * Returns an array of time strings (HH:MM). Empty array if nothing recorded.
     * Backward-compatible: if the stored value is a plain string, wraps it.
     */
    public function getTimes(string $date, string $shift): array
    {
        $val = $this->administration_data[$date][$shift] ?? [];
        // Backward-compat: old rows stored a plain string
        if (is_string($val)) {
            return $val !== '' ? [$val] : [];
        }
        return array_values(array_filter((array) $val, fn($t) => $t !== ''));
    }

    /**
     * @deprecated Use getTimes() for new code. Kept so old calls don't break.
     */
    public function getTime(string $date, string $shift): string
    {
        $times = $this->getTimes($date, $shift);
        return implode(', ', $times);
    }

    /**
     * Add a time entry to a date/shift slot and persist.
     */
    public function addTime(string $date, string $shift, string $time): void
    {
        $data = $this->administration_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = ['7-3' => [], '3-11' => [], '11-7' => []];
        }
        $existing = $this->getTimes($date, $shift);
        if ($time !== '' && !in_array($time, $existing)) {
            $existing[] = $time;
            sort($existing);
        }
        $data[$date][$shift] = $existing;
        $this->administration_data = $data;
        $this->save();
    }

    /**
     * Remove a specific time entry from a date/shift slot and persist.
     */
    public function removeTime(string $date, string $shift, string $time): void
    {
        $data = $this->administration_data ?? [];
        $existing = $this->getTimes($date, $shift);
        $data[$date][$shift] = array_values(array_filter($existing, fn($t) => $t !== $time));
        $this->administration_data = $data;
        $this->save();
    }

    /**
     * @deprecated Kept for backward-compat. Prefer addTime/removeTime.
     */
    public function setTime(string $date, string $shift, string $time): void
    {
        $data = $this->administration_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = ['7-3' => [], '3-11' => [], '11-7' => []];
        }
        $data[$date][$shift] = $time !== '' ? [$time] : [];
        $this->administration_data = $data;
        $this->save();
    }
}