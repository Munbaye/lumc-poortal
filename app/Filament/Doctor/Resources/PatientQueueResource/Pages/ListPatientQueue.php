<?php
namespace App\Filament\Doctor\Resources\PatientQueueResource\Pages;

use App\Filament\Doctor\Resources\PatientQueueResource;
use Filament\Resources\Pages\ListRecords;

class ListPatientQueue extends ListRecords
{
    protected static string $resource = PatientQueueResource::class;

    protected function getHeaderActions(): array
    {
        return []; // doctors don't create visits
    }
}