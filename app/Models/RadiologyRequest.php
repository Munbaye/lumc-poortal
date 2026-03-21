<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RadiologyRequest extends Model
{
    protected $fillable = [
        'request_no', 'status',
        'visit_id', 'patient_id', 'doctor_id', 'submitted_by',
        'modality', 'source', 'ward',
        'examination_desired', 'clinical_diagnosis',
        'clinical_findings', 'radiologist_interpretation',
        'requesting_physician',
        'date_requested',
        'request_received_at',
        'exam_started_at',
        'exam_done_at',
        'notes',
    ];

    protected $casts = [
        'date_requested'      => 'date',
        'request_received_at' => 'datetime',
        'exam_started_at'     => 'datetime',
        'exam_done_at'        => 'datetime',
    ];

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

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (RadiologyRequest $model) {
            if (empty($model->request_no)) {
                $model->request_no = static::generateRequestNo();
            }
        });
    }

    public static function generateRequestNo(): string
    {
        return DB::transaction(function () {
            $year = now()->year;
            $seq  = DB::table('radiology_request_sequences')
                ->where('year', $year)->lockForUpdate()->first();

            if ($seq) {
                $next = $seq->last_sequence + 1;
                DB::table('radiology_request_sequences')
                    ->where('year', $year)
                    ->update(['last_sequence' => $next, 'updated_at' => now()]);
            } else {
                $next = 1;
                DB::table('radiology_request_sequences')->insert([
                    'year' => $year, 'last_sequence' => $next,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            return 'RAD-' . $year . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
        });
    }

    public function visit()       { return $this->belongsTo(Visit::class); }
    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(User::class, 'doctor_id'); }
    public function submittedBy() { return $this->belongsTo(User::class, 'submitted_by'); }

    public function results()
    {
        return $this->hasMany(ResultUpload::class, 'request_id')
            ->where('request_type', 'radiology');
    }

    public function result()
    {
        return $this->hasOne(ResultUpload::class, 'request_id')
            ->where('request_type', 'radiology');
    }
}