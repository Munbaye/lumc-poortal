<?php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class ClerkPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('clerk')
            ->path('clerk')
            ->login()
            ->homeUrl('/clerk')
            ->colors(['primary' => Color::Amber])
            ->brandName('LUMC — Clerk Portal')
            ->favicon(asset('images/favicon.ico'))

            // ✅ Use discoverPages ONLY — do NOT also call ->pages([...])
            ->discoverPages(
                in: app_path('Filament/Clerk/Pages'),
                for: 'App\Filament\Clerk\Pages'
            )
            // ✅ Use discoverResources ONLY — do NOT also call ->resources([...])
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
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}