<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Filament\Navigation\MenuItem;

class TechPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tech')
            ->path('tech')
            ->colors(['primary' => Color::Orange])

            // Generic name — sidebar brand is handled in the blade view
            // via JS injection so it always reflects the authenticated user's specialty
            ->brandName(function (): string {
                $s = strtolower(auth()->user()?->specialty ?? '');
                if (str_contains($s, 'med tech') || str_contains($s, 'medtech') ||
                    str_contains($s, 'laboratory') || str_contains($s, 'medical technologist')) {
                    return 'LUMC — MedTech';
                }
                if (str_contains($s, 'radiolog') || str_contains($s, 'rad tech') || str_contains($s, 'radtech')) {
                    return 'LUMC — RadTech';
                }
                return 'LUMC — Tech Portal';
            })
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString(
                '<img src="' . asset('images/lumc-logo.png') . '" alt="LUMC"
                      style="width:38px;height:38px;object-fit:contain;">'
            ))
            ->brandLogoHeight('auto')

            ->sidebarCollapsibleOnDesktop(false)
            ->globalSearch(true)
            ->favicon(asset('images/lumc-logo.png'))

            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn () => '#'),
                'settings' => MenuItem::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn () => '#'),
            ])

            ->discoverPages(
                in: app_path('Filament/Tech/Pages'),
                for: 'App\\Filament\\Tech\\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Tech/Resources'),
                for: 'App\\Filament\\Tech\\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Tech/Widgets'),
                for: 'App\\Filament\\Tech\\Widgets'
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
