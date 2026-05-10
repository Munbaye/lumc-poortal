<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (config('app.env') === 'local') {
            URL::forceRootUrl(config('app.url'));
        }

        // Inject the My Account modal into ALL panels (staff + patient)
        Filament::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => auth()->check()
                ? Blade::render('@include("filament.components.account-modal")')
                : '',
        );
    }
}