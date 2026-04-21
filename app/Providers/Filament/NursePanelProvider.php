<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Filament\Navigation\MenuItem;

class NursePanelProvider extends PanelProvider
{
    // NURSE = Rose/Pink #be123c

    private static function css(): string
    {
        return self::panelCss('#be123c', '#be123c', 'rgba(190,18,60,.1)', 'rgba(190,18,60,.3)', '#be123c');
    }

    private static function panelCss(string $navbar, string $sidebarHeader, string $activeBgLight, string $activeBgDark, string $activeText): string
    {
        return '
        <style>
        .fi-topbar,
        .fi-topbar nav,
        .fi-topbar > div,
        .fi-topbar > nav { background-color: ' . $navbar . ' !important; }
        .fi-topbar { border-bottom:none !important; box-shadow:0 2px 8px rgba(0,0,0,.25) !important; }
        .fi-topbar .fi-logo span { color:#fff !important; }
        .fi-topbar svg, .fi-topbar button svg, .fi-topbar a svg,
        .fi-topbar .fi-icon-btn svg, .fi-topbar .fi-btn svg { color:#fff !important; stroke:#fff !important; }
        .fi-topbar .fi-icon-btn { color:#fff !important; }
        .fi-topbar .fi-icon-btn:hover { background:rgba(255,255,255,.12) !important; }
        .fi-topbar .fi-avatar { border:2px solid rgba(255,255,255,.4) !important; }
        .fi-theme-switcher { background:rgba(255,255,255,.15) !important; border-radius:.5rem !important; }
        button.fi-theme-switcher-btn { color:rgba(255,255,255,.7) !important; }
        button.fi-theme-switcher-btn svg { color:rgba(255,255,255,.7) !important; stroke:rgba(255,255,255,.7) !important; }
        button.fi-theme-switcher-btn[aria-pressed="true"] { background:rgba(255,255,255,.28) !important; }
        button.fi-theme-switcher-btn[aria-pressed="true"] svg { color:#fff !important; stroke:#fff !important; }

        html:not(.dark) .fi-dropdown-panel { background:#fff !important; border:1px solid #e5e7eb !important; box-shadow:0 4px 20px rgba(0,0,0,.1) !important; }
        html:not(.dark) .fi-dropdown-list a, html:not(.dark) .fi-dropdown-list button { color:#374151 !important; font-size:.875rem !important; }
        html:not(.dark) .fi-dropdown-list a svg, html:not(.dark) .fi-dropdown-list button svg { color:#6b7280 !important; stroke:#6b7280 !important; }
        html.dark .fi-dropdown-panel { background:#1e293b !important; border:1px solid #334155 !important; }
        html.dark .fi-dropdown-list a, html.dark .fi-dropdown-list button { color:#e2e8f0 !important; font-size:.875rem !important; }
        html.dark .fi-dropdown-list a svg, html.dark .fi-dropdown-list button svg { color:#94a3b8 !important; stroke:#94a3b8 !important; }

        .fi-sidebar-header { background-color:' . $sidebarHeader . ' !important; border-bottom:none !important; border-right:none !important; }
        .fi-sidebar-header .fi-logo span, .fi-sidebar-header .fi-logo svg { color:#fff !important; stroke:#fff !important; }

        html:not(.dark) .fi-sidebar { background:#fff !important; border-right:1px solid #e5e7eb !important; }
        html:not(.dark) .fi-sidebar-item-label { color:#374151 !important; font-size:.875rem !important; font-weight:500 !important; }
        html:not(.dark) .fi-sidebar-item-button svg { color:#6b7280 !important; stroke:#6b7280 !important; }
        html:not(.dark) .fi-sidebar-item-button:hover { background:rgba(0,0,0,.04) !important; border-radius:.5rem !important; }
        html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button { background-color:' . $activeBgLight . ' !important; border-radius:.6rem !important; }
        html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-label { color:' . $activeText . ' !important; font-weight:700 !important; }
        html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button svg { color:' . $activeText . ' !important; stroke:' . $activeText . ' !important; }
        html:not(.dark) .lumc-sidebar-user { border-bottom:1px solid #e5e7eb !important; }
        html:not(.dark) .lumc-user-name { color:#111827 !important; }
        html:not(.dark) .lumc-user-role { color:#6b7280 !important; }

        html.dark .fi-sidebar { background:#0f172a !important; border-right:1px solid #1e293b !important; }
        html.dark .fi-sidebar-header { background-color:' . $sidebarHeader . ' !important; }
        html.dark .fi-sidebar-item-label { color:#cbd5e1 !important; font-size:.875rem !important; font-weight:500 !important; }
        html.dark .fi-sidebar-item-button svg { color:#94a3b8 !important; stroke:#94a3b8 !important; }
        html.dark .fi-sidebar-item-button:hover { background:rgba(255,255,255,.06) !important; border-radius:.5rem !important; }
        html.dark .fi-sidebar-item-active .fi-sidebar-item-button { background-color:' . $activeBgDark . ' !important; border-radius:.6rem !important; }
        html.dark .fi-sidebar-item-active .fi-sidebar-item-label { color:#fff !important; font-weight:700 !important; }
        html.dark .fi-sidebar-item-active .fi-sidebar-item-button svg { color:#fff !important; stroke:#fff !important; }
        html.dark .lumc-sidebar-user { border-bottom:1px solid #1e293b !important; }
        html.dark .lumc-user-name { color:#f1f5f9 !important; }
        html.dark .lumc-user-role { color:#94a3b8 !important; }

        html:not(.dark) .fi-main { background:#f8fafc !important; }
        html.dark       .fi-main { background:#020617 !important; }
        </style>';
    }

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
                                background:rgba(255,255,255,.18);padding:2px;flex-shrink:0;" alt="LUMC">
                    <span style="font-weight:800;font-size:.9rem;letter-spacing:.04em;
                                 white-space:nowrap;color:#fff;text-transform:uppercase;">LUMC-NURSE</span>
                </div>'
            ))
            ->brandLogoHeight('auto')
            ->renderHook('panels::head.end', fn () => new \Illuminate\Support\HtmlString(self::css()))
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