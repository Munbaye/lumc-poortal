<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * DischargeSummary — stores the doctor's discharge summary for a visit.
 *
 * One record per visit (enforced by unique index on visit_id).
 * Auto-filled fields are copied from Visit / Patient at save time so the
 * summary remains accurate even if the patient record is later edited.
 */
class DischargeSummary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'written_by',

        // Demographics
        'patient_family_name',
        'patient_first_name',
        'patient_middle_name',
        'permanent_address',
        'telephone_no',
        'sex',
        'civil_status',
        'hospital_case_no',
        'ward_service',

        // Dates
        'date_admitted',
        'date_discharged',

        // Clinical
        'attending_physician',
        'admitting_diagnosis',
        'final_diagnosis',
        'chief_complaints',
        'brief_clinical_history',
        'laboratory_findings',
        'course_in_ward',
        'disposition',

        // Status
        'is_finalized',
        'finalized_at',
    ];

    protected $casts = [
        'date_admitted'   => 'datetime',
        'date_discharged' => 'datetime',
        'finalized_at'    => 'datetime',
        'is_finalized'    => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit()     { return $this->belongsTo(Visit::class); }
    public function patient()   { return $this->belongsTo(Patient::class); }
    public function writtenBy() { return $this->belongsTo(User::class, 'written_by'); }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Assembled full patient name in "FAMILY, Given M." format.
     */
    public function getPatientFullNameAttribute(): string
    {
        $middle = $this->patient_middle_name
            ? ' ' . strtoupper(substr($this->patient_middle_name, 0, 1)) . '.'
            : '';

        return strtoupper($this->patient_family_name ?? '')
             . ', '
             . ($this->patient_first_name ?? '')
             . $middle;
    }

    // ── Factory helper ────────────────────────────────────────────────────────

    /**
     * Build a DischargeSummary (not yet saved) pre-filled from a Visit.
     * The caller should call ->save() or ->fill([...])->save().
     */
    public static function fromVisit(Visit $visit): static
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $patient = $visit->patient;
        $history = $visit->medicalHistory;
        $doctor  = $history?->doctor;

        return new static([
            'visit_id'            => $visit->id,
            'patient_id'          => $patient->id,
            'written_by'          => auth()->id(),

            // Demographics
            'patient_family_name' => $patient->family_name,
            'patient_first_name'  => $patient->first_name,
            'patient_middle_name' => $patient->middle_name,
            'permanent_address'   => $patient->address,
            'telephone_no'        => $patient->contact_number,
            'sex'                 => $patient->sex,
            'civil_status'        => $patient->civil_status,
            'hospital_case_no'    => $patient->case_no,
            'ward_service'        => $visit->admitted_service ?? $history?->service,

            // Dates
            'date_admitted'       => $visit->clerk_admitted_at ?? $visit->doctor_admitted_at,
            'date_discharged'     => now(),

            // Clinical (editable by doctor)
            'attending_physician' => $doctor ? 'Dr. ' . $doctor->name : null,
            'admitting_diagnosis' => $visit->admitting_diagnosis ?? $history?->diagnosis,
            'final_diagnosis'     => $history?->diagnosis,
            'chief_complaints'    => $visit->chief_complaint,
        ]);
    }
}