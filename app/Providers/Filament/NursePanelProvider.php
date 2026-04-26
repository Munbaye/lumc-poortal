<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
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
            ->brandLogo(fn() => new HtmlString(
                '<div style="display:flex;align-items:center;gap:10px;">'
                    . '<img src="' . asset('images/lumc-logo.png') . '" alt="LUMC Logo" style="height:40px;width:auto;">'
                    . '<span style="font-weight:700;color:#111827;">LUMC — Nurse Portal</span>'
                    . '</div>'
            ))
            ->favicon(asset('images/lumc-logo.png'))
            ->discoverPages(
                in: app_path('Filament/Nurse/Pages'),
                for: 'App\Filament\Nurse\Pages'
            )
            ->discoverResources(
                in: app_path('Filament/Nurse/Resources'),
                for: 'App\Filament\Nurse\Resources'
            )
            ->discoverWidgets(
                in: app_path('Filament/Nurse/Widgets'),
                for: 'App\Filament\Nurse\Widgets'
            )
            ->pages([
                // NICU-specific pages only
                CreateProvisionalRecord::class,
                CompleteBabyInformation::class,
                BreastfeedingObservation::class,
            ])
            ->navigationGroups([
                'NICU Care',  // Only NICU-related navigation
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