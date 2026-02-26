<?php
namespace App\Filament\Clerk\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisitResource extends Resource
{
    protected static ?string $model           = Visit::class;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = "Today's Patients";
    protected static ?int    $navigationSort  = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::with('patient')
                    ->whereDate('registered_at', today())
                    ->latest('registered_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Patient Name')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('patient.age_display')
                    ->label('Age'),

                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),

                Tables\Columns\TextColumn::make('payment_class')
                    ->label('Class')
                    ->formatStateUsing(fn ($state) => $state ?? 'Charity')
                    ->badge()
                    ->color(fn ($state) => $state === 'Private' ? 'gray' : 'success'),

                Tables\Columns\TextColumn::make('chief_complaint')
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->chief_complaint),

                Tables\Columns\TextColumn::make('status')
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
                        'discharged'  => 'gray',
                        'admitted'    => 'primary',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Time')
                    ->time('H:i')
                    ->sortable(),
            ])
            ->defaultSort('registered_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->label('Type')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER']),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'registered'  => 'Registered',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'discharged'  => 'Discharged',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('add_vitals')
                    ->label('Add Vitals')
                    ->icon('heroicon-o-heart')
                    ->color('warning')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Clerk\Pages\RecordVitals::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => $record->status === 'registered'),

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