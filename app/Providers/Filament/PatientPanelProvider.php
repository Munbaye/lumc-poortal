<?php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class PatientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('patient')
            ->path('patient')
            ->login()
            // NO ->homeUrl() — prevents redirect loops
            ->colors(['primary' => Color::Green])
            ->brandName('LUMC — Patient Portal')
            ->favicon(asset('images/favicon.ico'))
            ->discoverPages(
                in: app_path('Filament/Patient/Pages'),
                for: 'App\Filament\Patient\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Patient/Widgets'),
                for: 'App\Filament\Patient\Widgets'
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