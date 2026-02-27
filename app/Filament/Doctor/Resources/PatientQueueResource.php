<?php
namespace App\Filament\Doctor\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientQueueResource extends Resource
{
    protected static ?string $model           = Visit::class;
    protected static ?string $navigationIcon  = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Patient Queue';
    protected static ?string $modelLabel      = 'Patient';
    protected static ?int    $navigationSort  = 1;

    public static function table(Table $table): Table
    {
        return $table
            // Base query — tabs (modifyQueryUsing) refine this further
            ->query(
                Visit::with(['patient', 'latestVitals', 'assignedDoctor'])
                    ->latest('registered_at')
            )
            ->columns([

                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('patient.age_display')
                    ->label('Age'),

                Tables\Columns\TextColumn::make('patient.sex')
                    ->label('Sex'),

                // Entry point badge (OPD / ER)
                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Entry')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),

                // Payment class — NULL means not yet admitted (shows "—")
                Tables\Columns\TextColumn::make('payment_class')
                    ->label('Class')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->color(fn ($state) => match ($state) {
                        'Charity' => 'success',
                        'Private' => 'gray',
                        default   => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->chief_complaint),

                // ── Vitals summary ───────────────────────────────────────────
                Tables\Columns\TextColumn::make('latestVitals.temperature')
                    ->label('Temp')
                    ->formatStateUsing(fn ($state) => $state ? $state . '°C' : '—')
                    ->color(fn ($state) => ($state && ($state < 36.0 || $state > 37.5)) ? 'danger' : null),

                Tables\Columns\TextColumn::make('latestVitals.pulse_rate')
                    ->label('PR')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : '—')
                    ->color(fn ($state) => ($state && ($state < 60 || $state > 100)) ? 'danger' : null),

                Tables\Columns\TextColumn::make('latestVitals.respiratory_rate')
                    ->label('RR')
                    ->formatStateUsing(fn ($state) => $state ? $state . '/min' : '—')
                    ->color(fn ($state) => ($state && ($state < 12 || $state > 20)) ? 'danger' : null),

                Tables\Columns\TextColumn::make('latestVitals.o2_saturation')
                    ->label('O₂')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '—')
                    ->color(fn ($state) => ($state && $state < 95) ? 'danger' : null),

                // ── Status ───────────────────────────────────────────────────
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'registered'  => 'Waiting',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'admitted'    => 'Admitted',
                        'discharged'  => 'Discharged',
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
                        'referred'    => 'gray',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Time')
                    ->time('H:i')
                    ->sortable(),

            ])
            ->defaultSort('registered_at', 'asc')
            ->filters([
                // Quick filter for when viewing across multiple tabs
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'registered'  => 'Registered',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'admitted'    => 'Admitted',
                        'discharged'  => 'Discharged',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('assess')
                    ->label(fn (Visit $record) => $record->status === 'admitted' ? 'Update' : 'Assess')
                    ->icon('heroicon-o-clipboard-document')
                    ->color(fn (Visit $record) => $record->status === 'admitted' ? 'gray' : 'primary')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Doctor\Pages\PatientAssessment::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => !in_array($record->status, ['discharged', 'referred'])),
            ]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => PatientQueueResource\Pages\ListPatientQueue::route('/'),
        ];
    }
}