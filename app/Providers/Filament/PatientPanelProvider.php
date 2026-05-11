<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Http\Middleware\Filament\PatientAuthenticate;
use App\Http\Middleware\Filament\PatientForcePasswordChange;
use Illuminate\Support\HtmlString;

class PatientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('patient')
            ->path('patient')
            ->colors(['primary' => Color::Green])
            ->brandLogo(fn() => new HtmlString('
                <div style="display:flex;align-items:center;gap:.625rem;">
                    <img src="' . asset('images/lumc-logo.png') . '"
                         alt="LUMC"
                         style="height:32px;width:32px;object-fit:contain;flex-shrink:0;
                                filter:drop-shadow(0 1px 3px rgba(0,0,0,.18));">
                    <span style="
                        font-size:.8rem;font-weight:700;letter-spacing:.01em;
                        white-space:nowrap;line-height:1.1;
                        display:flex;align-items:center;gap:.45rem;">
                        <span class="lumc-brand-lumc" style="
                            font-weight:900;letter-spacing:.04em;
                            font-size:.85rem;">LUMC</span>
                        <span style="
                            opacity:.35;font-weight:300;font-size:1rem;
                            line-height:1;color:currentColor;">|</span>
                        <span class="lumc-brand-role">Patient</span>
                    </span>
                </div>
                <style>
                    .fi-logo .lumc-brand-lumc { color:#16a34a; }
                    .fi-logo .lumc-brand-role { color:#374151;font-size:.78rem;font-weight:600; }
                    html.dark .fi-logo .lumc-brand-lumc { color:#4ade80; }
                    html.dark .fi-logo .lumc-brand-role { color:#94a3b8; }
                </style>
            '))
            ->brandLogoHeight('auto')
            ->favicon(asset('images/lumc-logo.png'))
            ->login(false)
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#account'),
            ])
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
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
                PatientForcePasswordChange::class,
                PatientAuthenticate::class,
            ])
            ->authGuard('web');
    }
}