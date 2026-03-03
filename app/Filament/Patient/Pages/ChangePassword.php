<?php

namespace App\Filament\Patient\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon          = 'heroicon-o-lock-closed';
    protected static string  $view                    = 'filament.patient.pages.change-password';
    protected static ?string $navigationLabel         = 'Change Password';
    protected static ?string $title                   = 'Set Your Password';
    protected static bool    $shouldRegisterNavigation = false;

    public string $currentPassword = '';
    public string $newPassword     = '';
    public string $confirmPassword = '';

    public function save(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword'     => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'confirmPassword.same' => 'Passwords do not match.',
            'newPassword.min'      => 'New password must be at least 8 characters.',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => 'The current password is incorrect.',
            ]);
        }

        if ($this->currentPassword === $this->newPassword) {
            throw ValidationException::withMessages([
                'newPassword' => 'New password must be different from your current password.',
            ]);
        }

        $user->update([
            'password'              => Hash::make($this->newPassword),
            'force_password_change' => false,
        ]);

        Notification::make()
            ->title('Password changed! Welcome to LUMC Patient Portal.')
            ->success()
            ->send();

        $this->redirect('/patient/my-records');
    }
}