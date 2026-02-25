<?php
namespace App\Filament\Tech\Resources\ResultUploadResource\Pages;

use App\Filament\Tech\Resources\ResultUploadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResultUpload extends CreateRecord
{
    protected static string $resource = ResultUploadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();
        return $data;
    }
}