<?php

namespace App\Filament\Shared\Pages;

use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Base class shared by all panel-specific MySignature pages.
 * Subclasses only need to override $accentColor / $accentLight / $accentMid
 * and the navigation registration details.
 */
abstract class BaseSignaturePage extends Page
{
    /* ── Override in each subclass ── */
    protected string $accentColor = '#374151';
    protected string $accentLight = 'rgba(55,65,81,.08)';
    protected string $accentMid   = 'rgba(55,65,81,.25)';

    /* ── Filament page boilerplate ── */
    protected static string $view = 'filament.shared.my-signature';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?string $title           = 'My Signature';
    protected static ?int    $navigationSort  = 99;

    /* ── View data ── */
    public function getViewData(): array
    {
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()->getId();

        return [
            'accentColor'      => $this->accentColor,
            'accentLight'      => $this->accentLight,
            'accentMid'        => $this->accentMid,
            'currentSignature' => $user->signature ?? null,
            'saveUrl'          => route('signature.save', ['panel' => $panelId]),
        ];
    }

    /* ── Title ── */
    public function getTitle(): string
    {
        return 'My Signature';
    }
}