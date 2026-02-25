<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'doctor_id',
        'chief_complaint', 'history_of_present_illness', 'past_medical_history',
        'family_history', 'social_history', 'allergies', 'current_medications',
        'physical_exam', 'diagnosis', 'differential_diagnosis',
        'disposition', 'admitted_ward', 'service', 'payment_type', 'plan'
    ];

    public function visit()   { return $this->belongsTo(Visit::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(User::class, 'doctor_id'); }
}