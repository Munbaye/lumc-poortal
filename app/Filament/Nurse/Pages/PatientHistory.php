<?php

namespace App\Filament\Nurse\Pages;

use App\Filament\Shared\Pages\BasePatientHistoryPage;

class PatientHistory extends BasePatientHistoryPage
{
    protected static string $view = 'filament.nurse.pages.patient-history';

    protected function getVisitUrl(int $visitId): string
    {
        return NurseChart::getUrl(['visitId' => $visitId]);
    }

    public function getPatientListUrl(): string
    {
        return PatientList::getUrl();
    }
}
