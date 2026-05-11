<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Illuminate\Support\HtmlString;

class TechPanelProvider extends PanelProvider
{
    private static function isMed(): bool
    {
        return auth()->user()?->hasRole('tech-med') ?? false;
    }

    private static function isRad(): bool
    {
        return auth()->user()?->hasRole('tech-rad') ?? false;
    }

    private static function accentColor(): string
    {
        return match(true) {
            self::isMed() => '#0f766e',
            self::isRad() => '#475569',
            default       => '#ea580c',
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

    private static function roleLabel(): string
    {
        return match(true) {
            self::isMed() => 'MedTech',
            self::isRad() => 'RadTech',
            default       => 'Tech',
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

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tech')
            ->path('tech')
            ->colors(fn (): array => self::primaryColor())
            ->brandName('')
            ->brandLogo(fn () => new HtmlString(
                '<div style="display:flex;align-items:center;gap:.625rem;">
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
                        <span class="lumc-brand-role">' . self::roleLabel() . '</span>
                    </span>
                </div>
                <style>
                    .fi-logo .lumc-brand-lumc { color:' . self::accentColor() . '; }
                    .fi-logo .lumc-brand-role { color:#374151;font-size:.78rem;font-weight:600; }
                    html.dark .fi-logo .lumc-brand-lumc { color:' . self::accentColorDark() . '; }
                    html.dark .fi-logo .lumc-brand-role { color:#94a3b8; }
                </style>'
            ))
            ->brandLogoHeight('auto')
            ->sidebarCollapsibleOnDesktop(false)
            ->globalSearch(false)
            ->favicon(asset('images/lumc-logo.png'))
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#account'),
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