<?php
namespace App\Filament\Nurse\Resources\DoctorsOrderResource\Pages;

use App\Filament\Nurse\Resources\DoctorsOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListDoctorsOrders extends ListRecords
{
    protected static string $resource = DoctorsOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}