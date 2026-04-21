<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoomResource\Pages;
use App\Models\Room;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class RoomResource extends Resource
{
    protected static ?string $model           = Room::class;
    protected static ?string $navigationIcon  = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Rooms';
    protected static ?string $modelLabel      = 'Room';
    protected static ?string $navigationGroup = 'Ward Management';
    protected static ?int    $navigationSort  = 11;

    // ── Form ─────────────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Room Information')
                ->schema([
                    Forms\Components\Select::make('ward_id')
                        ->label('Ward')
                        ->options(
                            Ward::where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                        )
                        ->required()
                        ->searchable()
                        ->preload()
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('room_number')
                        ->label('Room Number / Label')
                        ->placeholder('e.g. 1436, REPSI 2805, Aisle 1')
                        ->required()
                        ->maxLength(50),

                    Forms\Components\Select::make('classification')
                        ->label('Room Classification')
                        ->options(Room::CLASSIFICATIONS)
                        ->required()
                        ->default('service')
                        ->live()
                        ->helperText('Private rooms are auto-assigned 1 bed. Aisle rooms support flexible bed counts.'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(3),

            Forms\Components\Section::make('Capacity')
                ->schema([
                    Forms\Components\TextInput::make('bed_capacity')
                        ->label('Bed Capacity')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(30)
                        ->default(1)
                        ->helperText('For Service and Pay Ward rooms. Nurses will add individual beds up to this limit.')
                        ->visible(fn (Get $get) =>
                            ! in_array($get('classification'), ['private', 'aisle', ''])
                        ),

                    Forms\Components\Placeholder::make('private_bed_note')
                        ->label('Bed Capacity')
                        ->content('Private rooms always have exactly 1 bed. This is set automatically.')
                        ->visible(fn (Get $get) => $get('classification') === 'private'),

                    Forms\Components\TextInput::make('bed_capacity')
                        ->label('Aisle Bed Capacity')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(50)
                        ->default(5)
                        ->helperText('Max number of aisle beds nurses can add in this aisle section.')
                        ->visible(fn (Get $get) => $get('classification') === 'aisle'),
                ])
                ->columns(1),

            Forms\Components\Section::make('Maintenance')
                ->schema([
                    Forms\Components\Toggle::make('is_under_maintenance')
                        ->label('Under Maintenance')
                        ->helperText('When ON, this room will be flagged in the Nurse panel and no beds can be assigned.')
                        ->live(),

                    Forms\Components\Textarea::make('maintenance_notes')
                        ->label('Maintenance Notes / Reason')
                        ->placeholder('e.g. Plumbing repair, Electrical work, Deep cleaning...')
                        ->rows(2)
                        ->nullable()
                        ->visible(fn (Get $get) => (bool) $get('is_under_maintenance')),
                ])
                ->columns(1),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ward.name')
                    ->label('Ward')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Room')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('classification')
                    ->label('Classification')
                    ->formatStateUsing(fn ($state) => Room::CLASSIFICATIONS[$state] ?? ucfirst($state))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'service'  => 'info',
                        'pay_ward' => 'warning',
                        'private'  => 'success',
                        'aisle'    => 'gray',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('bed_capacity')
                    ->label('Capacity')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('beds_count')
                    ->label('Beds Added')
                    ->counts('beds')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_under_maintenance')
                    ->label('Maintenance')
                    ->boolean()
                    ->trueIcon('heroicon-o-wrench-screwdriver')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

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
            ])
            ->defaultGroup('ward.name')
            ->filters([
                Tables\Filters\SelectFilter::make('ward_id')
                    ->label('Ward')
                    ->options(
                        Ward::orderBy('name')->pluck('name', 'id')
                    )
                    ->searchable(),

                Tables\Filters\SelectFilter::make('classification')
                    ->label('Classification')
                    ->options(Room::CLASSIFICATIONS),

                Tables\Filters\TernaryFilter::make('is_under_maintenance')
                    ->label('Maintenance')
                    ->trueLabel('Under Maintenance')
                    ->falseLabel('Operational')
                    ->placeholder('All'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->placeholder('All'),
            ])
            ->actions([
                // Quick toggle maintenance from the table
                Tables\Actions\Action::make('toggle_maintenance')
                    ->label(fn (Room $record) =>
                        $record->is_under_maintenance ? 'Mark Operational' : 'Set Maintenance'
                    )
                    ->icon(fn (Room $record) =>
                        $record->is_under_maintenance
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-wrench-screwdriver'
                    )
                    ->color(fn (Room $record) =>
                        $record->is_under_maintenance ? 'success' : 'danger'
                    )
                    ->requiresConfirmation()
                    ->modalHeading(fn (Room $record) =>
                        $record->is_under_maintenance
                            ? 'Mark Room as Operational?'
                            : 'Set Room Under Maintenance?'
                    )
                    ->modalDescription(fn (Room $record) =>
                        $record->is_under_maintenance
                            ? 'Nurses will be able to assign beds in this room again.'
                            : 'Nurses will see this room as unavailable. Existing beds will be flagged.'
                    )
                    ->form(fn (Room $record) => $record->is_under_maintenance ? [] : [
                        Forms\Components\Textarea::make('maintenance_notes')
                            ->label('Reason for Maintenance')
                            ->placeholder('e.g. Plumbing repair, electrical work...')
                            ->rows(2),
                    ])
                    ->action(function (Room $record, array $data) {
                        $record->update([
                            'is_under_maintenance' => ! $record->is_under_maintenance,
                            'maintenance_notes'    => $record->is_under_maintenance
                                ? null
                                : ($data['maintenance_notes'] ?? null),
                        ]);

                        Notification::make()
                            ->title($record->is_under_maintenance
                                ? "Room {$record->room_number} marked as Operational"
                                : "Room {$record->room_number} set Under Maintenance"
                            )
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-home-modern')
            ->emptyStateHeading('No Rooms Yet')
            ->emptyStateDescription('Add rooms to your wards. Start by creating a ward first.');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit'   => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}