<?php
// File: app/Filament/Admin/Resources/WardResource/Pages/EditWard.php

namespace App\Filament\Admin\Resources\WardResource\Pages;

use App\Filament\Admin\Resources\WardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWard extends EditRecord
{
    protected static string $resource = WardResource::class;

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