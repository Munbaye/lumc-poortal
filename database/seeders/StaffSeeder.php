<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create roles first (canAccessPanel depends on these) ───────────
        foreach (['admin', 'doctor', 'nurse', 'clerk', 'clerk-opd', 'clerk-er', 'tech', 'patient'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Admin ──────────────────────────────────────────────────────────
        User::create([
            'name'        => 'System Administrator',
            'username'    => 'admin',
            'email'       => 'admin@lumc.gov.ph',
            'employee_id' => 'ADMIN-001',
            'panel'       => 'admin',
            'is_active'   => true,
            'password'    => Hash::make('password'),
        ])->assignRole('admin');

        // ── Doctors ────────────────────────────────────────────────────────
        $doctorDefs = [
            ['Dr. Ricardo Santos',   'rsantos',   'doctor@lumc.gov.ph',    'MD-001', 'Internal Medicine'],
            ['Dr. Maria Reyes',      'mreyes',    'mreyes@lumc.gov.ph',    'MD-002', 'Pediatrics'],
            ['Dr. Jose Dela Cruz',   'jdelacruz', 'jdelacruz@lumc.gov.ph', 'MD-003', 'Surgery'],
            ['Dr. Ana Mendoza',      'amendoza',  'amendoza@lumc.gov.ph',  'MD-004', 'OB-Gyne'],
            ['Dr. Roberto Bautista', 'rbautista', 'rbautista@lumc.gov.ph', 'MD-005', 'Emergency Medicine'],
            ['Dr. Lourdes Castillo', 'lcastillo', 'lcastillo@lumc.gov.ph', 'MD-006', 'Cardiology'],
        ];

        foreach ($doctorDefs as [$name, $username, $email, $empId, $specialty]) {
            User::create([
                'name'        => $name,
                'username'    => $username,
                'email'       => $email,
                'employee_id' => $empId,
                'specialty'   => $specialty,
                'panel'       => 'doctor',
                'is_active'   => true,
                'password'    => Hash::make('password'),
            ])->assignRole('doctor');
        }

        // ── Nurses ─────────────────────────────────────────────────────────
        $nurseDefs = [
            ['Nurse Ana Gonzalez', 'agonzalez', 'nurse@lumc.gov.ph',   'RN-001'],
            ['Nurse Ben Torres',   'btorres',   'btorres@lumc.gov.ph', 'RN-002'],
            ['Nurse Clara Ramos',  'cramos',    'cramos@lumc.gov.ph',  'RN-003'],
        ];

        foreach ($nurseDefs as [$name, $username, $email, $empId]) {
            User::create([
                'name'        => $name,
                'username'    => $username,
                'email'       => $email,
                'employee_id' => $empId,
                'panel'       => 'nurse',
                'is_active'   => true,
                'password'    => Hash::make('password'),
            ])->assignRole('nurse');
        }

        // ── Clerks ─────────────────────────────────────────────────────────
        $clerkDefs = [
            ['Clerk Rosa Villanueva', 'rvillanueva', 'clerk@lumc.gov.ph',    'CLK-001'],
            ['Clerk Mark Aquino',     'maquino',     'maquino@lumc.gov.ph',  'CLK-002'],
        ];

        foreach ($clerkDefs as [$name, $username, $email, $empId]) {
            User::create([
                'name'        => $name,
                'username'    => $username,
                'email'       => $email,
                'employee_id' => $empId,
                'panel'       => 'clerk',
                'is_active'   => true,
                'password'    => Hash::make('password'),
            ])->assignRole(['clerk', 'clerk-opd']);
        }

        // ── Techs ──────────────────────────────────────────────────────────
        User::create([
            'name'        => 'Med Tech Josie Panganiban',
            'username'    => 'jmedtech',
            'email'       => 'medtech@lumc.gov.ph',
            'employee_id' => 'MT-001',
            'specialty'   => 'Medical Technologist',
            'panel'       => 'tech',
            'is_active'   => true,
            'password'    => Hash::make('password'),
        ])->assignRole('tech');

        User::create([
            'name'        => 'Rad Tech Paolo Cruz',
            'username'    => 'pradtech',
            'email'       => 'radtech@lumc.gov.ph',
            'employee_id' => 'RT-001',
            'specialty'   => 'Radiologic Technologist',
            'panel'       => 'tech',
            'is_active'   => true,
            'password'    => Hash::make('password'),
        ])->assignRole('tech');

        User::create([
            'name'        => 'Tech User',
            'username'    => 'techuser',
            'email'       => 'tech@lumc.gov.ph',
            'employee_id' => 'TU-001',
            'specialty'   => 'Medical Technologist',
            'panel'       => 'tech',
            'is_active'   => true,
            'password'    => Hash::make('password'),
        ])->assignRole('tech');

        // ── Patient portal demo accounts ───────────────────────────────────
        User::create([
            'name'                  => 'Liza Bautista',
            'username'              => 'lizabautista',
            'email'                 => 'liza.bautista@email.com',
            'panel'                 => 'patient',
            'is_active'             => true,
            'force_password_change' => false,
            'password'              => Hash::make('password'),
        ])->assignRole('patient');

        User::create([
            'name'                  => 'Ramon Mendoza',
            'username'              => 'ramonmendoza',
            'email'                 => 'ramon.mendoza@email.com',
            'panel'                 => 'patient',
            'is_active'             => true,
            'force_password_change' => false,
            'password'              => Hash::make('password'),
        ])->assignRole('patient');

        $this->command->info('   ✓ Staff seeded (admin + 6 doctors + 3 nurses + 2 clerks + 3 techs + 2 portal users)');
    }
}