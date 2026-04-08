<?php

namespace App\Filament\Clerk\Pages;

use Filament\Pages\Page;

class MySignature extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?string $title           = 'My Signature';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.shared.my-signature';
}