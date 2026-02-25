<?php
namespace App\Filament\Nurse\Resources;

use App\Models\NursesNote;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NursesNoteResource extends Resource
{
    protected static ?string $model = NursesNote::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = "Nurse's Notes";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('visit_id')
                ->label('Visit / Patient')
                ->options(Visit::with('patient')
                    ->where('status', 'assessed')
                    ->get()
                    ->mapWithKeys(fn($v) => [
                        $v->id => $v->patient->full_name . ' — ' . $v->registered_at->format('M d, Y')
                    ]))
                ->searchable()
                ->required(),
            Forms\Components\Textarea::make('note')
                ->label("Nurse's Note")
                ->rows(5)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visit.patient.full_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('note')->limit(80),
                Tables\Columns\TextColumn::make('nurse.name')->label('Nurse'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelationManagers(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => NursesNoteResource\Pages\ListNursesNotes::route('/'),
            'create' => NursesNoteResource\Pages\CreateNursesNote::route('/create'),
        ];
    }
}