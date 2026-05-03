<?php
namespace App\Filament\Clerk\Resources\VisitResource\Pages;

use App\Filament\Clerk\Resources\VisitResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Visits')
                ->icon('heroicon-o-clipboard-document-list'),

            'opd' => Tab::make('OPD')
                ->icon('heroicon-o-building-office')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('visit_type', 'OPD')
                ),

            'er' => Tab::make('ER')
                ->icon('heroicon-o-bolt')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('visit_type', 'ER')
                ),

            'nicu' => Tab::make('NICU')
                ->icon('heroicon-o-heart')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('visit_type', 'NICU')
                        ->whereHas('patient', fn ($q) => $q->where('is_provisional', false))
                ),
        ];
    }
}