<?php
namespace App\Filament\Nurse\Resources\NursesNoteResource\Pages;

use App\Filament\Nurse\Resources\NursesNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNursesNote extends CreateRecord
{
    protected static string $resource = NursesNoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nurse_id'] = auth()->id();
        return $data;
    }
}