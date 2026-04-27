<?php

namespace App\Filament\Clerk\Resources;

use App\Models\Visit;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitResource extends Resource
{
    protected static ?string $model           = Visit::class;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Patient Visits';
    protected static ?int    $navigationSort  = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::with(['patient'])
                    ->latest('registered_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Date / Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->registered_at->diffForHumans()),

                // Single Case No column with badge for provisional
                Tables\Columns\TextColumn::make('case_number')
                    ->label('Case No')
                    ->getStateUsing(function ($record) {
                        $patient = $record->patient;
                        if (!$patient) return '—';
                        
                        if ($patient->is_provisional) {
                            return $patient->temporary_case_no ?? 'TEMP-' . $patient->id;
                        }
                        return $patient->case_no ?? '—';
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', function (Builder $q) use ($search) {
                            $q->where('case_no', 'like', "%{$search}%")
                              ->orWhere('temporary_case_no', 'like', "%{$search}%");
                        });
                    })
                    ->copyable()
                    ->fontFamily('mono')
                    ->badge(fn ($record) => $record->patient && $record->patient->is_provisional)
                    ->color(fn ($record) => $record->patient && $record->patient->is_provisional ? 'warning' : 'primary')
                    ->icon(fn ($record) => $record->patient && $record->patient->is_provisional ? 'heroicon-o-clock' : null)
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->patient && $record->patient->is_provisional 
                            ? $state . ' (Temporary)'
                            : $state
                    ),

                Tables\Columns\TextColumn::make('patient_display_name')
                    ->label('Patient Name')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', function (Builder $q) use ($search) {
                            $q->where('family_name', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                              ->orWhere('temporary_case_no', 'like', "%{$search}%")
                              ->orWhere('temporary_identifier', 'like', "%{$search}%");
                        });
                    })
                    ->getStateUsing(fn ($record) => optional($record->patient)->display_name ?? '—')
                    ->weight('bold')
                    ->color(fn ($record) => optional($record->patient)->has_incomplete_info ? 'danger' : null),

                // Separate column for the (Temporary) badge? No, integrated above

                Tables\Columns\TextColumn::make('patient_age')
                    ->label('Age')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => optional($record->patient)->age_display ?? '—'),

                Tables\Columns\TextColumn::make('patient_sex')
                    ->label('Sex')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => optional($record->patient)->sex ?? '—'),

                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Entry')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'ER'   => 'danger',
                        'NICU' => 'success',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(28)
                    ->tooltip(fn ($record) => $record->chief_complaint),

                Tables\Columns\TextColumn::make('status')
                    ->label('Visit Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'registered'              => 'Registered',
                        'vitals_done'             => 'Vitals Done',
                        'assessed'                => 'Assessed',
                        'discharged'              => 'Discharged',
                        'admitted'                => 'Admitted',
                        'referred'                => 'Referred',
                        'provisional_registration' => 'Provisional Reg',
                        default                    => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'registered'              => 'warning',
                        'vitals_done'             => 'info',
                        'assessed'                => 'success',
                        'admitted'                => 'primary',
                        'provisional_registration' => 'danger',
                        default                   => 'gray',
                    }),
            ])
            ->defaultSort('registered_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('record_type')
                    ->label('Record Type')
                    ->options([
                        'all' => 'All Records',
                        'permanent' => 'Permanent Records Only',
                        'provisional' => '⚠️ Provisional Records (Need Conversion)',
                    ])
                    ->default('all')
                    ->query(function (Builder $query, array $data) {
                        if (($data['value'] ?? 'all') === 'provisional') {
                            $query->whereHas('patient', fn($q) => $q->where('is_provisional', true));
                        } elseif (($data['value'] ?? 'all') === 'permanent') {
                            $query->whereHas('patient', fn($q) => $q->where('is_provisional', false));
                        }
                        return $query;
                    }),

                Tables\Filters\SelectFilter::make('visit_type')
                    ->label('Entry Point')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER', 'NICU' => 'NICU']),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'registered'               => 'Registered',
                        'vitals_done'              => 'Vitals Done',
                        'assessed'                 => 'Assessed',
                        'discharged'               => 'Discharged',
                        'admitted'                 => 'Admitted',
                        'referred'                 => 'Referred',
                        'provisional_registration' => 'Provisional Registration',
                    ]),
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\Action::make('review_and_convert')
                    ->label('Review & Convert')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->button()
                    ->url(fn (Visit $record) => 
                        \App\Filament\Clerk\Pages\ConvertToPermanent::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => $record->patient && $record->patient->is_provisional),

                Tables\Actions\Action::make('add_vitals')
                    ->label('Add Vitals')
                    ->icon('heroicon-o-plus')
                    ->color('warning')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Clerk\Pages\RecordVitals::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => $record->status === 'registered'),

                Tables\Actions\Action::make('patient_history')
                    ->label('Patient History')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Clerk\Pages\PatientHistory::getUrl([
                            'patientId' => $record->patient_id,
                        ])
                    )
                    ->visible(fn (Visit $record) => $record->patient_id),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getTabs(): array
    {
        return [
            'all' => Tables\Contracts\HasTabs\Tab::make('All Visits')
                ->icon('heroicon-o-clipboard-document-list')
                ->badge(Visit::count())
                ->badgeColor('gray'),
                
            'provisional' => Tables\Contracts\HasTabs\Tab::make('⚠️ Provisional (Need Conversion)')
                ->icon('heroicon-o-bell-alert')
                ->badge(Visit::whereHas('patient', fn($q) => $q->where('is_provisional', true))->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('patient', fn($q) => $q->where('is_provisional', true))
                ),
                
            'nicu_admitted' => Tables\Contracts\HasTabs\Tab::make('NICU - Admitted')
                ->icon('heroicon-o-heart')
                ->badge(Visit::where('admitted_service', 'NICU')
                    ->whereHas('patient', fn($q) => $q->where('is_provisional', false))
                    ->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('admitted_service', 'NICU')
                        ->whereHas('patient', fn($q) => $q->where('is_provisional', false))
                ),
                
            'opd_er' => Tables\Contracts\HasTabs\Tab::make('OPD / ER')
                ->icon('heroicon-o-building-office')
                ->badge(Visit::whereIn('visit_type', ['OPD', 'ER'])->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereIn('visit_type', ['OPD', 'ER'])
                ),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Visit::whereHas('patient', fn($q) => $q->where('is_provisional', true))->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = Visit::whereHas('patient', fn($q) => $q->where('is_provisional', true))->count();
        return $count > 0 ? 'danger' : 'success';
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => VisitResource\Pages\ListVisits::route('/'),
            'view'  => VisitResource\Pages\ViewVisit::route('/{record}'),
        ];
    }
}