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
        $pendingNicu = Visit::where('visit_type', 'NICU')
            ->whereHas('patient', fn ($q) => $q->where('is_provisional', true))
            ->count();

        return [
            'all' => Tab::make('All Visits')
                ->icon('heroicon-o-clipboard-document-list'),

            'nicu_pending' => Tab::make('NICU – Needs Registration')
                ->icon('heroicon-o-bell-alert')
                ->badge($pendingNicu ?: null)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('visit_type', 'NICU')
                        ->whereHas('patient', fn ($q) => $q->where('is_provisional', true))
                ),

            'opd_er' => Tab::make('OPD / ER')
                ->icon('heroicon-o-building-office')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereIn('visit_type', ['OPD', 'ER'])
                ),

            'nicu_admitted' => Tab::make('NICU – Admitted')
                ->icon('heroicon-o-heart')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('visit_type', 'NICU')
                        ->whereHas('patient', fn ($q) => $q->where('is_provisional', false))
                ),

            'provisional' => Tab::make('Provisional (Need Conversion)')
                ->icon('heroicon-o-exclamation-triangle')
                ->badge(
                    Visit::whereHas('patient', fn ($q) => $q->where('is_provisional', true))->count() ?: null
                )
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('patient', fn ($q) => $q->where('is_provisional', true))
                ),
        ];
    }
}