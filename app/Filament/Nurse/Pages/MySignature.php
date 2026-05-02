<?php

namespace App\Filament\Nurse\Pages;

use App\Filament\Shared\Pages\BaseSignaturePage;

class MySignature extends BaseSignaturePage
{
    // NURSE = Rose #be123c
    protected string $accentColor = '#be123c';
    protected string $accentLight = 'rgba(190,18,60,.08)';
    protected string $accentMid   = 'rgba(190,18,60,.28)';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?int    $navigationSort  = 99;
}