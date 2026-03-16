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
        // Admission-specific demographics (filled by clerk on Complete Admission)
        'birthplace',
        'religion',
        'employer_name', 'employer_address', 'employer_phone',
        'father_full_name', 'father_address', 'father_phone',
        'mother_maiden_name', 'mother_address', 'mother_phone',
        'philhealth_id', 'philhealth_type',
        'social_service_class',
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
            $patient->case_no = DB::transaction(function () use ($patient) {
                $year = now()->year;
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

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Full name for general UI display:  "DELA CRUZ, Juan M."
     * (family name ALL-CAPS, given name title-case — standard PH hospital format)
     */
    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name
            ? ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';
        return strtoupper($this->family_name) . ', ' . $this->first_name . $middle;
    }

    /**
     * Full name for the Consent to Care form:
     *   "JUAN M. DELA CRUZ"  (First MI. FAMILY — all uppercase)
     *
     * This is the standard order used on Philippine hospital consent forms:
     * given name first, then family name, entirely in capitals.
     */
    public function getConsentNameAttribute(): string
    {
        $first  = strtoupper($this->first_name  ?? '');
        $middle = $this->middle_name
            ? strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';
        $family = strtoupper($this->family_name ?? '');

        return trim(implode(' ', array_filter([$first, $middle, $family])));
    }

    public function getCurrentAgeAttribute(): ?int
    {
        if ($this->birthday) {
            return (int) Carbon::parse($this->birthday)->diffInYears(now());
        }
        return $this->age;
    }

    public function getAgeDisplayAttribute(): string
    {
        $age = $this->current_age;
        return ($age !== null && $age >= 0) ? $age . ' y/o' : 'Unknown';
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visits()     { return $this->hasMany(Visit::class); }
    public function vitals()     { return $this->hasMany(Vital::class); }
    public function latestVisit(){ return $this->hasOne(Visit::class)->latestOfMany('registered_at'); }
    public function userAccount(){ return $this->hasOne(User::class, 'patient_id'); }
}