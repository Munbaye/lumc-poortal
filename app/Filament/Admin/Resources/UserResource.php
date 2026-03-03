<?php
namespace App\Filament\Admin\Resources;

use App\Models\User;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Full Name')
                ->required(),
            Forms\Components\TextInput::make('username')
                ->label('Username (Login)')
                ->helperText('For patients: e.g. JuanDelaCruz25. This is what they type to log in.')
                ->unique(ignoreRecord: true)
                ->nullable(),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->nullable()
                ->helperText('Staff email for login. Patients use username instead.'),
            Forms\Components\TextInput::make('employee_id')->label('Employee ID'),
            Forms\Components\Select::make('panel')
                ->options([
                    'admin'   => 'Admin',
                    'doctor'  => 'Doctor',
                    'nurse'   => 'Nurse',
                    'clerk'   => 'Clerk (Both OPD+ER)',
                    'tech'    => 'Lab/Radiology Tech',
                    'patient' => 'Patient (View Only)',
                ])
                ->required()
                ->reactive(),
            Forms\Components\Select::make('patient_id')
                ->label('Linked Patient Record')
                ->options(
                    Patient::orderBy('family_name')
                        ->get()
                        ->mapWithKeys(fn($p) => [
                            $p->id => $p->case_no . ' — ' . $p->full_name
                        ])
                )
                ->searchable()
                ->nullable()
                ->visible(fn ($get) => $get('panel') === 'patient')
                ->helperText('Link this user account to a patient record.'),
            Forms\Components\Select::make('roles')
                ->multiple()
                ->relationship('roles', 'name')
                ->preload()
                ->label('Assigned Roles'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Toggle::make('force_password_change')
                ->label('Force Password Change on Next Login')
                ->default(false),
            // Only show password field on CREATE — never on edit
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->required(fn(string $operation) => $operation === 'create')
                ->visible(fn(string $operation) => $operation === 'create')
                ->label('Initial Password')
                ->helperText('Set the initial password. User can change it after login.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->placeholder('—')
                    ->copyable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('panel')->badge()
                    ->color(fn ($state) => match ($state) {
                        'admin'   => 'danger',
                        'doctor'  => 'primary',
                        'nurse'   => 'warning',
                        'clerk'   => 'info',
                        'tech'    => 'success',
                        'patient' => 'gray',
                        default   => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
                Tables\Columns\IconColumn::make('force_password_change')
                    ->boolean()
                    ->label('Pwd Change')
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('success'),
                Tables\Columns\TextColumn::make('patientRecord.case_no')
                    ->label('Patient ID')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('panel')
                    ->options([
                        'admin'   => 'Admin',
                        'doctor'  => 'Doctor',
                        'nurse'   => 'Nurse',
                        'clerk'   => 'Clerk',
                        'tech'    => 'Tech',
                        'patient' => 'Patient',
                    ]),
                Tables\Filters\TernaryFilter::make('force_password_change')
                    ->label('Pending Password Change'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Users'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Password')
                    ->modalDescription('Password will be reset to their username (patients) or email (staff). They will be forced to change it on next login.')
                    ->action(function (User $record) {
                        // Patients → reset to username (e.g. JuanDelaCruz25)
                        // Staff    → reset to their email (e.g. doctor@lumc.gov.ph)
                        if ($record->panel === 'patient') {
                            $newPwd = $record->username ?? $record->name;
                        } else {
                            $newPwd = $record->email ?? $record->username ?? $record->name;
                        }

                        $record->update([
                            'password'              => Hash::make($newPwd),
                            'force_password_change' => true,
                        ]);

                        Notification::make()
                            ->title('Password reset!')
                            ->body("New temporary password: {$newPwd}")
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ]);
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