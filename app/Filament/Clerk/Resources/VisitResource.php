<?php

namespace App\Filament\Clerk\Resources;

use App\Models\Visit;
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
                    ->whereHas('patient', fn ($q) => $q->where('is_provisional', false))
                    ->latest('registered_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Date / Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->registered_at->diffForHumans()),

                Tables\Columns\TextColumn::make('case_number')
                    ->label('Case No')
                    ->getStateUsing(fn ($record) => $record->patient?->case_no ?? '—')
                    ->copyable()
                    ->fontFamily('mono')
                    ->badge()
                    ->color('primary')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', fn (Builder $q) =>
                            $q->where('case_no', 'like', "%{$search}%")
                        );
                    }),

                Tables\Columns\TextColumn::make('patient_display_name')
                    ->label('Patient Name')
                    ->getStateUsing(fn ($record) => optional($record->patient)->full_name ?? '—')
                    ->weight('bold')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', fn (Builder $q) =>
                            $q->where('family_name', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                        );
                    }),

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
                        'ER'    => 'danger',
                        'NICU'  => 'success',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(28)
                    ->tooltip(fn ($record) => $record->chief_complaint),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'registered'  => 'Registered',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'discharged'  => 'Discharged',
                        'admitted'    => 'Admitted',
                        'referred'    => 'Referred',
                        default       => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'registered'  => 'warning',
                        'vitals_done' => 'info',
                        'assessed'    => 'success',
                        'admitted'    => 'primary',
                        'discharged'  => 'gray',
                        default       => 'gray',
                    }),
            ])
            ->defaultSort('registered_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->label('Entry Point')
                    ->options([
                        'OPD'  => 'OPD',
                        'ER'   => 'ER',
                        'NICU' => 'NICU',
                    ])
                    ->placeholder('All Entry Points')
                    ->visible(fn ($livewire) => ($livewire->activeTab ?? 'all') === 'all'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'registered'  => 'Registered',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'admitted'    => 'Admitted',
                        'discharged'  => 'Discharged',
                        'referred'    => 'Referred',
                    ])
                    ->placeholder('All Statuses'),

                Tables\Filters\SelectFilter::make('sex')
                    ->label('Sex')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->placeholder('All')
                    ->query(fn (Builder $query, array $data) =>
                        filled($data['value'])
                            ? $query->whereHas('patient', fn ($q) => $q->where('sex', $data['value']))
                            : $query
                    ),

                Tables\Filters\Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From')
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->placeholder('Start date'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until')
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->placeholder('End date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'],  fn ($q) => $q->whereDate('registered_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('registered_at', '<=', $data['until']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'])  $indicators[] = 'From: '  . \Carbon\Carbon::parse($data['from'])->format('M d, Y');
                        if ($data['until']) $indicators[] = 'Until: ' . \Carbon\Carbon::parse($data['until'])->format('M d, Y');
                        return $indicators;
                    }),
            ])
            ->persistFiltersInSession()
            ->actions([
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
                    ->label('History')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Clerk\Pages\PatientHistory::getUrl([
                            'patientId' => $record->patient_id,
                        ])
                    )
                    ->visible(fn (Visit $record) => (bool) $record->patient_id),

                Tables\Actions\ViewAction::make(),
            ]);
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