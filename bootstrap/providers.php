<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\ClerkPanelProvider::class,
    App\Providers\Filament\DoctorPanelProvider::class,
    App\Providers\Filament\NursePanelProvider::class,
    App\Providers\Filament\TechPanelProvider::class,
    App\Providers\Filament\PatientPanelProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
];
