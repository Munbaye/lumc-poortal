<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Shared\Pages\BaseSignaturePage;
use App\Models\User;

class MySignature extends BaseSignaturePage
{
    // ADMIN = Blue #1d4ed8
    protected string $accentColor = '#1d4ed8';
    protected string $accentLight = 'rgba(29,78,216,.08)';
    protected string $accentMid   = 'rgba(29,78,216,.28)';

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.admin.pages.my-signature';

    public function getViewData(): array
    {
        $data = parent::getViewData();

        // Collect all non-patient, non-admin staff signature statuses
        $staffSignatures = User::whereDoesntHave('roles', fn ($q) => $q->whereIn('name', ['patient']))
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name', 'panel', 'specialty', 'signature'])
            ->map(fn ($u) => [
                'id'        => $u->id,
                'name'      => $u->name,
                'panel'     => ucfirst($u->panel ?? 'staff'),
                'specialty' => $u->specialty,
                'has_sig'   => !empty($u->signature),
            ]);

        $data['staffSignatures']   = $staffSignatures;
        $data['staffWithSig']      = $staffSignatures->where('has_sig', true)->count();
        $data['staffWithoutSig']   = $staffSignatures->where('has_sig', false)->count();

        return $data;
    }
}