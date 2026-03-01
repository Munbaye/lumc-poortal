<?php

namespace App\Http\Middleware\Filament;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

/**
 * Replaces Filament's default Authenticate middleware for all STAFF panels.
 * When unauthenticated, redirects to our custom /staff login page
 * instead of the panel's own /admin/login, /clerk/login, etc.
 */
class StaffAuthenticate extends FilamentAuthenticate
{
    protected function redirectTo($request): string
    {
        return '/staff';
    }
}