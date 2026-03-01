<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;

class ClerkPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('clerk')
            ->path('clerk')
            ->colors(['primary' => Color::Amber])
            ->brandName('LUMC — Clerk Portal')
            ->favicon(asset('images/lumc-logo.png'))
            ->discoverPages(
                in: app_path('Filament/Clerk/Pages'),
                for: 'App\Filament\Clerk\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Clerk/Resources'),
                for: 'App\Filament\Clerk\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Clerk/Widgets'),
                for: 'App\Filament\Clerk\Widgets'
            )
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