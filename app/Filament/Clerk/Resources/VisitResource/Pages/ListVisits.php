<?php
namespace App\Filament\Clerk\Resources\VisitResource\Pages;

use App\Filament\Clerk\Resources\VisitResource;
use Filament\Resources\Pages\ListRecords;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return []; // no create button
    }
}