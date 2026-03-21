<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LabRequest extends Model
{
    protected $fillable = [
        'request_no', 'status',
        'visit_id', 'patient_id', 'doctor_id', 'submitted_by',
        'ward', 'request_type', 'stat_justification',
        'clinical_diagnosis', 'requesting_physician',
        'tests',
        'specimen', 'antibiotics_taken', 'antibiotics_duration', 'other_tests',
        'date_requested', 'request_received_at',
        'specimen_collected',          // ← was specimen_collected_by (removed "by")
        'test_started_at', 'test_done_at',
        'notes',
    ];

    protected $casts = [
        'tests'               => 'array',
        'date_requested'      => 'date',
        'request_received_at' => 'datetime',
        'test_started_at'     => 'datetime',
        'test_done_at'        => 'datetime',
    ];

    // ── Status constants ──────────────────────────────────────────────────────
    const STATUS_PENDING     = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED   = 'completed';

    public function isPending(): bool     { return $this->status === self::STATUS_PENDING; }
    public function isInProgress(): bool  { return $this->status === self::STATUS_IN_PROGRESS; }
    public function isCompleted(): bool   { return $this->status === self::STATUS_COMPLETED; }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED   => 'Completed',
            default                  => 'Pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_COMPLETED   => 'success',
            default                  => 'gray',
        };
    }

    // ── Boot: auto-generate request_no ───────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (LabRequest $model) {
            if (empty($model->request_no)) {
                $model->request_no = static::generateRequestNo();
            }
        });
    }

    public static function generateRequestNo(): string
    {
        return DB::transaction(function () {
            $year = now()->year;
            $seq  = DB::table('lab_request_sequences')
                ->where('year', $year)->lockForUpdate()->first();

            if ($seq) {
                $next = $seq->last_sequence + 1;
                DB::table('lab_request_sequences')
                    ->where('year', $year)
                    ->update(['last_sequence' => $next, 'updated_at' => now()]);
            } else {
                $next = 1;
                DB::table('lab_request_sequences')->insert([
                    'year' => $year, 'last_sequence' => $next,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            return 'LAB-' . $year . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
        });
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit()       { return $this->belongsTo(Visit::class); }
    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(User::class, 'doctor_id'); }
    public function submittedBy() { return $this->belongsTo(User::class, 'submitted_by'); }

    /** All uploaded result files for this request. */
    public function results()
    {
        return $this->hasMany(ResultUpload::class, 'request_id')
            ->where('request_type', 'lab');
    }

    /** First/only result (backwards-compat with existing views). */
    public function result()
    {
        return $this->hasOne(ResultUpload::class, 'request_id')
            ->where('request_type', 'lab');
    }
}