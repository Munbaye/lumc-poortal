<?php

namespace App\Filament\Tech\Resources\ResultUploadResource\Pages;

use App\Filament\Tech\Resources\ResultUploadResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListResultUploads extends ListRecords
{
    protected static string $resource = ResultUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Upload Result')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->url(static::getResource()::getUrl('create')),
        ];
    }
}