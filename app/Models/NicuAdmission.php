<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NicuAdmission extends Model
{
    protected $table = 'nicu_admissions';
    
    protected $fillable = [
        'visit_id',
        'patient_id',
        'filled_by',
        
        // Birth details
        'date_time_of_birth',
        'type_of_delivery',
        'delivered_at',
        'birth_attendant',
        
        // Admission context
        'admission_status',
        'referring_facility',
        'reason_for_nicu_admission',
        
        // Measurements
        'birth_weight_grams',
        'birth_length_cm',
        'head_circumference_cm',
        'chest_circumference_cm',
        'abdominal_circumference_cm',
        
        // APGAR
        'apgar_1min',
        'apgar_5min',
        'apgar_10min',
        
        // Gestational age
        'ga_by_dates_weeks',
        'ga_by_ballard_weeks',
        'newborn_classification',
        
        // Maternal history (NUR-022-0)
        'mother_name_raw',
        'mother_age',
        'mother_gravida',
        'mother_para',
        'prenatal_checkup_site',
        'prenatal_visit_count',
        'maternal_history',
        'maternal_signs_symptoms',
        'took_multivitamins',
        'had_ultrasound',
        'had_preterm_labor',
        'steroids_given',
    ];
    
    protected $casts = [
        'date_time_of_birth' => 'datetime',
        'took_multivitamins' => 'boolean',
        'had_ultrasound'     => 'boolean',
        'had_preterm_labor'  => 'boolean',
        'birth_weight_grams' => 'decimal:2',
        'birth_length_cm'    => 'decimal:1',
    ];
    
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
    
    /**
     * Get APGAR display string
     */
    public function getApgarDisplayAttribute(): string
    {
        $one = $this->apgar_1min ?? '—';
        $five = $this->apgar_5min ?? '—';
        return "{$one} / {$five}";
    }
}