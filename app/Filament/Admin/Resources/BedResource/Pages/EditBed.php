<?php
// File: app/Filament/Admin/Resources/BedResource/Pages/EditBed.php

namespace App\Filament\Admin\Resources\BedResource\Pages;

use App\Filament\Admin\Resources\BedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBed extends EditRecord
{
    protected static string $resource = BedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}