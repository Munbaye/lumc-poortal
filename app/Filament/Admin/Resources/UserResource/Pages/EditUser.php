<?php
namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record;
        $data['first_name']  = $user->first_name  ?? '';
        $data['middle_name'] = $user->middle_name ?? '';
        $data['last_name']   = $user->last_name   ?? '';
        $data['departments'] = $user->departments  ?? [];
        $data['username']    = $user->employee_id  ?? $user->username ?? '';
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Rebuild full name
        $parts = array_filter([
            $data['first_name']  ?? null,
            $data['middle_name'] ?? null,
            $data['last_name']   ?? null,
        ]);
        $data['name'] = implode(' ', $parts);

        // Keep username in sync with employee_id
        $data['username'] = $data['employee_id'];

        // If employee_id changed, update the password too and force change
        if ($this->record->employee_id !== $data['employee_id']) {
            $data['password']              = Hash::make($data['employee_id']);
            $data['force_password_change'] = true;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $user  = $this->record;
        $panel = $user->panel;

        $user->syncRoles([]);

        $this->ensureRole($panel);
        $user->assignRole($panel);

        foreach ($user->departments ?? [] as $dept) {
            $sub = $this->subRole($panel, $dept);
            if ($sub) {
                $this->ensureRole($sub);
                $user->assignRole($sub);
            }
        }
    }

    private function ensureRole(string $role): void
    {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }

    private function subRole(string $panel, string $dept): ?string
    {
        return match ($panel) {
            'clerk'  => 'clerk-'  . strtolower($dept),
            'tech'   => 'tech-'   . strtolower($dept),
            'doctor' => 'doctor-' . strtolower(str_replace([' ', '/'], '-', $dept)),
            'nurse'  => 'nurse-'  . strtolower(str_replace([' ', '/'], '-', $dept)),
            default  => null,
        };
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}