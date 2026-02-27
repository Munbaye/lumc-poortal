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
            // Shows ALL visits — use date filter to narrow.
            // Default: today's visits newest-first.
            ->query(
                Visit::with(['patient', 'assignedDoctor'])
                    ->latest('registered_at')
            )
            ->columns([
                // ── Date / Time ─────────────────────────────────────────────
                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Date / Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->registered_at->diffForHumans()),

                // ── Patient identifiers ─────────────────────────────────────
                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('patient.family_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('patient.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('patient.age')
                    ->label('Age')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? $state . ' y/o' : '—')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('patient.sex')
                    ->label('Sex')
                    ->alignCenter(),

                // ── Visit details ───────────────────────────────────────────
                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Entry')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),

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
                        'referred'    => 'gray',
                        default       => 'gray',
                    }),
            ])
            ->defaultSort('registered_at', 'desc')
            ->filters([
                // Date range — defaults to today so the clerk sees today's list by default
                Tables\Filters\Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From')
                            ->default(today())
                            ->native(false),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until')
                            ->default(today())
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],  fn ($q, $d) => $q->whereDate('registered_at', '>=', $d))
                            ->when($data['until'], fn ($q, $d) => $q->whereDate('registered_at', '<=', $d));
                    })
                    ->indicateUsing(function (array $data): array {
                        $out = [];
                        if ($data['from'])  $out[] = 'From: '  . Carbon::parse($data['from'])->format('M d, Y');
                        if ($data['until']) $out[] = 'Until: ' . Carbon::parse($data['until'])->format('M d, Y');
                        return $out;
                    }),

                Tables\Filters\SelectFilter::make('visit_type')
                    ->label('Entry Point')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER']),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'registered'  => 'Registered',
                        'vitals_done' => 'Vitals Done',
                        'assessed'    => 'Assessed',
                        'discharged'  => 'Discharged',
                        'admitted'    => 'Admitted',
                        'referred'    => 'Referred',
                    ]),
            ])
            ->persistFiltersInSession() // remembers last filter settings per user
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