<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MarDateColumn — stores the ordered list of date columns for a visit's MAR.
 *
 * There is exactly ONE row per visit (unique on visit_id).
 * The `dates` JSON array holds ordered date strings: ["2026-04-01", ...]
 */
class MarDateColumn extends Model
{
    protected $fillable = [
        'visit_id',
        'dates',
    ];

    protected $casts = [
        'dates' => 'array',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get or create the date-columns record for a visit,
     * initialising with today's date if brand new.
     */
    public static function forVisit(int $visitId): self
    {
        return static::firstOrCreate(
            ['visit_id' => $visitId],
            ['dates'    => [\Carbon\Carbon::now('Asia/Manila')->toDateString()]]
        );
    }

    /** Add a new date column if not already present. Returns updated model. */
    public function addDate(string $date): self
    {
        $dates = $this->dates ?? [];
        if (!in_array($date, $dates)) {
            $dates[] = $date;
            sort($dates);   // keep chronological order
            $this->dates = $dates;
            $this->save();
        }
        return $this;
    }

    /** Remove a date column. Returns updated model. */
    public function removeDate(string $date): self
    {
        $this->dates = array_values(array_filter($this->dates ?? [], fn ($d) => $d !== $date));
        $this->save();
        return $this;
    }
}