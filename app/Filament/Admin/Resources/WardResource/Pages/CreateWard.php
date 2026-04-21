<?php
// ── CreateWard.php ────────────────────────────────────────────────────────────
// File: app/Filament/Admin/Resources/WardResource/Pages/CreateWard.php

namespace App\Filament\Admin\Resources\WardResource\Pages;

use App\Filament\Admin\Resources\WardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWard extends CreateRecord
{
    protected static string $resource = WardResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}