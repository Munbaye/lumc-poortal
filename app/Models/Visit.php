<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'clerk_id',
        'assigned_doctor_id',   // set by doctor on admission (Private patients)
        'visit_type',           // OPD | ER
        'chief_complaint',
        'payment_class',        // Charity | Private | null — doctor sets on admit
        'status',               // registered → vitals_done → assessed → discharged/admitted/referred
        'disposition',          // Discharged | Admitted | Referred | HAMA | Expired
        'admitted_ward',
        'admitted_service',
        'referral_notes',
        'brought_by',
        'condition_on_arrival',
        'registered_at',
        'discharged_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function patient()        { return $this->belongsTo(Patient::class); }
    public function clerk()          { return $this->belongsTo(User::class, 'clerk_id'); }
    public function assignedDoctor() { return $this->belongsTo(User::class, 'assigned_doctor_id'); }
    public function vitals()         { return $this->hasMany(Vital::class); }
    public function latestVitals()   { return $this->hasOne(Vital::class)->latestOfMany('taken_at'); }
    public function medicalHistory() { return $this->hasOne(MedicalHistory::class); }
    public function doctorsOrders()  { return $this->hasMany(DoctorsOrder::class); }
    public function nursesNotes()    { return $this->hasMany(NursesNote::class); }
    public function uploads()        { return $this->hasMany(ResultUpload::class); }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * True if this visit's records are visible to the given doctor.
     * Charity → all doctors; Private → only assigned_doctor_id.
     */
    public function isVisibleToDoctor(int $doctorId): bool
    {
        if ($this->payment_class === 'Private') {
            return $this->assigned_doctor_id === $doctorId;
        }
        return true; // Charity or not yet set
    }
}