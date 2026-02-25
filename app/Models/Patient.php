<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_no', 'family_name', 'first_name', 'middle_name',
        'birthday', 'age', 'sex', 'address', 'contact_number',
        'occupation', 'civil_status', 'spouse_name', 'father_name',
        'mother_name', 'nationality', 'registration_type',
        'brought_by', 'condition_on_arrival', 'is_pedia'
    ];

    protected $casts = [
        'birthday' => 'date',
        'is_pedia'  => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($patient) {

            // ── Auto-generate case_no ──────────────────────────────
            $year = now()->year;
            $last = static::whereYear('created_at', $year)
                ->orderByDesc('id')
                ->first();

            $seq = 1;
            if ($last && $last->case_no) {
                $parts = explode('-', $last->case_no);
                $seq   = ((int) end($parts)) + 1;
            }

            $patient->case_no = 'LUMC-' . $year . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);

            // ── Auto-calculate age from birthday ───────────────────
            // Uses Carbon so it is always a whole positive integer
            if ($patient->birthday) {
                $birthday = Carbon::parse($patient->birthday);

                // Birthday must be in the past; guard against future dates
                if ($birthday->isFuture()) {
                    $patient->age      = 0;
                    $patient->is_pedia = true;
                } else {
                    $age               = (int) $birthday->diffInYears(now()); // always ≥ 0
                    $patient->age      = $age;
                    $patient->is_pedia = $age < 12;
                }
            }
            // Note: pedia from weight (<10 kg) is evaluated in RecordVitals::save()
        });

        // Re-calculate age on update if birthday changes
        static::updating(function ($patient) {
            if ($patient->isDirty('birthday') && $patient->birthday) {
                $birthday = Carbon::parse($patient->birthday);
                if (!$birthday->isFuture()) {
                    $age           = (int) $birthday->diffInYears(now());
                    $patient->age  = $age;
                    $patient->is_pedia = $age < 12;
                }
            }
        });
    }

    // ── Accessors ─────────────────────────────────────────────────

    /**
     * "DELA CRUZ, Juan M." format
     */
    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name
            ? ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';
        return strtoupper($this->family_name) . ', ' . $this->first_name . $middle;
    }

    /**
     * "25 y/o" — always a clean whole number, never negative
     */
    public function getAgeDisplayAttribute(): string
    {
        if ($this->birthday) {
            $birthday = Carbon::parse($this->birthday);
            if ($birthday->isFuture()) {
                return '0 y/o';
            }
            $age = (int) $birthday->diffInYears(now()); // whole integer, never negative
            return $age . ' y/o';
        }

        if ($this->age !== null && $this->age >= 0) {
            return (int) $this->age . ' y/o';
        }

        return 'Unknown';
    }

    // ── Relationships ──────────────────────────────────────────────

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
}