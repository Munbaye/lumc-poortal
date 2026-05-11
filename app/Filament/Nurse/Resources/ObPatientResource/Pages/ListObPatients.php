<?php

namespace App\Filament\Nurse\Resources\ObPatientResource\Pages;

use App\Filament\Nurse\Resources\ObPatientResource;
use Filament\Resources\Pages\ListRecords;

class ListObPatients extends ListRecords
{
    protected static string $resource = ObPatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('new_ob_patient')
                ->label('+ New OB Patient')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(\App\Filament\Nurse\Pages\CreateProvisionalObRecord::getUrl()),
        ];
    }
}