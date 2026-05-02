<?php

namespace App\Filament\Clerk\Pages;

use App\Filament\Shared\Pages\BaseSignaturePage;

class MySignature extends BaseSignaturePage
{
    // CLERK = Amber #d97706
    protected string $accentColor = '#d97706';
    protected string $accentLight = 'rgba(217,119,6,.08)';
    protected string $accentMid   = 'rgba(217,119,6,.28)';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?int    $navigationSort  = 99;
}