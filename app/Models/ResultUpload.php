<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultUpload extends Model
{
    protected $fillable = [
        // Original fields
        'visit_id',
        'patient_id',
        'uploaded_by',
        'result_type',
        'test_name',
        'file_path',
        'file_name',
        'mime_type',
        'notes',

        // Added by migration: add_initial_reading_to_result_uploads_table
        'requested_by',
        'performed_by',
        'readability_checks',
        'initial_impression',
        'is_critical',
        'critical_reason',
        'critical_notified_at',
        'status',
    ];

    protected $casts = [
        'readability_checks'   => 'array',   // JSON → PHP array automatically
        'is_critical'          => 'boolean',
        'critical_notified_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit()       { return $this->belongsTo(Visit::class); }
    public function patient()     { return $this->belongsTo(Patient::class); }
    public function uploader()    { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function requestedBy() { return $this->belongsTo(User::class, 'requested_by'); }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isPendingValidation(): bool
    {
        return $this->status === 'pending_validation';
    }

    public function isReleased(): bool
    {
        return $this->status === 'released';
    }
}