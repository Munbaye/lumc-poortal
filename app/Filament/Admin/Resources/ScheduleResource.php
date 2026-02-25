<?php
namespace App\Filament\Admin\Resources;

use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    // Form for creating/editing schedules
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Staff Member')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('shift')
                ->options([
                    'Morning'   => 'Morning (7am-3pm)',
                    'Afternoon' => 'Afternoon (3pm-11pm)',
                    'Night'     => 'Night (11pm-7am)',
                    'Duty'      => '24-hour Duty',
                ])
                ->required(),

            Forms\Components\DatePicker::make('schedule_date')
                ->required(),

            Forms\Components\TimePicker::make('start_time')
                ->seconds(false)
                ->required(),

            Forms\Components\TimePicker::make('end_time')
                ->seconds(false)
                ->required(),

            Forms\Components\Select::make('department')
                ->options([
                    'OPD'          => 'OPD',
                    'ER'           => 'Emergency Room',
                    'Medical Ward' => 'Medical Ward',
                    'Pedia Ward'   => 'Pedia Ward',
                    'OB Ward'      => 'OB Ward',
                    'ICU'          => 'ICU',
                ]),

            Forms\Components\Textarea::make('notes')
                ->rows(2),
        ]);
    }

    // Table to display schedules
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Staff')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shift')
                    ->badge(),
                Tables\Columns\TextColumn::make('schedule_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time(),
                Tables\Columns\TextColumn::make('department'),
            ])
            ->defaultSort('schedule_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('shift')
                    ->options([
                        'Morning'   => 'Morning',
                        'Afternoon' => 'Afternoon',
                        'Night'     => 'Night',
                        'Duty'      => '24-hour Duty',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->options([
                        'OPD'          => 'OPD',
                        'ER'           => 'Emergency Room',
                        'Medical Ward' => 'Medical Ward',
                        'Pedia Ward'   => 'Pedia Ward',
                        'OB Ward'      => 'OB Ward',
                        'ICU'          => 'ICU',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ScheduleResource\Pages\ListSchedules::route('/'),
            'create' => ScheduleResource\Pages\CreateSchedule::route('/create'),
            'edit'   => ScheduleResource\Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}