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
     * Visibility rules:
     *   Charity  → all doctors can see
     *   Private  → only the assigned doctor
     */
    public static function getEloquentQuery(): Builder
    {
        $doctorId = auth()->id();

        return Visit::query()
            ->with(['patient', 'medicalHistory.doctor', 'nicuAdmission'])
            ->whereNotNull('doctor_admitted_at')
            ->where('status', 'admitted')
            ->where(function (Builder $q) use ($doctorId) {
                $q->where('payment_class', 'Charity')
                ->orWhereNull('payment_class')
                ->orWhere(function (Builder $q2) use ($doctorId) {
                    $q2->where('payment_class', 'Private')
                        ->where('assigned_doctor_id', $doctorId);
                });
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([])

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

                Tables\Columns\TextColumn::make('patient_full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn (Visit $record) => $record->patient?->full_name ?? '—')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('patient', function (Builder $q) use ($search) {
                            $q->where('case_no', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('family_name', 'like', "%{$search}%")
                            ->orWhereRaw(
                                "CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', family_name) LIKE ?",
                                ["%{$search}%"]
                            );
                        });
                    })
                    ->weight('semibold'),

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

                Tables\Columns\TextColumn::make('doctor_admitted_at')
                    ->label('Date Admitted')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->description(fn ($record) =>
                        $record->clerk_admitted_at
                            ? 'Clerk admitted ' . $record->clerk_admitted_at->timezone('Asia/Manila')->diffForHumans()
                            : '⏳ Pending clerk admission'
                    ),
                Tables\Columns\TextColumn::make('nicuAdmission.birth_weight_grams')
                    ->label('Birth Weight')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' g' : '—')
                    ->visible(fn ($record) => $record && $record->visit_type === 'NICU'),

                Tables\Columns\TextColumn::make('nicuAdmission.apgar_display')
                    ->label('APGAR')
                    ->visible(fn ($record) => $record && $record->visit_type === 'NICU'),
            ])

            ->emptyStateHeading(fn (\Livewire\Component $livewire) =>
                $livewire->viewFilter === 'all'
                    ? 'No patients found'
                    : 'No admitted patients'
            )

            ->defaultSort('doctor_admitted_at', 'asc')
            ->searchPlaceholder('Search by name or case no…')

            // Added SelectFilter to switch between "Admitted Only" and "All Patients"
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('viewFilter')
                    ->label('Show')
                    ->options([
                        'admitted' => '🏥 Admitted Only',
                        'all'      => '🗂️ All Patients',
                    ])
                    ->default('admitted')
                    ->query(fn (Builder $query, array $data) => $query), // query is handled in ListAdmittedPatients
            ])

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
            $count = Visit::whereNotNull('doctor_admitted_at')    // ← was clerk_admitted_at
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