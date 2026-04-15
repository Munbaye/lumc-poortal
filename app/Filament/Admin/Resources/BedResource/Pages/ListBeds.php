<?php
// File: app/Filament/Admin/Resources/BedResource/Pages/ListBeds.php

namespace App\Filament\Admin\Resources\BedResource\Pages;

use App\Filament\Admin\Resources\BedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeds extends ListRecords
{
    protected static string $resource = BedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Bed Manually')
                ->icon('heroicon-o-plus'),
        ];
    }
}