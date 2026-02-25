<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin', 'doctor', 'nurse',
            'clerk', 'clerk-opd', 'clerk-er', 'tech', 'patient'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create default admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@lumc.gov.ph'],
            [
                'name'       => 'LUMC Administrator',
                'password'   => bcrypt('password'),
                'panel'      => 'admin',
                'is_active'  => true,
                'employee_id'=> 'ADMIN-001',
            ]
        );
        $admin->assignRole('admin');

        // Create default doctor
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@lumc.gov.ph'],
            [
                'name'       => 'Dr. Sample Doctor',
                'password'   => bcrypt('password'),
                'panel'      => 'doctor',
                'is_active'  => true,
                'employee_id'=> 'MD-001',
            ]
        );
        $doctor->assignRole('doctor');

        // Create default nurse
        $nurse = User::firstOrCreate(
            ['email' => 'nurse@lumc.gov.ph'],
            [
                'name'       => 'Nurse Sample',
                'password'   => bcrypt('password'),
                'panel'      => 'nurse',
                'is_active'  => true,
                'employee_id'=> 'NUR-001',
            ]
        );
        $nurse->assignRole('nurse');

        // Create default clerk
        $clerk = User::firstOrCreate(
            ['email' => 'clerk@lumc.gov.ph'],
            [
                'name'       => 'Clerk Sample',
                'password'   => bcrypt('password'),
                'panel'      => 'clerk',
                'is_active'  => true,
                'employee_id'=> 'CLK-001',
            ]
        );
        $clerk->assignRole('clerk');

        // Create default tech
        $tech = User::firstOrCreate(
            ['email' => 'tech@lumc.gov.ph'],
            [
                'name'       => 'Tech Sample',
                'password'   => bcrypt('password'),
                'panel'      => 'tech',
                'is_active'  => true,
                'employee_id'=> 'TEC-001',
            ]
        );
        $tech->assignRole('tech');
    }
}