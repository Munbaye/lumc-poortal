<?php

namespace App\Filament\Nurse\Resources;

use App\Models\Patient;
use App\Filament\Nurse\Pages\CompleteBabyInformation;
use App\Filament\Nurse\Pages\NurseChart;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NicuBabyResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'NICU Babies';
    protected static ?string $navigationGroup = 'NICU Care';
    protected static ?int $navigationSort = 3;
    
    public static function getEloquentQuery(): Builder
    {
        // Nurses can only see NICU babies (provisional or with NICU visits)
        return parent::getEloquentQuery()
            ->with(['latestVisit'])  // Eager load latestVisit
            ->where(function ($query) {
                $query->where('is_provisional', true)
                    ->orWhereHas('visits', function ($q) {
                        $q->where('visit_type', 'NICU');
                    });
            });
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('temporary_case_no')
                    ->label('Temp ID')
                    ->searchable()
                    ->visible(fn ($record) => $record->is_provisional),
                    
                Tables\Columns\TextColumn::make('case_no')
                    ->label('Case No')
                    ->searchable()
                    ->visible(fn ($record) => !$record->is_provisional),
                    
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Baby Name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('mother_full_name')
                    ->label('Mother\'s Name'),
                    
                Tables\Columns\TextColumn::make('birth_datetime')
                    ->label('Birth Date/Time')
                    ->dateTime('M d, Y H:i'),
                    
                Tables\Columns\IconColumn::make('is_provisional')
                    ->label('Status')
                    ->icon(fn ($state) => $state ? 'heroicon-o-clock' : 'heroicon-o-check-circle')
                    ->color(fn ($state) => $state ? 'warning' : 'success')
                    ->tooltip(fn ($record) => $record->is_provisional 
                        ? 'Provisional - Needs Clerk Registration' 
                        : 'Permanent Record'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_provisional')
                    ->label('Show Provisional Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit_info')
                    ->label('Edit Info')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record) => 
                        CompleteBabyInformation::getUrl(['patientId' => $record->id])
                    )
                    ->visible(fn ($record) => $record->is_provisional),
                    
                Tables\Actions\Action::make('view_chart')
                    ->label('View Chart')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => 
                        $record->latestVisit 
                            ? NurseChart::getUrl(['visitId' => $record->latestVisit->id])
                            : '#'
                    ),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => NicuBabyResource\Pages\ListNicuBabies::route('/'),
        ];
    }
    
    public static function canCreate(): bool { return false; }
}