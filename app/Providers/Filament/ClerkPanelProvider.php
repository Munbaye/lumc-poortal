<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            ->brandLogo(fn() => new HtmlString(
                '<div style="display:flex;align-items:center;gap:10px;">'
                    . '<img src="' . asset('images/lumc-logo.png') . '" alt="LUMC Logo" style="height:40px;width:auto;">'
                    . '<span style="font-weight:700;color:#111827;">LUMC — Clerk Portal</span>'
                    . '</div>'
            ))
            ->favicon(asset('images/lumc-logo.png'))
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
                // Only Clerk pages here - NICU pages are now in Nurse panel
                ConvertToPermanent::class,
                EditBabyInformation::class,
            ])
            ->navigationGroups([
                'Patient Management',
                'NICU Management',  // For clerk's NICU conversion page
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
