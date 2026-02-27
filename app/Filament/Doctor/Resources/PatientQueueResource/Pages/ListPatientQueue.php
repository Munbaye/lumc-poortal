<?php
namespace App\Filament\Doctor\Resources\PatientQueueResource\Pages;

use App\Filament\Doctor\Resources\PatientQueueResource;
use App\Models\Visit;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPatientQueue extends ListRecords
{
    protected static string $resource = PatientQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $doctorId = auth()->id();

        // Statuses that mean "still being processed" (not yet admitted/discharged)
        $pendingStatuses = ['registered', 'vitals_done', 'assessed'];

        return [

            // ── OPD Queue ─────────────────────────────────────────────────────
            // All OPD patients not yet admitted/discharged — visible to all doctors
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
            // All ER patients not yet admitted/discharged — visible to all doctors
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

            // ── Admitted (Inpatient) ──────────────────────────────────────────
            // Charity → all doctors see it
            // Private → only the assigned doctor sees it
            'admitted' => Tab::make('Admitted')
                ->icon('heroicon-o-building-office-2')
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('status', 'admitted')
                    ->where(function (Builder $q) use ($doctorId) {
                        $q->where('payment_class', 'Charity')
                          ->orWhere(function (Builder $q2) use ($doctorId) {
                              $q2->where('payment_class', 'Private')
                                 ->where('assigned_doctor_id', $doctorId);
                          })
                          // Also show admitted visits where payment_class hasn't been set yet
                          ->orWhereNull('payment_class');
                    })
                    ->orderBy('registered_at', 'asc')
                )
                ->badge(fn () =>
                    Visit::where('status', 'admitted')
                        ->where(function (Builder $q) use ($doctorId) {
                            $q->where('payment_class', 'Charity')
                              ->orWhere(function (Builder $q2) use ($doctorId) {
                                  $q2->where('payment_class', 'Private')
                                     ->where('assigned_doctor_id', $doctorId);
                              })
                              ->orWhereNull('payment_class');
                        })
                        ->count()
                ),

        ];
    }

    public function getDefaultActiveTab(): ?string
    {
        return 'opd';
    }
}