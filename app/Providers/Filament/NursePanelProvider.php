<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;

class NursePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('nurse')
            ->path('nurse')
            ->colors(['primary' => Color::Rose])
            ->brandName('LUMC — Nurse Portal')
            ->favicon(asset('images/lumc-logo.png'))
            ->discoverPages(
                in: app_path('Filament/Nurse/Pages'),
                for: 'App\Filament\Nurse\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Nurse/Resources'),
                for: 'App\Filament\Nurse\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Nurse/Widgets'),
                for: 'App\Filament\Nurse\Widgets'
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