<?php

namespace App\Http\Middleware\Filament;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

/**
 * Replaces Filament's default Authenticate middleware for the PATIENT panel.
 * When unauthenticated, redirects to the public landing page (/)
 * where the patient login modal lives.
 */
class PatientAuthenticate extends FilamentAuthenticate
{
    protected function redirectTo($request): string
    {
        return '/';
    }
}