<?php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class TechPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tech')
            ->path('tech')
            ->login()
            // ✅ NO ->homeUrl() — prevents redirect loops
            ->colors(['primary' => Color::Orange])
            ->brandName('LUMC — Tech Portal')
            ->favicon(asset('images/favicon.ico'))
            ->discoverPages(
                in: app_path('Filament/Tech/Pages'),
                for: 'App\Filament\Tech\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Tech/Resources'),
                for: 'App\Filament\Tech\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Tech/Widgets'),
                for: 'App\Filament\Tech\Widgets'
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
            ->authMiddleware([\Filament\Http\Middleware\Authenticate::class]);
    }
}