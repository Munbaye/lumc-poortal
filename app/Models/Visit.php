<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'clerk_id', 'assigned_doctor_id',
        'visit_type',
        'type_of_service',
        'medico_legal',
        'notified_proper_authority',
        'chief_complaint',
        'admitting_diagnosis',
        'payment_class',
        'status', 'disposition',
        'admitted_ward', 'admitted_service',
        'referral_notes',
        'brought_by', 'condition_on_arrival',
        'registered_at',
        'discharged_at',
        'doctor_admitted_at',
        'clerk_admitted_at',
        
        // ── NEW NICU FIELDS ───────────────────────────────────────────────────
        'is_provisionally_registered',
        'referring_facility',
        'admission_type',
    ];

    protected $casts = [
        'registered_at'      => 'datetime',
        'discharged_at'      => 'datetime',
        'doctor_admitted_at' => 'datetime',
        'clerk_admitted_at'  => 'datetime',
        'medico_legal'       => 'boolean',
        'is_provisionally_registered' => 'boolean',
    ];

    public function patient()          { return $this->belongsTo(Patient::class); }
    public function clerk()            { return $this->belongsTo(User::class, 'clerk_id'); }
    public function assignedDoctor()   { return $this->belongsTo(User::class, 'assigned_doctor_id'); }
    public function vitals()           { return $this->hasMany(Vital::class); }
    public function latestVitals()     { return $this->hasOne(Vital::class)->latestOfMany('taken_at'); }
    public function medicalHistory()   { return $this->hasOne(MedicalHistory::class); }
    public function doctorsOrders()    { return $this->hasMany(DoctorsOrder::class); }
    public function nursesNotes()      { return $this->hasMany(NursesNote::class); }
    public function uploads()          { return $this->hasMany(ResultUpload::class); }
    public function erRecord()         { return $this->hasOne(ErRecord::class); }
    public function admissionRecord()  { return $this->hasOne(AdmissionRecord::class); }
    public function consentRecord()    { return $this->hasOne(ConsentRecord::class); }
    public function labRequests()      { return $this->hasMany(LabRequest::class); }
    public function radiologyRequests(){ return $this->hasMany(RadiologyRequest::class); }
    
    // ── NICU Relationships ────────────────────────────────────────────────────
    public function nicuAdmission()    { return $this->hasOne(NicuAdmission::class); }

    public function isPendingAdmission(): bool
    {
        return $this->doctor_admitted_at !== null && $this->clerk_admitted_at === null;
    }

    public function isVisibleToDoctor(int $doctorId): bool
    {
        if ($this->payment_class === 'Private') {
            return $this->assigned_doctor_id === $doctorId;
        }
        return true;
    }
    
    /**
     * Check if this is a provisional NICU visit
     */
    public function isProvisionalNicu(): bool
    {
        return $this->visit_type === 'NICU' && $this->is_provisionally_registered === true;
    }

    public function nicuPhysicalExam()
    {
        return $this->hasOne(NicuPhysicalExam::class);
    }

    public function ballardExams()
    {
        return $this->hasMany(NicuBallardExam::class)->orderBy('exam_number');
    }

    public function latestBallardExam()
    {
        return $this->hasOne(NicuBallardExam::class)->latestOfMany('exam_number');
    }

    public function breastfeedingObservations()
    {
        return $this->hasMany(NicuBreastfeedingObservation::class)->orderBy('observation_date', 'desc');
    }
}