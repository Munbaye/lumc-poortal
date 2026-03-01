<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force the app to use the correct URL so CSRF cookies
        // are always scoped to 127.0.0.1:8000 during local dev.
        if (config('app.env') === 'local') {
            URL::forceRootUrl(config('app.url'));
        }
    }
}