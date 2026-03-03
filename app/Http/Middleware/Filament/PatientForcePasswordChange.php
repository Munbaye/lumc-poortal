<?php

namespace App\Http\Middleware\Filament;

use Closure;
use Illuminate\Http\Request;

class PatientForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (
            $user &&
            $user->panel === 'patient' &&
            $user->force_password_change
        ) {
            $path = $request->path();

            $allowed = [
                'patient-change-password',
                'patient/logout',
                'logout',
                'livewire',
            ];

            foreach ($allowed as $allow) {
                if (str_contains($path, $allow)) {
                    return $next($request);
                }
            }

            return redirect('/?change_password=1');
        }

        return $next($request);
    }
}