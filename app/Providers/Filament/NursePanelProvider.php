<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Filament\Navigation\MenuItem;
use Illuminate\Support\HtmlString;
use App\Filament\Nurse\Pages\CreateProvisionalRecord;
use App\Filament\Nurse\Pages\CompleteBabyInformation;
use App\Filament\Nurse\Pages\BreastfeedingObservation;

class NursePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('nurse')
            ->path('nurse')
            ->colors(['primary' => Color::Rose])
            ->brandName('')
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString(
                '<div style="display:flex;align-items:center;gap:.55rem;">
                    <img src="' . asset('images/lumc-logo.png') . '"
                         style="width:30px;height:30px;object-fit:contain;border-radius:50%;
                                background:rgba(190,18,60,.12);padding:2px;flex-shrink:0;" alt="LUMC">
                    <span class="lumc-brand-text"
                          style="font-weight:800;font-size:.9rem;letter-spacing:.04em;
                                 white-space:nowrap;text-transform:uppercase;">LUMC-NURSE</span>
                </div>
                <style>
                    .fi-logo .lumc-brand-text { color:#be123c; }
                    .dark .fi-logo .lumc-brand-text { color:#fda4af; }
                </style>'
            ))
            ->brandLogoHeight('auto')
            ->sidebarCollapsibleOnDesktop(false)
            ->globalSearch(false)
            ->favicon(asset('images/lumc-logo.png'))
            ->userMenuItems([
                'profile'  => MenuItem::make()->label('My Profile')->icon('heroicon-o-user-circle')->url(fn () => '#'),
                'settings' => MenuItem::make()->label('Settings')->icon('heroicon-o-cog-6-tooth')->url(fn () => '#'),
            ])
            ->discoverPages(in: app_path('Filament/Nurse/Pages'), for: 'App\\Filament\\Nurse\\Pages')
            ->discoverResources(in: app_path('Filament/Nurse/Resources'), for: 'App\\Filament\\Nurse\\Resources')
            ->discoverWidgets(in: app_path('Filament/Nurse/Widgets'), for: 'App\\Filament\\Nurse\\Widgets')
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