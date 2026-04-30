<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'admin',
            'doctor',
            'nurse',
            'clerk',
            'clerk-opd',
            'clerk-er',
            'tech',
            'tech-med',
            'tech-rad',
            'tech-tech',
            'patient',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('✅ Roles created: ' . implode(', ', $roles));
    }
}