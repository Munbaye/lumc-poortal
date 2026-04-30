<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NicuPhysicalExam extends Model
{
    protected $table = 'nicu_physical_exams';

    protected $fillable = [
        'visit_id', 'patient_id', 'examined_by',
        'exam_date', 'hours_after_birth',
        'apgar_birth', 'apgar_5min', 'apgar_10min',
        'general_condition',
        'head_circumference_cm', 'chest_circumference_cm', 'abdominal_circumference_cm',
        'birth_weight_g', 'birth_length_cm',
        'general_muscular_tonus',
        'skin_color', 'skin_turgor', 'skin_rash', 'skin_desquamation',
        'head_molding', 'head_scalp', 'head_fontanelles', 'head_suture',
        'face',
        'eyes_conjunctiva', 'eyes_sclera', 'eyes_pupils', 'eyes_discharge',
        'ears',
        'nose',
        'mouth_lip', 'mouth_tongue', 'mouth_palate',
        'neck_sternocleidomastoid', 'neck_fistula', 'neck_other',
        'chest_shape', 'chest_respiration', 'chest_clavicles', 'chest_breast',
        'chest_heart', 'chest_lungs',
        'abdomen',
        'spleen', 'kidneys', 'liver', 'umbilical_cord',
        'inguinal_hernia', 'diastasis_recti',
        'genitals_male', 'genitals_female',
        'extremities',
        'clubfoot', 'hip_dislocation', 'femoral_pulse', 'spine', 'anus',
        'impression',
        'pediatrician_name', 'pediatrician_signature',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examined_by');
    }

    /**
     * Get APGAR display string
     */
    public function getApgarDisplayAttribute(): string
    {
        $birth = $this->apgar_birth ?? '—';
        $five = $this->apgar_5min ?? '—';
        $ten = $this->apgar_10min ?? '—';
        return "{$birth} / {$five} / {$ten}";
    }
}