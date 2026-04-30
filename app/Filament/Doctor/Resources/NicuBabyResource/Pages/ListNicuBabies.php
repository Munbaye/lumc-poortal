<?php

namespace App\Filament\Doctor\Resources\NicuBabyResource\Pages;

use App\Filament\Doctor\Resources\NicuBabyResource;
use Filament\Resources\Pages\ListRecords;

class ListNicuBabies extends ListRecords
{
    protected static string $resource = NicuBabyResource::class;
    
    protected static ?string $title = 'NICU Babies';
}