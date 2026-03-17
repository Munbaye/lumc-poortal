<?php

namespace App\Filament\Doctor\Resources\PatientQueueResource\Pages;

use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

/**
 * Patient Queue — OPD and ER pending patients only.
 *
 * Admitted patients have been moved to their own resource:
 * AdmittedPatientsResource → /doctor/admitted-patients
 */
class ListPatientQueue extends ListRecords
{
    protected static string $resource = PatientQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        // Statuses that mean "still being processed" (not yet admitted/discharged/referred)
        $pendingStatuses = ['registered', 'vitals_done', 'assessed'];

        return [

            // ── OPD Queue ─────────────────────────────────────────────────────
            'opd' => Tab::make('OPD Queue')
                ->icon('heroicon-o-clipboard-document-list')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('visit_type', 'OPD')
                    ->whereIn('status', $pendingStatuses)
                    ->orderBy('registered_at', 'asc')
                )
                ->badge(fn () =>
                    Visit::where('visit_type', 'OPD')
                        ->whereIn('status', $pendingStatuses)
                        ->count()
                ),

            // ── ER Queue ──────────────────────────────────────────────────────
            'er' => Tab::make('ER Queue')
                ->icon('heroicon-o-bolt')
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('visit_type', 'ER')
                    ->whereIn('status', $pendingStatuses)
                    ->orderBy('registered_at', 'asc')
                )
                ->badge(fn () =>
                    Visit::where('visit_type', 'ER')
                        ->whereIn('status', $pendingStatuses)
                        ->count()
                ),

            // NOTE: "Admitted" tab has been removed from here.
            // Admitted patients are now in:  AdmittedPatientsResource  (/doctor/admitted-patients)
        ];
    }

    public function getDefaultActiveTab(): ?string
    {
        return 'opd';
    }
}