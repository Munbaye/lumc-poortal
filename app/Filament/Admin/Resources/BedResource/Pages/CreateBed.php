<?php
// File: app/Filament/Admin/Resources/BedResource/Pages/CreateBed.php

namespace App\Filament\Admin\Resources\BedResource\Pages;

use App\Filament\Admin\Resources\BedResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBed extends CreateRecord
{
    protected static string $resource = BedResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}