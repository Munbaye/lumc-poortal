<?php
// ─────────────────────────────────────────────────────────────────────────────
// This file registers auth event listeners for activity logging.
// ─────────────────────────────────────────────────────────────────────────────
namespace App\Listeners;

use App\Listeners\LogAuthEvent;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class  => [LogAuthEvent::class . '@handleLogin'],
        Logout::class => [LogAuthEvent::class . '@handleLogout'],
        Failed::class => [LogAuthEvent::class . '@handleFailed'],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}