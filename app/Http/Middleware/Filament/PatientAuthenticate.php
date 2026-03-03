<?php

namespace App\Http\Middleware\Filament;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if ($user->panel !== 'patient' || !$user->is_active) {
            Auth::logout();
            return redirect('/');
        }

        return $next($request);
    }
}