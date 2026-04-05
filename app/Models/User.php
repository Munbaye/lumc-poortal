<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Carbon\Carbon;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birthdate',
        'username',
        'email',
        'password',
        'employee_id',
        'panel',
        'specialty',
        'departments',
        'is_active',
        'patient_id',
        'force_password_change',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at'     => 'datetime',
        'is_active'             => 'boolean',
        'force_password_change' => 'boolean',
        'birthdate'             => 'date',
        'departments'           => 'array',
    ];

    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            $parts = array_filter([
                $this->first_name,
                $this->middle_name ? $this->middle_name[0] . '.' : null,
                $this->last_name,
            ]);
            return implode(' ', $parts);
        }
        return $this->name ?? '';
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate ? Carbon::parse($this->birthdate)->age : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->is_active) return false;

        return match ($panel->getId()) {
            'admin'   => $this->hasRole('admin'),
            'doctor'  => $this->hasRole('doctor'),
            'nurse'   => $this->hasRole('nurse'),
            'clerk'   => $this->hasRole(['clerk', 'clerk-opd', 'clerk-er']),
            'tech'    => $this->hasRole(['tech', 'tech-rad', 'tech-med', 'tech-tech']),
            'patient' => $this->hasRole('patient'),
            default   => false,
        };
    }

    public function patientRecord()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function getDoctorLabelAttribute(): string
    {
        $name = $this->full_name ?: $this->name;
        return $this->specialty
            ? "Dr. {$name} ({$this->specialty})"
            : "Dr. {$name}";
    }

    public function visits()    { return $this->hasMany(Visit::class, 'clerk_id'); }
    public function schedules() { return $this->hasMany(Schedule::class); }
}