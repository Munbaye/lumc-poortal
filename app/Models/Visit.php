<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'clerk_id', 'visit_type', 'chief_complaint',
        'status', 'disposition', 'admitted_ward', 'referral_notes',
        'brought_by', 'condition_on_arrival',
        'registered_at', 'discharged_at'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function patient()       { return $this->belongsTo(Patient::class); }
    public function clerk()         { return $this->belongsTo(User::class, 'clerk_id'); }
    public function vitals()        { return $this->hasMany(Vital::class); }
    public function latestVitals()  { return $this->hasOne(Vital::class)->latestOfMany(); }
    public function medicalHistory(){ return $this->hasOne(MedicalHistory::class); }
    public function doctorsOrders() { return $this->hasMany(DoctorsOrder::class); }
    public function nursesNotes()   { return $this->hasMany(NursesNote::class); }
    public function uploads()       { return $this->hasMany(ResultUpload::class); }
}