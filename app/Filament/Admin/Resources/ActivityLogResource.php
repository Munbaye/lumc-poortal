<?php
namespace App\Filament\Admin\Resources;

use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?string $pluralLabel = 'Activity Logs';
    protected static ?string $navigationGroup = 'Admin';

    // Activity logs are view-only
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ActivityLogResource\Pages\ListActivityLogs::route('/'),
        ];
    }

    // Table definition
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Record ID'),

                Tables\Columns\TextColumn::make('ip_address'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created_patient'  => 'Patient Created',
                        'updated_patient'  => 'Patient Updated',
                        'recorded_vitals'  => 'Vitals Recorded',
                        'assessed_patient' => 'Doctor Assessment',
                        'completed_order'  => 'Doctor Order Completed',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([]) // no row actions
            ->bulkActions([]); // no bulk actions
    }
}