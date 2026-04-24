<?php

namespace App\Filament\Nurse\Resources\NicuBabyResource\Pages;

use App\Filament\Nurse\Resources\NicuBabyResource;
use App\Filament\Nurse\Pages\CompleteBabyInformation;
use App\Filament\Nurse\Pages\NurseChart;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListNicuBabies extends ListRecords
{
    protected static string $resource = NicuBabyResource::class;
    
    protected static ?string $title = 'NICU Babies';
    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('temporary_case_no')
                    ->label('Temp ID')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('warning')
                    ->visible(fn ($record) => $record && $record->is_provisional),
                    
                Tables\Columns\TextColumn::make('case_no')
                    ->label('Case No')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('primary')
                    ->visible(fn ($record) => $record && !$record->is_provisional),
                    
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Baby Name')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('mother_full_name')
                    ->label("Mother's Name")
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('birth_datetime')
                    ->label('Birth Date/Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('newborn_age_display')
                    ->label('Age'),
                    
                Tables\Columns\IconColumn::make('is_provisional')
                    ->label('Status')
                    ->icon(fn ($state) => $state ? 'heroicon-o-clock' : 'heroicon-o-check-circle')
                    ->color(fn ($state) => $state ? 'warning' : 'success')
                    ->tooltip(fn ($record) => $record && $record->is_provisional 
                        ? 'Provisional - Needs Clerk Registration' 
                        : 'Permanent Record'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_provisional')
                    ->label('Show Provisional Only')
                    ->placeholder('All Babies')
                    ->trueLabel('Provisional Only')
                    ->falseLabel('Permanent Only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_provisional', true),
                        false: fn (Builder $query) => $query->where('is_provisional', false),
                    ),
                Tables\Filters\Filter::make('birth_datetime')
                    ->label('Birth Date Range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('birth_datetime', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('birth_datetime', '<=', $data['until']));
                    }),
            ])
            ->defaultSort('birth_datetime', 'desc')
            ->actions([
                Tables\Actions\Action::make('edit_info')
                    ->label('Edit Info')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn ($record) => 
                        CompleteBabyInformation::getUrl(['patientId' => $record->id])
                    )
                    ->visible(fn ($record) => $record && $record->is_provisional),
                    
                Tables\Actions\Action::make('view_chart')
                    ->label('View Chart')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => 
                        $record->latestVisit 
                            ? NurseChart::getUrl(['visitId' => $record->latestVisit->id])
                            : '#'
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}