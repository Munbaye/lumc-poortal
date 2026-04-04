<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Vital — one row of vital signs measurement for a visit.
 *
 * Used for both:
 *   • Initial registration vitals (recorded_by = clerk, source context = 'registration')
 *   • Ongoing nurse monitoring sheet entries (recorded_by = nurse)
 *
 * Paper form column mapping (Vital Signs Monitoring Sheet):
 *   Date & Time          → taken_at
 *   SpO2                 → o2_saturation
 *   CR (Cardiac Rate)    → cardiac_rate
 *   PR /min              → pulse_rate
 *   RR /min              → respiratory_rate
 *   Temp.                → temperature
 *   Neurological VS      → neurological_vs
 *   Others               → others_vs
 *   Remarks              → notes
 *
 * Additional fields stored but not on the monitoring sheet paper form:
 *   blood_pressure, height_cm, weight_kg, pain_scale, temperature_site
 */
class Vital extends Model
{
    protected $fillable = [
        // Core relationships
        'visit_id',
        'patient_id',
        'recorded_by',   // FK to users — auto-filled with auth user
        'nurse_name',    // free-text — allows interns, midwives, non-system users

        // Monitoring sheet columns
        'temperature',
        'temperature_site',
        'pulse_rate',
        'cardiac_rate',
        'respiratory_rate',
        'o2_saturation',
        'neurological_vs',
        'others_vs',
        'notes',

        // Additional fields (from registration / admission)
        'blood_pressure',
        'height_cm',
        'weight_kg',
        'pain_scale',

        // Timestamp
        'taken_at',
    ];

    protected $casts = [
        'taken_at'     => 'datetime',
        'temperature'  => 'float',
        'height_cm'    => 'float',
        'weight_kg'    => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function visit()    { return $this->belongsTo(Visit::class); }
    public function patient()  { return $this->belongsTo(Patient::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * True if any monitoring-sheet field is abnormal.
     * Used for row-level highlighting.
     */
    public function hasAbnormalValues(): bool
    {
        if ($this->temperature   && ($this->temperature   < 36.0 || $this->temperature   > 37.5)) return true;
        if ($this->pulse_rate    && ($this->pulse_rate    < 60   || $this->pulse_rate    > 100))  return true;
        if ($this->respiratory_rate && ($this->respiratory_rate < 12 || $this->respiratory_rate > 20)) return true;
        if ($this->o2_saturation && $this->o2_saturation < 95)  return true;
        return false;
    }

    /**
     * Formatted taken_at in Manila timezone for display.
     */
    public function getTakenAtDisplayAttribute(): string
    {
        return $this->taken_at
            ? $this->taken_at->timezone('Asia/Manila')->format('M j, Y H:i')
            : '—';
    }
}