<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Filament\Navigation\MenuItem;
use Illuminate\Support\HtmlString;

class TechPanelProvider extends PanelProvider
{
    // ── Role-based specialty detection ────────────────────────────────────────
    // Roles assigned in the users table:
    //   tech-med  → Medical Technologist (lab requests only)
    //   tech-rad  → Radiologic Technologist (rad requests only)
    //   tech-tech → General Tech (sees both queues)

    private static function isMed(): bool
    {
        return auth()->user()?->hasRole('tech-med') ?? false;
    }

    private static function isRad(): bool
    {
        return auth()->user()?->hasRole('tech-rad') ?? false;
    }

    // ── Per-specialty values ──────────────────────────────────────────────────

    private static function accentColor(): string
    {
        return match(true) {
            self::isMed() => '#0f766e', // teal
            self::isRad() => '#475569', // slate
            default       => '#ea580c', // orange
        };
    }

    private static function accentColorDark(): string
    {
        return match(true) {
            self::isMed() => '#5eead4',
            self::isRad() => '#94a3b8',
            default       => '#fb923c',
        };
    }

    private static function brandName(): string
    {
        return match(true) {
            self::isMed() => 'LUMC — MedTech Portal',
            self::isRad() => 'LUMC — RadTech Portal',
            default       => 'LUMC — Tech Portal',
        };
    }

    private static function primaryColor(): array
    {
        return match(true) {
            self::isMed() => ['primary' => Color::Teal],
            self::isRad() => ['primary' => Color::Slate],
            default       => ['primary' => Color::Orange],
        };
    }

    // ── Panel definition ──────────────────────────────────────────────────────

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tech')
            ->path('tech')

            ->colors(fn (): array => self::primaryColor())

            ->brandName('')

            ->brandLogo(fn () => new HtmlString(
                '<div style="display:flex;align-items:center;gap:.55rem;">
                    <img src="' . asset('images/lumc-logo.png') . '"
                         style="width:30px;height:30px;object-fit:contain;border-radius:50%;
                                background:rgba(0,0,0,.06);padding:2px;flex-shrink:0;"
                         alt="LUMC">
                    <span class="lumc-tech-brand-text"
                          style="font-weight:800;font-size:.9rem;letter-spacing:.04em;
                                 white-space:nowrap;text-transform:uppercase;">'
                . self::brandName() .
                '</span>
                </div>
                <style>
                    .fi-logo .lumc-tech-brand-text { color:' . self::accentColor() . '; }
                    .dark .fi-logo .lumc-tech-brand-text { color:' . self::accentColorDark() . '; }
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

            ->discoverPages(in: app_path('Filament/Tech/Pages'), for: 'App\\Filament\\Tech\\Pages')
            ->discoverResources(in: app_path('Filament/Tech/Resources'), for: 'App\\Filament\\Tech\\Resources')
            ->discoverWidgets(in: app_path('Filament/Tech/Widgets'), for: 'App\\Filament\\Tech\\Widgets')

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