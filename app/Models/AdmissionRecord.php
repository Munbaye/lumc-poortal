<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AdmissionRecord — stores the data the clerk fills in on the ADM-001 form.
 * One per visit (unique on visit_id).
 */
class AdmissionRecord extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'filled_by',
        'patient_family_name', 'patient_first_name', 'patient_middle_name',
        'permanent_address', 'telephone_no', 'sex', 'civil_status',
        'birthdate', 'age', 'birthplace', 'nationality', 'religion', 'occupation',
        'employer_name', 'employer_address', 'employer_phone',
        'father_name', 'father_address', 'father_phone',
        'mother_maiden_name', 'mother_address', 'mother_phone',
        'admission_date', 'admission_time', 'discharge_date', 'discharge_time',
        'total_days', 'ward_service',
        'type_of_admission', 'social_service_class', 'payment_class',
        'alert', 'allergic_to', 'health_insurance_name',
        'philhealth_id', 'philhealth_type',
        'data_furnished_by', 'data_furnished_address', 'data_furnished_relation',
        'admission_diagnosis', 'final_diagnosis', 'other_diagnosis', 'principal_operation',
        'disposition', 'results',
    ];

    protected $casts = [
        'birthdate'      => 'date',
        'admission_date' => 'date',
        'discharge_date' => 'date',
    ];

    public function visit()    { return $this->belongsTo(Visit::class); }
    public function patient()  { return $this->belongsTo(Patient::class); }
    public function filledBy() { return $this->belongsTo(User::class, 'filled_by'); }
}