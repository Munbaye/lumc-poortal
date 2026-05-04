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
                    <span class="lumc-brand-text" style="font-weight:800;font-size:.9rem;letter-spacing:.04em;
                                 white-space:nowrap;text-transform:uppercase;">LUMC-NURSE</span>
                </div>
                <style>
                    .fi-logo .lumc-brand-text { color:#be123c; }
                    .dark .fi-logo .lumc-brand-text { color:#fda4af; }
                </style>'
            ))
            ->brandLogoHeight('auto')
            ->renderHook(
                'panels::sidebar.nav.start',
                fn () => new \Illuminate\Support\HtmlString(
                    auth()->check()
                        ? '<div class="lumc-sidebar-user" style="padding:.875rem 1rem .75rem;margin-bottom:.5rem;">
                               <div style="display:flex;align-items:center;gap:.75rem;">
                                   <div style="width:42px;height:42px;border-radius:50%;flex-shrink:0;
                                               background:#be123c;color:#fff;
                                               display:flex;align-items:center;justify-content:center;
                                               font-weight:800;font-size:1.05rem;
                                               border:2px solid rgba(255,255,255,.3);">'
                                       . strtoupper(substr(auth()->user()->name ?? 'N', 0, 1)) .
                                   '</div>
                                   <div style="min-width:0;">
                                       <div class="lumc-user-name" style="font-weight:700;font-size:.875rem;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
                                           . e(auth()->user()->name ?? 'Nurse') .
                                       '</div>
                                       <div class="lumc-user-role" style="font-size:.72rem;margin-top:.15rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Nurse</div>
                                   </div>
                               </div>
                           </div>'
                        : ''
                )
            )
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