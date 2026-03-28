<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ErRecord — stores the data the clerk fills in on the ER-001 form.
 * One per visit (unique on visit_id).
 */
class ErRecord extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'filled_by',
        'health_record_no', 'type_of_service', 'medico_legal',
        'case_type', 'notified_proper_authority',
        'patient_family_name', 'patient_first_name', 'patient_middle_name',
        'permanent_address', 'telephone_no', 'nationality', 'age', 'birthdate',
        'sex', 'civil_status', 'employer_name', 'employer_phone',
        'registration_date', 'registration_time',
        'brought_by', 'condition_on_arrival',
        'temperature', 'temperature_site', 'pulse_rate', 'blood_pressure',
        'cardiac_rate', 'respiratory_rate', 'height_cm', 'weight_kg',
        'chief_complaint', 'allergies', 'current_medication',
        'physical_findings_and_diagnosis', 'treatment',
        'disposition_date', 'disposition_time', 'disposition', 'condition_on_discharge',
    ];

    protected $casts = [
        'medico_legal'      => 'boolean',
        'birthdate'         => 'date',
        'registration_date' => 'date',
        'disposition_date'  => 'date',
        'temperature'       => 'float',
        'height_cm'         => 'float',
        'weight_kg'         => 'float',
    ];

    public function visit()    { return $this->belongsTo(Visit::class); }
    public function patient()  { return $this->belongsTo(Patient::class); }
    public function filledBy() { return $this->belongsTo(User::class, 'filled_by'); }
}