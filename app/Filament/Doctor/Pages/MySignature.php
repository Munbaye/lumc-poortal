<?php

namespace App\Filament\Doctor\Pages;

use App\Filament\Shared\Pages\BaseSignaturePage;

class MySignature extends BaseSignaturePage
{
    // DOCTOR = Teal #0f766e
    protected string $accentColor = '#0f766e';
    protected string $accentLight = 'rgba(15,118,110,.08)';
    protected string $accentMid   = 'rgba(15,118,110,.28)';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?int    $navigationSort  = 99;
}