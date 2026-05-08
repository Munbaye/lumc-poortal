<?php

namespace App\Filament\Doctor\Pages;

use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use App\Filament\Shared\Pages\BasePatientHistoryPage;

class PatientHistory extends BasePatientHistoryPage
{
    protected static string $view = 'filament.doctor.pages.patient-history';

    protected function getVisitUrl(int $visitId): string
    {
        return PatientChart::getUrl(['visitId' => $visitId]);
    }

    public function getPatientListUrl(): string
    {
        return AdmittedPatientsResource::getUrl('index');
    }
}
