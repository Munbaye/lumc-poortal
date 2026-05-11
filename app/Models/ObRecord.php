<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObRecord extends Model
{
    protected $table = 'ob_records';

    protected $fillable = [
        'visit_id', 'patient_id', 'filled_by',

        // Obstetric history
        'gravida', 'para', 'term', 'preterm', 'abortion', 'living',

        // Menstrual history
        'menarche', 'menses_interval', 'menses_duration', 'dysmenorrhea',

        // Prenatal
        'prenatal_checkup_type', 'prenatal_checkup_others', 'prenatal_visit_count',

        // Past & family history
        'past_medical_history', 'family_history',

        // Present pregnancy dates
        'lmp', 'pmp', 'edc', 'aog', 'quickening_date',

        // Symptoms
        'morning_sickness', 'abnormal_symptoms', 'edema', 'other_symptoms',

        // Contractions
        'contraction_frequency', 'contraction_duration', 'bog',

        // Physical exam
        'condition_conscious', 'condition_strength', 'condition_ambulatory',
        'heent', 'skin', 'heart', 'lungs', 'abdomen',

        // Fundic / fetal
        'fundic_height', 'fetal_presentation', 'fetal_position',
        'fetal_heart_tone', 'engagement',

        // IE
        'ie_cervical_dilation', 'ie_effacement', 'ie_station',
        'ie_presentation', 'ie_membranes', 'ie_other_findings',

        // Doctor / nurse
        'diagnosis_on_admission',
        'nurses_notes',
    ];

    protected $casts = [
        'dysmenorrhea'      => 'boolean',
        'bog'               => 'boolean',
        'abnormal_symptoms' => 'array',
        'edema'             => 'array',
        'lmp'               => 'date',
        'pmp'               => 'date',
        'edc'               => 'date',
        'quickening_date'   => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function filledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filled_by');
    }

    public function previousPregnancies(): HasMany
    {
        return $this->hasMany(ObPreviousPregnancy::class)->orderBy('gravida_order');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getGptalAttribute(): string
    {
        $g = $this->gravida ?? '?';
        $p = $this->para    ?? '?';
        $t = $this->term    ?? '?';
        $pt = $this->preterm ?? '?';
        $a = $this->abortion ?? '?';
        $l = $this->living  ?? '?';
        return "G{$g}P{$p} ({$t}-{$pt}-{$a}-{$l})";
    }
}