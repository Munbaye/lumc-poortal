<?php
namespace App\Filament\Tech\Resources;

use App\Models\ResultUpload;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResultUploadResource extends Resource
{
    protected static ?string $model = ResultUpload::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Upload Results';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('visit_id')
                ->label('Patient Visit')
                ->options(Visit::with('patient')
                    ->latest()
                    ->limit(100)
                    ->get()
                    ->mapWithKeys(fn($v) => [
                        $v->id => $v->patient->case_no . ' — ' . $v->patient->full_name
                            . ' (' . $v->registered_at->format('M d, Y') . ')'
                    ]))
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(fn($state, $set) =>
                    $set('patient_id', Visit::find($state)?->patient_id)),

            Forms\Components\Hidden::make('patient_id'),

            Forms\Components\Select::make('result_type')
                ->options([
                    'Laboratory' => 'Laboratory',
                    'Radiology'  => 'Radiology / Imaging',
                    'ECG'        => 'ECG / Cardiology',
                    'Other'      => 'Other',
                ])
                ->required(),

            Forms\Components\TextInput::make('test_name')
                ->label('Test / Exam Name')
                ->placeholder('e.g., Complete Blood Count, Chest X-Ray PA')
                ->required(),

            Forms\Components\FileUpload::make('file_path')
                ->label('Upload File')
                ->disk('public')
                ->directory('results')
                ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                ->maxSize(10240) // 10MB
                ->required()
                ->storeFileNamesIn('file_name'),

            Forms\Components\Textarea::make('notes')
                ->label('Technician Notes')
                ->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visit.patient.full_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('result_type')->badge(),
                Tables\Columns\TextColumn::make('test_name')->searchable(),
                Tables\Columns\TextColumn::make('uploader.name')->label('Uploaded By'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Date'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View File')
                    ->url(fn($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ResultUploadResource\Pages\ListResultUploads::route('/'),
            'create' => ResultUploadResource\Pages\CreateResultUpload::route('/create'),
        ];
    }
}