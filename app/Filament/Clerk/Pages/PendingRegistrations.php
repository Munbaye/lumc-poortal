<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendingRegistrations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Provisional Registrations';
    protected static ?string $title           = 'Provisional Registrations';
    protected static ?int    $navigationSort  = 3;
    protected static string  $view            = 'filament.clerk.pages.pending-registrations';

    public static function getNavigationBadge(): ?string
    {
        $count = Visit::whereHas('patient', fn ($q) => $q->where('is_provisional', true))->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::with(['patient'])
                    ->whereHas('patient', fn ($q) => $q->where('is_provisional', true))
                    ->latest('registered_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Date / Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->registered_at->diffForHumans()),

                Tables\Columns\TextColumn::make('temp_case_no')
                    ->label('Temp. Case No')
                    ->getStateUsing(fn ($record) =>
                        $record->patient?->temporary_case_no ?? 'TEMP-' . $record->patient?->id
                    )
                    ->fontFamily('mono')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('patient_identifier')
                    ->label('Patient / Identifier')
                    ->getStateUsing(fn ($record) =>
                        optional($record->patient)->display_name ?? '—'
                    )
                    ->weight('bold')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', fn (Builder $q) =>
                            $q->where('temporary_identifier', 'like', "%{$search}%")
                              ->orWhere('temporary_case_no', 'like', "%{$search}%")
                              ->orWhere('mother_family_name', 'like', "%{$search}%")
                              ->orWhere('baby_family_name', 'like', "%{$search}%")
                        );
                    }),

                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'ER'    => 'danger',
                        'NICU'  => 'success',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'provisional_registration' => 'Provisional',
                        'registered'               => 'Registered',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'provisional_registration' => 'danger',
                        'registered'               => 'warning',
                        default                    => 'gray',
                    }),
            ])
            ->defaultSort('registered_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->label('Type')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER', 'NICU' => 'NICU'])
                    ->placeholder('All Types'),

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
                            ->placeholder('mm/dd/yyyy')
                            ->native(false)
                            ->displayFormat('M d, Y'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until')
                            ->placeholder('mm/dd/yyyy')
                            ->native(false)
                            ->displayFormat('M d, Y'),
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

                Tables\Filters\Filter::make('registered_today')
                    ->label('Registered Today')
                    ->query(fn (Builder $q) => $q->whereDate('registered_at', today()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('review_and_convert')
                    ->label('Review & Convert')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->button()
                    ->url(fn (Visit $record) =>
                        ConvertToPermanent::getUrl(['visitId' => $record->id])
                    ),

                Tables\Actions\Action::make('patient_history')
                    ->label('History')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->button()
                    ->url(fn (Visit $record) =>
                        PatientHistory::getUrl(['patientId' => $record->patient_id])
                    )
                    ->visible(fn (Visit $record) => (bool) $record->patient_id),
            ]);
    }
}