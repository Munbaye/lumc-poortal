<?php

namespace App\Filament\Nurse\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Models\DoctorsOrder;

class DoctorsOrderResource extends Resource
{
    protected static ?string $model = DoctorsOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visit.patient.full_name')
                    ->label('Patient'),
                TextColumn::make('order_text')
                    ->label('Order')
                    ->limit(80),
                IconColumn::make('is_completed')
                    ->boolean()
                    ->label('Done'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Ordered'),
            ])
            ->filters([
                Filter::make('pending')
                    ->query(fn($query) => $query->where('is_completed', false))
                    ->default(),
            ])
            ->actions([
                Action::make('complete')
                    ->label('Mark Done')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (DoctorsOrder $record) {
                        $record->update([
                            'is_completed' => true,
                            'completed_by' => auth()->id(),
                            'completed_at' => now(),
                        ]);
                    })
                    ->visible(fn($record) => !$record->is_completed),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => DoctorsOrderResource\Pages\ListDoctorsOrders::route('/'),
        ];
    }
}