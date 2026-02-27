<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'doctor_id',
        // NUR-006 History
        'chief_complaint',
        'history_of_present_illness',
        'past_medical_history',
        'family_history',
        'occupation_environment',
        'drug_allergies',
        'drug_therapy',
        'other_allergies',
        // NUR-005 Physical Exam
        'pe_skin',
        'pe_head_eent',
        'pe_lymph_nodes',
        'pe_chest',
        'pe_lungs',
        'pe_cardiovascular',
        'pe_breast',
        'pe_abdomen',
        'pe_rectum',
        'pe_genitalia',
        'pe_musculoskeletal',
        'pe_extremities',
        'pe_neurology',
        // Assessment
        'admitting_impression',
        'diagnosis',
        'differential_diagnosis',
        'plan',
        'disposition',
        'admitted_ward',
        'service',
        'payment_type',
    ];

    public function visit()   { return $this->belongsTo(Visit::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(User::class, 'doctor_id'); }
}