<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Illuminate\Support\HtmlString;
use App\Filament\Clerk\Pages\ConvertToPermanent;
use App\Filament\Clerk\Pages\EditBabyInformation;

class ClerkPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('clerk')
            ->path('clerk')
            ->colors(['primary' => Color::Amber])
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
                        <span class="lumc-brand-role">Clerk</span>
                    </span>
                </div>
                <style>
                    .fi-logo .lumc-brand-lumc { color:#d97706; }
                    .fi-logo .lumc-brand-role { color:#374151;font-size:.78rem;font-weight:600; }
                    html.dark .fi-logo .lumc-brand-lumc { color:#fbbf24; }
                    html.dark .fi-logo .lumc-brand-role { color:#94a3b8; }
                </style>
            '))
            ->brandLogoHeight('auto')
            ->favicon(asset('images/lumc-logo.png'))
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#account'),
            ])
            ->discoverPages(
                in: app_path('Filament/Clerk/Pages'),
                for: 'App\Filament\Clerk\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Clerk/Resources'),
                for: 'App\Filament\Clerk\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Clerk/Widgets'),
                for: 'App\Filament\Clerk\Widgets'
            )
            ->pages([
                ConvertToPermanent::class,
                EditBabyInformation::class,
            ])
            ->navigationGroups([
                'Patient Management',
                'NICU Management',
                'Reports',
            ])
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