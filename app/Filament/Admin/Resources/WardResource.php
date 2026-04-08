<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WardResource\Pages;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WardResource extends Resource
{
    protected static ?string $model           = Ward::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Wards';
    protected static ?string $modelLabel      = 'Ward';
    protected static ?string $navigationGroup = 'Ward Management';
    protected static ?int    $navigationSort  = 10;

    // ── Form ─────────────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Ward Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Ward Name')
                        ->placeholder('e.g. Floor 14 Ward, REPSI Ward, Private Ward')
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('code')
                        ->label('Short Code')
                        ->placeholder('e.g. F14, REPSI, PVT')
                        ->nullable()
                        ->maxLength(20),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Inactive wards are hidden from nurses and staff.'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description / Notes')
                        ->nullable()
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(3),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ward Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Rooms')
                    ->counts('rooms')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('beds_count')
                    ->label('Total Beds')
                    ->counts('beds')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Notes')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->placeholder('All'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Ward $record) {
                        // Prevent deletion if beds are occupied
                        $occupied = $record->beds()->where('status', 'occupied')->count();
                        if ($occupied > 0) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Cannot Delete Ward')
                                ->body("This ward has {$occupied} occupied bed(s). Discharge patients first.")
                                ->persistent()
                                ->send();
                            $this->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateHeading('No Wards Yet')
            ->emptyStateDescription('Create your first ward to start managing rooms and beds.');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWards::route('/'),
            'create' => Pages\CreateWard::route('/create'),
            'edit'   => Pages\EditWard::route('/{record}/edit'),
        ];
    }
}