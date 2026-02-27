<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'employee_id', 'panel', 'specialty', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->is_active) return false;

        return match ($panel->getId()) {
            'admin'   => $this->hasRole('admin'),
            'doctor'  => $this->hasRole('doctor'),
            'nurse'   => $this->hasRole('nurse'),
            'clerk'   => $this->hasRole(['clerk', 'clerk-opd', 'clerk-er']),
            'tech'    => $this->hasRole('tech'),
            'patient' => $this->hasRole('patient'),
            default   => false,
        };
    }

    // Full display name for dropdowns — includes specialty if available
    public function getDoctorLabelAttribute(): string
    {
        return $this->specialty
            ? "Dr. {$this->name} ({$this->specialty})"
            : "Dr. {$this->name}";
    }

    public function visits()    { return $this->hasMany(Visit::class, 'clerk_id'); }
    public function schedules() { return $this->hasMany(Schedule::class); }
}