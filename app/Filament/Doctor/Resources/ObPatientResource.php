<?php

namespace App\Filament\Doctor\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ObPatientResource extends Resource
{
    protected static ?string $model           = Visit::class;
    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'OB Patients';
    protected static ?string $modelLabel      = 'OB Patient';
    protected static ?string $navigationGroup = 'OB Care';
    protected static ?int    $navigationSort  = 3;

    public static function getEloquentQuery(): Builder
    {
        return Visit::query()
            ->select('visits.*')
            ->with(['patient', 'obRecord'])
            ->where('visit_type', 'OB')
            ->whereNotIn('status', ['discharged', 'referred'])
            ->latest('registered_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('case_number')
                    ->label('Case No')
                    ->getStateUsing(fn ($record) => $record->patient?->is_provisional
                        ? ($record->patient->temporary_case_no ?? 'TEMP-' . $record->patient->id)
                        : ($record->patient?->case_no ?? '—'))
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereHas('patient', fn ($p) =>
                            $p->where('case_no', 'like', "%{$s}%")
                              ->orWhere('temporary_case_no', 'like', "%{$s}%")
                        ))
                    ->copyable()
                    ->fontFamily('mono')
                    ->color(fn ($record) => $record->patient?->is_provisional ? 'warning' : 'primary'),

                Tables\Columns\TextColumn::make('patient_name')
                    ->label('Patient Name')
                    ->getStateUsing(fn ($record) => $record->patient?->display_name
                        ?? $record->patient?->full_name
                        ?? '—')
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereHas('patient', fn ($p) =>
                            $p->where('family_name', 'like', "%{$s}%")
                              ->orWhere('first_name', 'like', "%{$s}%")
                              ->orWhere('temporary_case_no', 'like', "%{$s}%")
                              ->orWhere('temporary_identifier', 'like', "%{$s}%")
                        ))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(fn ($record) => $record->patient?->age
                        ? $record->patient->age . ' y/o'
                        : '—'),

                Tables\Columns\TextColumn::make('gptal')
                    ->label('G/P')
                    ->getStateUsing(fn ($record) => $record->obRecord
                        ? 'G' . ($record->obRecord->gravida ?? '?') . 'P' . ($record->obRecord->para ?? '?')
                        : '—'),

                Tables\Columns\TextColumn::make('aog')
                    ->label('AOG')
                    ->getStateUsing(fn ($record) => $record->obRecord?->aog ?? '—'),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(35)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Arrived')
                    ->dateTime('M d, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        'provisional_registration' => 'Provisional',
                        'registered'               => 'Registered',
                        'admitted'                 => 'Admitted',
                        default                    => ucfirst($record->status ?? '—'),
                    })
                    ->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'provisional_registration' => 'danger',
                        'registered'               => 'warning',
                        'admitted'                 => 'success',
                        default                    => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'provisional_registration' => 'Provisional (Needs Assessment)',
                        'registered'               => 'Registered (Needs Assessment)',
                        'admitted'                 => 'Admitted',
                    ]),
                Tables\Filters\Filter::make('needs_assessment')
                    ->label('Needs Assessment')
                    ->query(fn (Builder $query) =>
                        $query->whereIn('status', ['provisional_registration', 'registered'])
                    ),
            ])
            ->defaultSort('registered_at', 'asc')
            ->actions([
                Tables\Actions\Action::make('assess')
                    ->label('Assess & Admit')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('primary')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Doctor\Pages\ObAssessment::getUrl(['visitId' => $record->id]))
                    ->visible(fn (Visit $record) =>
                        in_array($record->status, ['provisional_registration', 'registered'])),

                Tables\Actions\Action::make('view_chart')
                    ->label('View Chart')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Doctor\Pages\PatientChart::getUrl(['visitId' => $record->id]))
                    ->visible(fn (Visit $record) => $record->status === 'admitted'),
            ]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Doctor\Resources\ObPatientResource\Pages\ListObPatients::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = Visit::where('visit_type', 'OB')
                ->whereIn('status', ['provisional_registration', 'registered'])
                ->count();
            return $count > 0 ? (string) $count : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}