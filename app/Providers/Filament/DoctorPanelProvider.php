<?php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Filament\Doctor\Pages\PatientAssessment;

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('doctor')
            ->path('doctor')
            ->login()
            // ✅ homeUrl must point to an actual registered route (not the panel root)
            //    PatientQueueResource registers at /doctor/patient-queues
            ->homeUrl('/doctor/patient-queues')
            ->colors(['primary' => Color::Teal])
            ->brandName('LUMC — Doctor Portal')
            ->favicon(asset('images/favicon.ico'))
            ->resources([
                PatientQueueResource::class,
            ])
            ->pages([
                PatientAssessment::class,
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
            ->authMiddleware([\Filament\Http\Middleware\Authenticate::class]);
    }
}