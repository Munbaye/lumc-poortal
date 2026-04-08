<?php
// ── ListWards.php ─────────────────────────────────────────────────────────────
// File: app/Filament/Admin/Resources/WardResource/Pages/ListWards.php

namespace App\Filament\Admin\Resources\WardResource\Pages;

use App\Filament\Admin\Resources\WardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWards extends ListRecords
{
    protected static string $resource = WardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Ward')
                ->icon('heroicon-o-plus'),
        ];
    }
}