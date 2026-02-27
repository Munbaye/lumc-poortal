<?php
namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            // NO ->homeUrl() — letting Filament decide where to land prevents redirect loops
            ->colors(['primary' => Color::Blue])
            ->brandName('LUMC Admin Panel')
            ->favicon(asset('images/favicon.ico'))
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\Filament\Admin\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\Filament\Admin\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\Filament\Admin\Widgets'
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
            ->authMiddleware([Authenticate::class]);
    }
}