<?php
namespace App\Filament\Admin\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'User Accounts';
    protected static ?int $navigationSort = 1;

    public static function departmentOptions(string $panel): array
    {
        $wardOptions = [
            'Internal Medicine' => 'Internal Medicine',
            'Pediatrics'        => 'Pediatrics',
            'Surgery'           => 'Surgery',
            'OB-Gyne'           => 'OB-Gyne',
            'Emergency'         => 'Emergency',
            'NICU'              => 'NICU',
            'ICU'               => 'ICU',
        ];

        if ($panel === 'doctor' || $panel === 'nurse') return $wardOptions;

        if ($panel === 'clerk') return ['OPD' => 'OPD', 'ER' => 'ER'];

        if ($panel === 'tech') return [
            'RAD'  => 'Radiology Tech',
            'MED'  => 'Medical Tech',
            'TECH' => 'General Tech',
        ];

        return [];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Personal Information ──────────────────────────────────────────
            Forms\Components\Section::make('Personal Information')
                ->icon('heroicon-o-user')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->nullable()
                            ->maxLength(100),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male'   => 'Male',
                                'Female' => 'Female',
                                'Other'  => 'Other',
                            ])
                            ->nullable()
                            ->placeholder('— optional —'),
                        Forms\Components\DatePicker::make('birthdate')
                            ->label('Birthdate (Optional)')
                            ->nullable()
                            ->maxDate(now())
                            ->displayFormat('M d, Y')
                            ->reactive(),
                        Forms\Components\Placeholder::make('age_display')
                            ->label('Age')
                            ->content(function (Get $get): string {
                                $bd = $get('birthdate');
                                if (!$bd) return '—';
                                try {
                                    return Carbon::parse($bd)->age . ' years old';
                                } catch (\Exception $e) {
                                    return '—';
                                }
                            })
                            ->reactive(),
                    ]),
                ]),

            // ── Role & Department ─────────────────────────────────────────────
            Forms\Components\Section::make('Role & Department Assignment')
                ->icon('heroicon-o-building-office')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('panel')
                            ->label('Portal / Role')
                            ->options([
                                'admin'  => '🔴 Admin',
                                'doctor' => '🔵 Doctor',
                                'nurse'  => '🟡 Nurse',
                                'clerk'  => '🔷 Clerk',
                                'tech'   => '🟢 Lab / Radiology Tech',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('departments', [])),

                        Forms\Components\Select::make('departments')
                            ->label(function (Get $get): string {
                                return match ($get('panel')) {
                                    'doctor' => 'Ward / Department',
                                    'nurse'  => 'Ward / Department',
                                    'clerk'  => 'Clerk Assignment',
                                    'tech'   => 'Tech Specialization',
                                    default  => 'Department',
                                };
                            })
                            ->options(function (Get $get): array {
                                return static::departmentOptions($get('panel') ?? '');
                            })
                            ->multiple()
                            ->nullable()
                            ->visible(function (Get $get): bool {
                                return in_array($get('panel'), ['doctor', 'nurse', 'clerk', 'tech']);
                            })
                            ->helperText('Select one or more.')
                            ->reactive(),
                    ]),
                ]),

            // ── Account Credentials ───────────────────────────────────────────
            Forms\Components\Section::make('Account Credentials')
                ->icon('heroicon-o-key')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('employee_id')
                            ->label('Employee ID')
                            ->helperText('This will be their username AND default password.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->reactive()
                            ->afterStateUpdated(function (string $state, callable $set) {
                                $set('username', $state);
                            }),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address (Optional)')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->helperText('Optional. Use @lumc.gov.ph format for staff.'),
                    ]),
                    Forms\Components\Hidden::make('username'),
                ]),

            // ── Account Settings ──────────────────────────────────────────────
            Forms\Components\Section::make('Account Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Account Active')
                        ->default(true)
                        ->helperText('Inactive accounts cannot log in.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')
                    ->label('Employee ID')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->description(fn (User $r): string => implode(', ', $r->departments ?? []))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birthdate')
                    ->label('Age')
                    ->formatStateUsing(fn ($state) => $state
                        ? Carbon::parse($state)->age . ' yrs'
                        : '—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('panel')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'admin'  => 'danger',
                        'doctor' => 'primary',
                        'nurse'  => 'warning',
                        'clerk'  => 'info',
                        'tech'   => 'success',
                        default  => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\IconColumn::make('force_password_change')
                    ->boolean()
                    ->label('Pwd Change')
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn ($query) => $query->where('panel', '!=', 'patient'))
            ->filters([
                Tables\Filters\SelectFilter::make('panel')
                    ->options([
                        'admin'  => 'Admin',
                        'doctor' => 'Doctor',
                        'nurse'  => 'Nurse',
                        'clerk'  => 'Clerk',
                        'tech'   => 'Tech',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active Users'),
                Tables\Filters\SelectFilter::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Password')
                    ->modalDescription('Password will be reset to their Employee ID. They will be forced to change it on next login.')
                    ->action(function (User $record) {
                        $newPwd = $record->employee_id ?? $record->username ?? $record->name;
                        $record->update([
                            'password'              => Hash::make($newPwd),
                            'force_password_change' => true,
                        ]);
                        Notification::make()
                            ->title('Password reset!')
                            ->body("Temporary password set to: {$newPwd}")
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit'   => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}