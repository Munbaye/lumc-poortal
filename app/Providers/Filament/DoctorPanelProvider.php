<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use App\Filament\Doctor\Resources\NicuBabyResource;
use App\Filament\Doctor\Pages\PatientAssessment;
use App\Filament\Doctor\Pages\PatientChart;
use App\Filament\Doctor\Pages\PatientHistory;
use App\Filament\Doctor\Pages\NicuAssessment;
use App\Filament\Doctor\Pages\BallardScore;
use App\Filament\Doctor\Pages\DischargeSummaryPage;
use App\Filament\Doctor\Pages\MySignature;
use App\Http\Middleware\Filament\StaffAuthenticate;
use Illuminate\Support\HtmlString;

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('doctor')
            ->path('doctor')
            ->homeUrl('/doctor/patient-queues')
            ->colors(['primary' => Color::Teal])
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
                        <span class="lumc-brand-role">Doctor</span>
                    </span>
                </div>
                <style>
                    .fi-logo .lumc-brand-lumc { color:#0d9488; }
                    .fi-logo .lumc-brand-role { color:#374151;font-size:.78rem;font-weight:600; }
                    html.dark .fi-logo .lumc-brand-lumc { color:#2dd4bf; }
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
            ->resources([
                PatientQueueResource::class,
                AdmittedPatientsResource::class,
                NicuBabyResource::class,
            ])
            ->pages([
                PatientAssessment::class,
                PatientChart::class,
                PatientHistory::class,
                NicuAssessment::class,
                BallardScore::class,
                DischargeSummaryPage::class,
                MySignature::class,
            ])
            ->widgets([])
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