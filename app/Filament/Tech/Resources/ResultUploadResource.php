<?php

namespace App\Filament\Tech\Resources;

use App\Models\ResultUpload;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ResultUploadResource extends Resource
{
    protected static ?string $model           = ResultUpload::class;
    protected static ?string $navigationIcon  = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Uploaded Results';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Select::make('visit_id')
                ->label('Patient Visit')
                ->searchable()
                ->required()
                ->reactive()
                ->getSearchResultsUsing(fn(string $search) =>
                    Visit::with('patient')
                        ->whereIn('status', ['vitals_done', 'assessed', 'admitted'])
                        ->whereHas('patient', fn($q) =>
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('family_name', 'like', "%{$search}%")
                              ->orWhere('case_no', 'like', "%{$search}%")
                        )
                        ->latest()
                        ->limit(20)
                        ->get()
                        ->filter(fn($v) => $v->patient !== null)
                        ->mapWithKeys(fn($v) => [
                            $v->id =>
                                $v->patient->case_no
                                . ' — '
                                . $v->patient->first_name . ' ' . $v->patient->family_name
                                . ' | ' . $v->visit_type
                                . ' (' . $v->registered_at->format('M d, Y') . ')',
                        ])
                )
                ->getOptionLabelUsing(fn($value) =>
                    optional(Visit::with('patient')->find($value))?->patient?->case_no
                    . ' — '
                    . optional(Visit::with('patient')->find($value))?->patient?->first_name
                    . ' ' . optional(Visit::with('patient')->find($value))?->patient?->family_name
                )
                ->afterStateUpdated(fn($state, $set) =>
                    $set('patient_id', Visit::find($state)?->patient_id)
                )
                ->helperText('Type patient name or case number to search.'),

            Forms\Components\Hidden::make('patient_id'),

            Forms\Components\Select::make('result_type')
                ->label('Result Type')
                ->options([
                    'Laboratory' => 'Laboratory',
                    'Radiology'  => 'Radiology / Imaging',
                    'ECG'        => 'ECG / Cardiology',
                    'Other'      => 'Other',
                ])
                ->required()
                ->reactive(),

            Forms\Components\TextInput::make('test_name')
                ->label('Test / Exam Name')
                ->placeholder('e.g., Complete Blood Count, Chest X-Ray PA')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('file_path')
                ->label('Upload Result File')
                ->disk('public')
                ->directory('results')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                ->maxSize(10240)
                ->required()
                ->storeFileNamesIn('file_name')
                ->helperText('Accepted formats: JPEG, PNG, PDF. Max size: 10MB.'),

            Forms\Components\Textarea::make('notes')
                ->label('Technician Notes')
                ->placeholder('Optional remarks about this result...')
                ->rows(2)
                ->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
                $query->with([
                    'visit.patient',
                    'uploader',
                    // 'amendments' removed — amended_from column not yet in DB
                    // restore once the amendment migration has been run
                ])
            )

            ->columns([
                Tables\Columns\TextColumn::make('visit.patient.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Click to copy Case Number'),

                Tables\Columns\TextColumn::make('visit.patient.family_name')
                    ->label('Patient')
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) =>
                        $record->visit?->patient
                            ? $record->visit->patient->first_name . ' ' . $record->visit->patient->family_name
                            : '—'
                    ),

                Tables\Columns\TextColumn::make('visit.visit_type')
                    ->label('Visit Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'ER'    => 'danger',
                        'OPD'   => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('result_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Laboratory' => 'success',
                        'Radiology'  => 'primary',
                        'ECG'        => 'warning',
                        'Other'      => 'secondary',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('test_name')
                    ->label('Test / Exam Name')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Uploaded By')
                    ->default('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded On')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])

            ->defaultSort('created_at', 'desc')

            ->filters([
                SelectFilter::make('result_type')
                    ->label('Result Type')
                    ->options([
                        'Laboratory' => 'Laboratory',
                        'Radiology'  => 'Radiology / Imaging',
                        'ECG'        => 'ECG / Cardiology',
                        'Other'      => 'Other',
                    ]),

                Filter::make('today')
                    ->label('Uploaded Today')
                    ->query(fn(Builder $query) =>
                        $query->whereDate('created_at', today())
                    ),

                Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn(Builder $query) =>
                        $query->whereBetween('created_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek(),
                        ])
                    ),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->label('Amend')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(true), // TODO: restore per-record check once amended_from migration is run

                Tables\Actions\Action::make('view_file')
                    ->label('View File')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->color('info'),
            ])

            ->emptyStateIcon('heroicon-o-arrow-up-tray')
            ->emptyStateHeading('No results uploaded yet')
            ->emptyStateDescription('Upload a lab or radiology result to get started.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Upload First Result')
                    ->url(fn() => static::getUrl('create'))
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ResultUploadResource\Pages\ListResultUploads::route('/'),
            'create' => ResultUploadResource\Pages\CreateResultUpload::route('/create'),
            'view'   => ResultUploadResource\Pages\ViewResultUpload::route('/{record}'),
            'edit'   => ResultUploadResource\Pages\EditResultUpload::route('/{record}/edit'),
        ];
    }
}