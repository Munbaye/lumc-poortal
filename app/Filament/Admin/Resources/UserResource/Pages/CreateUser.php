<?php
namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Build full name: Last, First Middle
        $parts = array_filter([
            $data['first_name']  ?? null,
            $data['middle_name'] ?? null,
            $data['last_name']   ?? null,
        ]);
        $data['name'] = implode(' ', $parts);

        // Username mirrors the employee_id
        $data['username'] = $data['employee_id'];

        // Password = employee_id, force change on first login
        $data['password']              = Hash::make($data['employee_id']);
        $data['force_password_change'] = true;

        return $data;
    }

    protected function afterCreate(): void
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