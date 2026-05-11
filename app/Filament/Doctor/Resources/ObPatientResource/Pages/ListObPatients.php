<?php

namespace App\Filament\Doctor\Resources\ObPatientResource\Pages;

use App\Filament\Doctor\Resources\ObPatientResource;
use Filament\Resources\Pages\ListRecords;

class ListObPatients extends ListRecords
{
    protected static string $resource = ObPatientResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}