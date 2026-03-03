<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_no', 'family_name', 'first_name', 'middle_name',
        'birthday', 'age', 'sex', 'address', 'contact_number',
        'occupation', 'civil_status', 'spouse_name', 'father_name',
        'mother_name', 'nationality', 'registration_type',
        'brought_by', 'condition_on_arrival', 'is_pedia',
        'has_incomplete_info', 'is_unknown',
    ];

    protected $casts = [
        'birthday'            => 'date',
        'is_pedia'            => 'boolean',
        'has_incomplete_info' => 'boolean',
        'is_unknown'          => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($patient) {

            // ── Auto-generate case_no (with lock to prevent duplicates) ──────
            //
            // We wrap the sequence read+assign in a DB transaction with a
            // table-level lock so that if two patients are created at the exact
            // same millisecond, they cannot both read the same "last" seq number.
            //
            $patient->case_no = DB::transaction(function () {
                $year = now()->year;

                // IMPORTANT: use withTrashed() so soft-deleted patients are
                // included. The unique constraint on case_no applies to ALL rows
                // including soft-deleted ones, so we must never reuse their numbers.
                $last = static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->lockForUpdate()
                    ->orderByDesc('id')
                    ->first();

                $seq = 1;
                if ($last && $last->case_no) {
                    $parts = explode('-', $last->case_no);
                    $seq   = ((int) end($parts)) + 1;
                }

                return 'LUMC-' . $year . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
            });

            // ── Auto-calculate age from birthday ─────────────────────────────
            if ($patient->birthday) {
                $birthday = Carbon::parse($patient->birthday);
                if ($birthday->isFuture()) {
                    $patient->age      = 0;
                    $patient->is_pedia = true;
                } else {
                    $age               = (int) $birthday->diffInYears(now());
                    $patient->age      = $age;
                    $patient->is_pedia = $age < 12;
                }
            }
            // If only age given (no birthday), keep age as-is
        });

        static::updating(function ($patient) {
            if ($patient->isDirty('birthday') && $patient->birthday) {
                $birthday = Carbon::parse($patient->birthday);
                if (!$birthday->isFuture()) {
                    $age               = (int) $birthday->diffInYears(now());
                    $patient->age      = $age;
                    $patient->is_pedia = $age < 12;
                }
            }
        });
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name
            ? ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';
        return strtoupper($this->family_name) . ', ' . $this->first_name . $middle;
    }

    /**
     * Always compute age dynamically from birthday if available,
     * so it updates automatically over the years.
     */
    public function getCurrentAgeAttribute(): ?int
    {
        if ($this->birthday) {
            return (int) \Carbon\Carbon::parse($this->birthday)->diffInYears(now());
        }
        return $this->age;
    }

    public function getAgeDisplayAttribute(): string
    {
        $age = $this->current_age;

        if ($age !== null && $age >= 0) {
            return $age . ' y/o';
        }

        return 'Unknown';
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function latestVisit()
    {
        return $this->hasOne(Visit::class)->latestOfMany('registered_at');
    }

    public function userAccount()
    {
        return $this->hasOne(User::class, 'patient_id');
    }
}