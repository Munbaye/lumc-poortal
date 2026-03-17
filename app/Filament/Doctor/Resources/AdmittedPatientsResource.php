<?php

namespace App\Filament\Doctor\Resources;

use App\Filament\Doctor\Pages\PatientChart;
use App\Models\Visit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdmittedPatientsResource extends Resource
{
    protected static ?string $model           = Visit::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Admitted Patients';
    protected static ?string $modelLabel      = 'Admitted Patient';
    protected static ?int    $navigationSort  = 2;

    /**
     * Base query: only visits where the clerk has completed admission
     * (clerk_admitted_at IS NOT NULL → visit is fully admitted).
     *
     * Visibility rules:
     *   Charity  → all doctors can see
     *   Private  → only the assigned doctor
     */
    public static function getEloquentQuery(): Builder
    {
        $doctorId = auth()->id();

        return Visit::query()
            ->with(['patient', 'medicalHistory.doctor'])
            ->whereNotNull('clerk_admitted_at')           // fully admitted
            ->where('status', 'admitted')
            ->where(function (Builder $q) use ($doctorId) {
                $q->where('payment_class', 'Charity')
                  ->orWhereNull('payment_class')          // safety net
                  ->orWhere(function (Builder $q2) use ($doctorId) {
                      $q2->where('payment_class', 'Private')
                         ->where('assigned_doctor_id', $doctorId);
                  });
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([

                // Row number (visual only — not a DB column)
                Tables\Columns\TextColumn::make('row_no')
                    ->label('No.')
                    ->rowIndex()
                    ->width(50),

                Tables\Columns\TextColumn::make('patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->weight('semibold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('patient.age_display')
                    ->label('Age'),

                Tables\Columns\TextColumn::make('patient.sex')
                    ->label('Sex'),

                Tables\Columns\TextColumn::make('admitting_diagnosis')
                    ->label('Admitting Diagnosis')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->admitting_diagnosis)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('admitted_service')
                    ->label('Service')
                    ->badge()
                    ->color('success')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('payment_class')
                    ->label('Class')
                    ->badge()
                    ->color(fn ($state) => $state === 'Private' ? 'gray' : 'success'),

                Tables\Columns\TextColumn::make('clerk_admitted_at')
                    ->label('Date Admitted')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->description(fn ($record) =>
                        $record->clerk_admitted_at
                            ? $record->clerk_admitted_at->timezone('Asia/Manila')->diffForHumans()
                            : null
                    ),

            ])
            ->defaultSort('clerk_admitted_at', 'asc')
            ->searchPlaceholder('Search by name or case no…')
            ->actions([
                Tables\Actions\Action::make('open_chart')
                    ->label('Open Chart')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->button()
                    ->url(fn (Visit $record) =>
                        PatientChart::getUrl(['visitId' => $record->id])
                    ),
            ])
            ->recordUrl(fn (Visit $record) =>
                PatientChart::getUrl(['visitId' => $record->id])
            );
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Doctor\Resources\AdmittedPatientsResource\Pages\ListAdmittedPatients::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $doctorId = auth()->id();
            $count = Visit::whereNotNull('clerk_admitted_at')
                ->where('status', 'admitted')
                ->where(function (Builder $q) use ($doctorId) {
                    $q->where('payment_class', 'Charity')
                      ->orWhereNull('payment_class')
                      ->orWhere(function (Builder $q2) use ($doctorId) {
                          $q2->where('payment_class', 'Private')
                             ->where('assigned_doctor_id', $doctorId);
                      });
                })
                ->count();
            return $count > 0 ? (string) $count : null;
        } catch (\Throwable) {
            return null;
        }
    }
}