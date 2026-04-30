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
        
        // ── NICU PROVISIONAL FIELDS ───────────────────────────────────────
        'is_provisional',
        'temporary_case_no',
        'temporary_identifier',
        'mother_last_name_at_birth',
        'birth_datetime',
        'baby_family_name',
        'baby_first_name',
        'baby_middle_name',
        'mother_family_name',
        'mother_first_name',
        'mother_middle_name',
        'mother_age',
        'mother_address_full',
        'mother_contact',
        'clerk_registered_at',
        'clerk_registered_by',
    ];

    protected $casts = [
        'birthday'            => 'date',
        'birth_datetime'      => 'datetime',
        'is_pedia'            => 'boolean',
        'has_incomplete_info' => 'boolean',
        'is_unknown'          => 'boolean',
        'is_provisional'      => 'boolean',
        'clerk_registered_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($patient) {
            // For provisional records: set placeholders for required fields
            if ($patient->is_provisional) {
                $patient->case_no = null;
                $patient->family_name = $patient->family_name ?? 'PROVISIONAL';
                $patient->first_name = $patient->first_name ?? 'RECORD';
                return;
            }
            
            // Only generate permanent case_no for NON-provisional records
            if (!$patient->case_no) {
                $patient->case_no = DB::transaction(function () use ($patient) {
                    $year = now()->year;
                    $last = static::withTrashed()
                        ->whereYear('created_at', $year)
                        ->where('is_provisional', false)
                        ->lockForUpdate()
                        ->orderByDesc('id')
                        ->first();
                    $seq = 1;
                    if ($last && $last->case_no && !str_starts_with($last->case_no, 'TEMP')) {
                        $parts = explode('-', $last->case_no);
                        $seq   = ((int) end($parts)) + 1;
                    }
                    return 'LUMC-' . $year . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
                });
            }

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
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        // For provisional records, show temporary identifier
        if ($this->is_provisional && $this->temporary_identifier) {
            return $this->temporary_identifier . ' (Temporary)';
        }
        
        // For permanent records with baby name
        if ($this->baby_family_name && $this->baby_first_name) {
            $middle = $this->baby_middle_name
                ? ' ' . strtoupper(substr($this->baby_middle_name, 0, 1)) . '.'
                : '';
            return strtoupper($this->baby_family_name) . ', ' . $this->baby_first_name . $middle;
        }
        
        // Fallback to regular name fields
        $middle = $this->middle_name
            ? ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';
        return strtoupper($this->family_name) . ', ' . $this->first_name . $middle;
    }

    /**
     * Display name for searches and lists (shows mother's name for provisional)
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->is_provisional) {
            return $this->temporary_identifier ?? 'Unidentified Baby';
        }
        return $this->full_name;
    }

    /**
     * Get the baby's official display name (after clerk registration)
     */
    public function getBabyOfficialNameAttribute(): string
    {
        if ($this->baby_family_name && $this->baby_first_name) {
            $middle = $this->baby_middle_name
                ? ' ' . strtoupper(substr($this->baby_middle_name, 0, 1)) . '.'
                : '';
            return strtoupper($this->baby_family_name) . ', ' . $this->baby_first_name . $middle;
        }
        return $this->full_name;
    }

    /**
     * Get mother's full name for display
     */
    public function getMotherFullNameAttribute(): string
    {
        if ($this->mother_family_name && $this->mother_first_name) {
            $middle = $this->mother_middle_name
                ? ' ' . $this->mother_middle_name
                : '';
            return $this->mother_first_name . $middle . ' ' . $this->mother_family_name;
        }
        return $this->mother_name ?? 'Unknown';
    }

    public function getConsentNameAttribute(): string
    {
        // For provisional babies, use mother's name for consent
        if ($this->is_provisional) {
            return strtoupper($this->mother_full_name);
        }
        
        $first  = strtoupper($this->baby_first_name ?? $this->first_name ?? '');
        $middle = $this->baby_middle_name ?? $this->middle_name
            ? strtoupper(substr($this->baby_middle_name ?? $this->middle_name, 0, 1)) . '.'
            : '';
        $family = strtoupper($this->baby_family_name ?? $this->family_name ?? '');

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
    
    /**
     * Get age in days/hours for newborns (from birth_datetime)
     */
    public function getNewbornAgeDisplayAttribute(): string
    {
        if (!$this->birth_datetime) {
            return 'Unknown';
        }
        
        $hours = (int) Carbon::parse($this->birth_datetime)->diffInHours(now());
        
        if ($hours < 24) {
            return $hours . ' hour(s) old';
        }
        
        $days = floor($hours / 24);
        return $days . ' day(s) old';
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visits()     { return $this->hasMany(Visit::class); }
    public function vitals()     { return $this->hasMany(Vital::class); }
    public function latestVisit(){ return $this->hasOne(Visit::class)->latestOfMany('registered_at'); }
    public function userAccount(){ return $this->hasOne(User::class, 'patient_id'); }
    
    // ── NICU Relationships ────────────────────────────────────────────────────
    public function nicuAdmissions(){ return $this->hasMany(NicuAdmission::class, 'patient_id'); }
    public function latestNicuAdmission(){ return $this->hasOne(NicuAdmission::class)->latestOfMany('id'); }
    
    public function clerkRegisteredBy(){ return $this->belongsTo(User::class, 'clerk_registered_by'); }

    // ── Helpers ───────────────────────────────────────────────────────────────
    
    /**
     * Generate a temporary case number for provisional records
     */
    public static function generateTemporaryCaseNo(): string
    {
        $today = now()->format('Ymd');
        
        // Get the last sequence number for today
        $last = static::where('temporary_case_no', 'like', "TEMP-{$today}-%")
            ->orderBy('temporary_case_no', 'desc')
            ->first();
            
        if ($last && $last->temporary_case_no) {
            $parts = explode('-', $last->temporary_case_no);
            $seq = (int) end($parts) + 1;
        } else {
            $seq = 1;
        }
        
        return 'TEMP-' . $today . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Convert provisional record to permanent
     */
    public function convertToPermanent(int $clerkId): void
    {
        DB::beginTransaction();
        
        try {
            // Move baby name from provisional fields to permanent fields
            if ($this->baby_family_name) {
                $this->family_name = $this->baby_family_name;
                $this->first_name = $this->baby_first_name ?? '';
                $this->middle_name = $this->baby_middle_name;
            }
            
            // Move mother's information if needed
            if ($this->mother_family_name) {
                $this->mother_name = $this->mother_full_name;
                $this->mother_address = $this->mother_address_full;
                $this->mother_phone = $this->mother_contact;
            }
            
            // Generate permanent case number manually (since boot() won't run on update)
            $year = now()->year;
            $last = static::withTrashed()
                ->whereYear('created_at', $year)
                ->where('is_provisional', false)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();
            $seq = 1;
            if ($last && $last->case_no && !str_starts_with($last->case_no, 'TEMP')) {
                $parts = explode('-', $last->case_no);
                $seq = ((int) end($parts)) + 1;
            }
            $permanentCaseNo = 'LUMC-' . $year . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
            
            // Update the patient
            $this->is_provisional = false;
            $this->case_no = $permanentCaseNo;
            $this->clerk_registered_at = now();
            $this->clerk_registered_by = $clerkId;
            
            $this->save();
            
            // Update the associated visit - PRESERVE admission status
            if ($visit = $this->latestVisit) {
                $visit->is_provisionally_registered = false;
                
                // Only change status to 'registered' if it's NOT already 'admitted'
                // If doctor already admitted, keep it as 'admitted'
                if ($visit->status !== 'admitted') {
                    $visit->status = 'registered';
                }
                // If status is 'admitted', leave it as 'admitted' (doctor already admitted)
                
                $visit->save();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}