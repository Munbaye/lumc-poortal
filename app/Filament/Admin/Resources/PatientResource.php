<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PatientResource\Pages;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class PatientResource extends Resource
{
    protected static ?string $model          = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Patients';
    protected static ?string $modelLabel     = 'Patient';
    protected static ?int    $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Portal Account')
                ->schema([
                    Forms\Components\Placeholder::make('portal_username')
                        ->label('Patient Username (Login)')
                        ->content(fn ($record) => $record?->userAccount?->username ?? '— No account yet —'),
                ])
                ->visible(fn ($record) => $record !== null),

            Forms\Components\Section::make('Identity')
                ->schema([
                    Forms\Components\TextInput::make('family_name')
                        ->label('Family Name')
                        ->required()
                        ->dehydrateStateUsing(fn ($s) => ucwords(strtolower($s))),

                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->dehydrateStateUsing(fn ($s) => ucwords(strtolower($s))),

                    Forms\Components\TextInput::make('middle_name')
                        ->label('Middle Name')
                        ->nullable()
                        ->dehydrateStateUsing(fn ($s) => $s ? ucwords(strtolower($s)) : null),

                    Forms\Components\Select::make('sex')
                        ->options(['Male' => 'Male', 'Female' => 'Female'])
                        ->required(),

                    Forms\Components\DatePicker::make('birthday')
                        ->label('Birthday')
                        ->nullable(),

                    Forms\Components\TextInput::make('age')
                        ->label('Age (if no birthday)')
                        ->numeric()
                        ->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Contact & Address')
                ->schema([
                    Forms\Components\Textarea::make('address')
                        ->label('Complete Address')
                        ->rows(2)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('contact_number')
                        ->label('Contact Number')
                        ->nullable(),

                    Forms\Components\TextInput::make('occupation')
                        ->nullable(),

                    Forms\Components\Select::make('civil_status')
                        ->options([
                            'Single'    => 'Single',
                            'Married'   => 'Married',
                            'Widowed'   => 'Widowed',
                            'Separated' => 'Separated',
                            'Annulled'  => 'Annulled',
                        ])
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Family Information')
                ->schema([
                    Forms\Components\TextInput::make('father_name')
                        ->label("Father's Name")->nullable(),
                    Forms\Components\TextInput::make('mother_name')
                        ->label("Mother's Name")->nullable(),
                    Forms\Components\TextInput::make('spouse_name')
                        ->label('Spouse Name')->nullable(),
                ])->columns(3)->collapsed(),

            Forms\Components\Section::make('Registration Status')
                ->schema([
                    Forms\Components\Select::make('registration_type')
                        ->options(['OPD' => 'OPD', 'ER' => 'ER'])
                        ->default('OPD'),

                    Forms\Components\Toggle::make('has_incomplete_info')
                        ->label('Has Incomplete Info')
                        ->helperText('When ON, patient name shows in red across all panels.'),

                    Forms\Components\Toggle::make('is_unknown')
                        ->label('Unknown / Unidentified Patient')
                        ->helperText('Turn OFF once identity is confirmed.'),
                ])->columns(3),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('case_no')
                    ->label('Case No')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(['family_name', 'first_name'])
                    ->sortable('family_name')
                    ->color(fn ($record) => $record->has_incomplete_info ? 'danger' : null),

                Tables\Columns\TextColumn::make('sex'),

                Tables\Columns\TextColumn::make('age_display')
                    ->label('Age'),

                Tables\Columns\TextColumn::make('registration_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'ER' ? 'danger' : 'primary'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) =>
                        $record->is_unknown ? 'Unknown' :
                        ($record->has_incomplete_info ? 'Incomplete' : 'Complete')
                    )
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Unknown'    => 'warning',
                        'Incomplete' => 'danger',
                        'Complete'   => 'success',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_unknown')
                    ->label('Unknown Patients'),
                Tables\Filters\TernaryFilter::make('has_incomplete_info')
                    ->label('Incomplete Info'),
                Tables\Filters\SelectFilter::make('registration_type')
                    ->options(['OPD' => 'OPD', 'ER' => 'ER']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('create_account')
                    ->label('Create Account')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn (Patient $record) =>
                        !$record->is_unknown &&
                        !User::where('patient_id', $record->id)->exists()
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Create Patient Portal Account')
                    ->modalDescription('Creates a login account. Username and password shown once after creation.')
                    ->action(function (Patient $record) {
                        $firstName = preg_replace('/[^a-zA-Z]/', '', $record->first_name);
                        $lastName  = preg_replace('/[^a-zA-Z]/', '', $record->family_name);
                        $age       = $record->current_age ?? $record->age ?? 0;

                        $baseUsername = ucfirst(strtolower($firstName)) . ucfirst(strtolower($lastName)) . $age;
                        $username     = $baseUsername;
                        $counter      = 1;
                        while (User::where('username', $username)->exists()) {
                            $username = $baseUsername . $counter++;
                        }

                        $email = 'patient_' . $record->id . '_' . time() . '@internal';

                        $user = User::create([
                            'name'                  => $record->full_name,
                            'username'              => $username,
                            'email'                 => $email,
                            'password'              => Hash::make($username),
                            'panel'                 => 'patient',
                            'is_active'             => true,
                            'patient_id'            => $record->id,
                            'force_password_change' => true,
                        ]);

                        $role = \Spatie\Permission\Models\Role::firstOrCreate(
                            ['name' => 'patient', 'guard_name' => 'web']
                        );
                        $user->assignRole($role);

                        Notification::make()
                            ->title('Account created!')
                            ->body("Username: {$username}  |  Password: {$username}")
                            ->success()
                            ->persistent()
                            ->send();
                    }),

                Tables\Actions\Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->visible(fn (Patient $record) =>
                        User::where('patient_id', $record->id)->exists()
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Reset Patient Password')
                    ->modalDescription('Resets password to the username. Patient must change it on next login.')
                    ->action(function (Patient $record) {
                        $user = User::where('patient_id', $record->id)->first();
                        if (!$user) return;

                        $newPwd = $user->username ?? $user->name;
                        $user->update([
                            'password'              => Hash::make($newPwd),
                            'force_password_change' => true,
                        ]);

                        Notification::make()
                            ->title('Password reset!')
                            ->body("Temporary password: {$newPwd}")
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit'   => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}