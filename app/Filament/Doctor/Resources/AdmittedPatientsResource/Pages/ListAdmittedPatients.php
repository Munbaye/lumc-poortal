<?php

namespace App\Filament\Doctor\Resources\AdmittedPatientsResource\Pages;

use App\Filament\Doctor\Resources\AdmittedPatientsResource;
use Filament\Resources\Pages\ListRecords;

class ListAdmittedPatients extends ListRecords
{
    protected static string $resource = AdmittedPatientsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}