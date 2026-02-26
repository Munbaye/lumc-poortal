<?php
namespace App\Filament\Doctor\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords;

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
            ->columns([
                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('patient.age_display')
                    ->label('Age'),

                Tables\Columns\TextColumn::make('patient.sex')
                    ->label('Sex'),

                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(35),

                // Vitals summary with abnormality highlighting
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

                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'vitals_done' => 'Waiting',
                        'assessed'    => 'Assessed',
                        default       => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'vitals_done' => 'warning',
                        'assessed'    => 'success',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Time')
                    ->time('H:i'),
            ])
            ->defaultSort('registered_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER']),
            ])
            ->actions([
                Tables\Actions\Action::make('assess')
                    ->label('Assess')
                    ->icon('heroicon-o-clipboard-document')
                    ->url(fn ($record) =>
                        \App\Filament\Doctor\Pages\PatientAssessment::getUrl(['visitId' => $record->id])
                    ),
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