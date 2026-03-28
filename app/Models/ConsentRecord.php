<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ConsentRecord — stores the clerk-filled Consent to Care data.
 * One per visit (unique on visit_id).
 */
class ConsentRecord extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'saved_by',
        'active_section',
        // Section 1
        'patient_name', 'doctor_name_sec1', 'witness_sec1', 'signed_date_sec1',
        // Section 2
        'guardian_name', 'nok_sig_name', 'being_the',
        'doctor_name_sec2', 'witness_sec2', 'signed_date_sec2', 'relation_to_patient',
    ];

    public function visit()   { return $this->belongsTo(Visit::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function savedBy() { return $this->belongsTo(User::class, 'saved_by'); }
}