<?php

namespace App\Filament\Clerk\Pages;

use App\Filament\Shared\Pages\BaseBedManagementPage;

class BedManagement extends BaseBedManagementPage
{
    protected static ?int $navigationSort = 5;

    public function getEmptyRoomsIconClassProperty(): string
    {
        return 'w-8 h-8 mx-auto mb-3 opacity-40';
    }
}
