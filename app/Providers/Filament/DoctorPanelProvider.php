<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use App\Filament\Doctor\Pages\PatientAssessment;
use App\Filament\Doctor\Pages\PatientChart;
use App\Filament\Doctor\Pages\PatientHistory;
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
            ->brandLogo(fn() => new HtmlString(
                '<div style="display:flex;align-items:center;gap:10px;">'
                    . '<img src="' . asset('images/lumc-logo.png') . '" alt="LUMC Logo" style="height:40px;width:auto;">'
                    . '<span style="font-weight:700;color:#111827;">LUMC — Doctor Portal</span>'
                    . '</div>'
            ))
            ->favicon(asset('images/lumc-logo.png'))
            ->resources([
                PatientQueueResource::class,
                AdmittedPatientsResource::class,
            ])
            ->pages([
                PatientAssessment::class,
                PatientChart::class,
                PatientHistory::class,
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
