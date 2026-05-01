<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BedResource\Pages;
use App\Models\Bed;
use App\Models\Room;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BedResource extends Resource
{
    protected static ?string $model           = Bed::class;
    protected static ?string $navigationIcon  = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Beds';
    protected static ?string $modelLabel      = 'Bed';
    protected static ?string $navigationGroup = 'Ward Management';
    protected static ?int    $navigationSort  = 12;

    // ── Form (Admin can create/edit beds manually if needed) ──────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Bed Details')
                ->schema([
                    Forms\Components\Select::make('ward_id')
                        ->label('Ward')
                        ->options(Ward::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('room_id', null)),

                    Forms\Components\Select::make('room_id')
                        ->label('Room')
                        ->options(fn (Forms\Get $get) =>
                            Room::where('ward_id', $get('ward_id'))
                                ->where('is_active', true)
                                ->where('is_under_maintenance', false)
                                ->orderBy('room_number')
                                ->pluck('room_number', 'id')
                        )
                        ->required()
                        ->searchable()
                        ->preload()
                        ->disabled(fn (Forms\Get $get) => ! $get('ward_id')),

                    Forms\Components\TextInput::make('bed_label')
                        ->label('Bed Label')
                        ->placeholder('e.g. Bed A, Bed 1, Aisle Bed 3')
                        ->required()
                        ->maxLength(50),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(Bed::STATUSES)
                        ->default('available')
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ward.name')
                    ->label('Ward')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('room.classification')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => Room::CLASSIFICATIONS[$state] ?? ucfirst($state))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'service'  => 'info',
                        'pay_ward' => 'warning',
                        'private'  => 'success',
                        'aisle'    => 'gray',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('bed_label')
                    ->label('Bed')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Bed::STATUSES[$state] ?? ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'available'   => 'success',
                        'occupied'    => 'danger',
                        'maintenance' => 'warning',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('visit.patient.full_name')
                    ->label('Occupied By')
                    ->placeholder('—')
                    ->searchable(['visits.patient.family_name', 'visits.patient.first_name']),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->defaultSort('ward_id', 'asc')
            ->groups([
                Tables\Grouping\Group::make('ward.name')
                    ->label('Ward')
                    ->collapsible(),
                Tables\Grouping\Group::make('room.room_number')
                    ->label('Room')
                    ->collapsible(),
            ])
            ->defaultGroup('ward.name')
            ->filters([
                Tables\Filters\SelectFilter::make('ward_id')
                    ->label('Ward')
                    ->options(Ward::orderBy('name')->pluck('name', 'id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Bed::STATUSES),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-inbox-stack')
            ->emptyStateHeading('No Beds Yet')
            ->emptyStateDescription('Beds are added by nurses in each room. Private rooms auto-generate 1 bed.');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBeds::route('/'),
            'create' => Pages\CreateBed::route('/create'),
            'edit'   => Pages\EditBed::route('/{record}/edit'),
        ];
    }
}