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
        'name', 'email', 'password', 'employee_id', 'panel', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    /**
     * Controls which Filament panel a user can access.
     * If they try to log in to the wrong panel, Filament shows "unauthorized".
     */
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

    public function visits()    { return $this->hasMany(\App\Models\Visit::class, 'clerk_id'); }
    public function schedules() { return $this->hasMany(\App\Models\Schedule::class); }
}