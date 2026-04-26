<?php

namespace App\Filament\Doctor\Resources;

use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NicuBabyResource extends Resource
{
    protected static ?string $model = Visit::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = '🍼 NICU Babies';
    protected static ?string $modelLabel = 'NICU Baby';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return Visit::query()
            ->select('visits.*')
            ->with(['patient', 'nicuAdmission', 'latestVitals'])
            ->where('visit_type', 'NICU')
            ->whereNotIn('status', ['discharged', 'referred', 'admitted'])
            ->latest('registered_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Simple case number column - shows temp ID or permanent case no
                Tables\Columns\TextColumn::make('case_number')
                    ->label('Case No')
                    ->getStateUsing(function ($record) {
                        if (!$record->patient) return '—';
                        return $record->patient->is_provisional 
                            ? ($record->patient->temporary_case_no ?? 'TEMP-' . $record->patient->id)
                            : ($record->patient->case_no ?? '—');
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', function (Builder $q) use ($search) {
                            $q->where('case_no', 'like', "%{$search}%")
                              ->orWhere('temporary_case_no', 'like', "%{$search}%");
                        });
                    })
                    ->copyable()
                    ->fontFamily('mono')
                    ->color(fn ($record) => $record->patient && $record->patient->is_provisional ? 'warning' : 'primary'),

                Tables\Columns\TextColumn::make('baby_name')
                    ->label('Baby Name')
                    ->getStateUsing(function ($record) {
                        if (!$record->patient) return '—';
                        return $record->patient->display_name ?? '—';
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', function (Builder $q) use ($search) {
                            $q->where('baby_family_name', 'like', "%{$search}%")
                              ->orWhere('baby_first_name', 'like', "%{$search}%")
                              ->orWhere('temporary_case_no', 'like', "%{$search}%")
                              ->orWhere('temporary_identifier', 'like', "%{$search}%");
                        });
                    })
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('mother_name')
                    ->label("Mother's Name")
                    ->getStateUsing(function ($record) {
                        if (!$record->patient) return '—';
                        return $record->patient->mother_full_name ?? $record->patient->mother_name ?? '—';
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('birth_datetime')
                    ->label('Birth Date/Time')
                    ->getStateUsing(function ($record) {
                        if ($record->nicuAdmission && $record->nicuAdmission->date_time_of_birth) {
                            return $record->nicuAdmission->date_time_of_birth->format('M d, Y H:i');
                        }
                        if ($record->patient && $record->patient->birth_datetime) {
                            return \Carbon\Carbon::parse($record->patient->birth_datetime)->format('M d, Y H:i');
                        }
                        return '—';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(function ($record) {
                        if (!$record->patient) return '—';
                        return $record->patient->newborn_age_display ?? $record->patient->age_display ?? '—';
                    }),

                Tables\Columns\TextColumn::make('birth_weight')
                    ->label('Birth Wt')
                    ->getStateUsing(function ($record) {
                        if ($record->nicuAdmission && $record->nicuAdmission->birth_weight_grams) {
                            return number_format($record->nicuAdmission->birth_weight_grams) . ' g';
                        }
                        return '—';
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        'provisional_registration' => '⚠️ Provisional',
                        'registered' => 'Registered',
                        'admitted' => 'Admitted',
                        default => ucfirst($record->status ?? '—'),
                    })
                    ->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'provisional_registration' => 'danger',
                        'registered' => 'warning',
                        'admitted' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('admitting_diagnosis')
                    ->label('Admitting Dx')
                    ->limit(30)
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'provisional_registration' => 'Provisional (Needs Registration)',
                        'registered' => 'Registered (Needs Assessment)',
                        'admitted' => 'Admitted',
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
                        \App\Filament\Doctor\Pages\NicuAssessment::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => in_array($record->status, ['provisional_registration', 'registered'])),

                Tables\Actions\Action::make('view_chart')
                    ->label('View Chart')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->button()
                    ->url(fn (Visit $record) =>
                        \App\Filament\Doctor\Pages\PatientChart::getUrl(['visitId' => $record->id])
                    )
                    ->visible(fn (Visit $record) => $record->status === 'admitted'),
            ]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Doctor\Resources\NicuBabyResource\Pages\ListNicuBabies::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = Visit::where('visit_type', 'NICU')
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