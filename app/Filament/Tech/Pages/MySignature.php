<?php

namespace App\Filament\Tech\Pages;

use App\Filament\Shared\Pages\BaseSignaturePage;

class MySignature extends BaseSignaturePage
{
    // TECH = Orange #ea580c (default; changes per specialty but orange is fine as default)
    protected string $accentColor = '#ea580c';
    protected string $accentLight = 'rgba(234,88,12,.08)';
    protected string $accentMid   = 'rgba(234,88,12,.28)';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?int    $navigationSort  = 99;

    /**
     * Override accent at runtime so it follows the tech user's specialty colour.
     */
    public function getViewData(): array
    {
        $spec = strtolower(auth()->user()?->specialty ?? '');

        $isRad = str_contains($spec, 'radiolog') || str_contains($spec, 'rad tech')
               || str_contains($spec, 'radtech') || str_contains($spec, 'x-ray');
        $isMed = str_contains($spec, 'med tech') || str_contains($spec, 'medtech')
               || str_contains($spec, 'laboratory') || str_contains($spec, 'medical tech');

        if ($isRad && !$isMed) {
            $this->accentColor = '#475569';
            $this->accentLight = 'rgba(71,85,105,.08)';
            $this->accentMid   = 'rgba(71,85,105,.28)';
        } elseif ($isMed && !$isRad) {
            $this->accentColor = '#0f766e';
            $this->accentLight = 'rgba(15,118,110,.08)';
            $this->accentMid   = 'rgba(15,118,110,.28)';
        }

        return parent::getViewData();
    }
}