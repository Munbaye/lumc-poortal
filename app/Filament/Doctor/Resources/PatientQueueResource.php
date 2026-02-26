<?php
namespace App\Filament\Doctor\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PatientQueueResource extends Resource
{
    protected static ?string $model          = Visit::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Patient Queue';
    protected static ?string $modelLabel     = 'Patient';
    protected static ?int    $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::with('patient', 'latestVitals')
                    ->whereDate('registered_at', today())
                    ->whereIn('status', ['vitals_done', 'assessed'])
                    ->orderBy('registered_at', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('patient.age_display')
                    ->label('Age'),
                Tables\Columns\TextColumn::make('patient.sex')
                    ->label('Sex'),
                Tables\Columns\TextColumn::make('visit_type')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),
                Tables\Columns\TextColumn::make('chief_complaint')
                    ->limit(40)
                    ->label('Chief Complaint'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'vitals_done' => 'warning',
                        'assessed'    => 'success',
                        default       => 'gray',
                    }),
                Tables\Columns\TextColumn::make('registered_at')
                    ->time('H:i')
                    ->label('Time'),
            ])
            ->defaultSort('registered_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER']),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'vitals_done' => 'Waiting for Doctor',
                        'assessed'    => 'Assessed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('assess')
                    ->label('Assess Patient')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('teal')
                    ->url(fn ($record) => \App\Filament\Doctor\Pages\PatientAssessment::getUrl(['visitId' => $record->id])),
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