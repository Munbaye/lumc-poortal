<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
use App\Filament\Tech\Resources\ResultUploadResource;
use App\Filament\Tech\Pages\TechDashboard;

class TechPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tech')
            ->path('tech')
            ->colors(['primary' => Color::Orange])
            ->brandName('LUMC — Tech Portal')
            ->favicon(asset('images/lumc-logo.png'))

            // Explicit registration — no filesystem scanning on every request
            ->resources([
                ResultUploadResource::class,
            ])
            ->pages([
                TechDashboard::class,
            ])
            ->homeUrl(fn () => TechDashboard::getUrl())

            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authMiddleware([StaffAuthenticate::class]);
    }
}