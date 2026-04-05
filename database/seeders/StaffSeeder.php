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
        // ── Create all roles ───────────────────────────────────────────────
        $roles = [
            // Base panel roles
            'admin', 'doctor', 'nurse', 'clerk', 'tech', 'patient',
            // Clerk sub-roles
            'clerk-opd', 'clerk-er',
            // Tech sub-roles
            'tech-rad', 'tech-med', 'tech-tech',
            // Doctor ward roles
            'doctor-internal-medicine', 'doctor-pediatrics', 'doctor-surgery',
            'doctor-ob-gyne', 'doctor-emergency', 'doctor-nicu', 'doctor-icu',
            // Nurse ward roles
            'nurse-internal-medicine', 'nurse-pediatrics', 'nurse-surgery',
            'nurse-ob-gyne', 'nurse-emergency', 'nurse-nicu', 'nurse-icu',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Admin ──────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@lumc.gov.ph'],
            [
                'name'        => 'System Administrator',
                'first_name'  => 'System',
                'last_name'   => 'Administrator',
                'username'    => 'admin',
                'employee_id' => 'ADMIN-001',
                'panel'       => 'admin',
                'is_active'   => true,
                'password'    => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        // ── Doctors ────────────────────────────────────────────────────────
        $doctorDefs = [
            ['Dr. Ricardo Santos',   'rsantos',   'doctor@lumc.gov.ph',    'MD-001', 'Internal Medicine', ['Internal Medicine']],
            ['Dr. Maria Reyes',      'mreyes',    'mreyes@lumc.gov.ph',    'MD-002', 'Pediatrics',        ['Pediatrics']],
            ['Dr. Jose Dela Cruz',   'jdelacruz', 'jdelacruz@lumc.gov.ph', 'MD-003', 'Surgery',           ['Surgery']],
            ['Dr. Ana Mendoza',      'amendoza',  'amendoza@lumc.gov.ph',  'MD-004', 'OB-Gyne',           ['OB-Gyne']],
            ['Dr. Roberto Bautista', 'rbautista', 'rbautista@lumc.gov.ph', 'MD-005', 'Emergency',         ['Emergency']],
            ['Dr. Lourdes Castillo', 'lcastillo', 'lcastillo@lumc.gov.ph', 'MD-006', 'Cardiology',        []],
        ];

        foreach ($doctorDefs as [$name, $username, $email, $empId, $specialty, $depts]) {
            $doc = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'username'    => $username,
                    'email'       => $email,
                    'employee_id' => $empId,
                    'specialty'   => $specialty,
                    'departments' => $depts,
                    'panel'       => 'doctor',
                    'is_active'   => true,
                    'password'    => Hash::make('password'),
                ]
            );
            $roles = ['doctor'];
            foreach ($depts as $dept) {
                $roles[] = 'doctor-' . strtolower(str_replace([' ', '-'], '-', $dept));
            }
            $doc->syncRoles($roles);
        }

        // ── Nurses ─────────────────────────────────────────────────────────
        $nurseDefs = [
            ['Nurse Ana Gonzalez', 'agonzalez', 'nurse@lumc.gov.ph',   'RN-001', ['Internal Medicine']],
            ['Nurse Ben Torres',   'btorres',   'btorres@lumc.gov.ph', 'RN-002', ['Emergency']],
            ['Nurse Clara Ramos',  'cramos',    'cramos@lumc.gov.ph',  'RN-003', ['Pediatrics']],
        ];

        foreach ($nurseDefs as [$name, $username, $email, $empId, $depts]) {
            $nurse = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'username'    => $username,
                    'email'       => $email,
                    'employee_id' => $empId,
                    'departments' => $depts,
                    'panel'       => 'nurse',
                    'is_active'   => true,
                    'password'    => Hash::make('password'),
                ]
            );
            $roles = ['nurse'];
            foreach ($depts as $dept) {
                $roles[] = 'nurse-' . strtolower(str_replace([' ', '-'], '-', $dept));
            }
            $nurse->syncRoles($roles);
        }

        // ── Clerks ─────────────────────────────────────────────────────────
        $clerkDefs = [
            ['Clerk Rosa Villanueva', 'rvillanueva', 'clerk@lumc.gov.ph',   'CLK-001', ['OPD']],
            ['Clerk Mark Aquino',     'maquino',     'maquino@lumc.gov.ph', 'CLK-002', ['OPD', 'ER']],
        ];

        foreach ($clerkDefs as [$name, $username, $email, $empId, $depts]) {
            $clerk = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'username'    => $username,
                    'email'       => $email,
                    'employee_id' => $empId,
                    'departments' => $depts,
                    'panel'       => 'clerk',
                    'is_active'   => true,
                    'password'    => Hash::make('password'),
                ]
            );
            $roles = ['clerk'];
            foreach ($depts as $dept) {
                $roles[] = 'clerk-' . strtolower($dept);
            }
            $clerk->syncRoles($roles);
        }

        // ── Techs ──────────────────────────────────────────────────────────
        $techDefs = [
            ['Med Tech Josie Panganiban', 'jmedtech',  'medtech@lumc.gov.ph', 'MT-001', 'Medical Technologist',   ['MED']],
            ['Rad Tech Paolo Cruz',       'pradtech',  'radtech@lumc.gov.ph', 'RT-001', 'Radiologic Technologist', ['RAD']],
            ['Tech User',                 'techuser',  'tech@lumc.gov.ph',    'TU-001', 'General Tech',            ['TECH']],
        ];

        foreach ($techDefs as [$name, $username, $email, $empId, $specialty, $depts]) {
            $tech = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'username'    => $username,
                    'email'       => $email,
                    'employee_id' => $empId,
                    'specialty'   => $specialty,
                    'departments' => $depts,
                    'panel'       => 'tech',
                    'is_active'   => true,
                    'password'    => Hash::make('password'),
                ]
            );
            $roles = ['tech'];
            foreach ($depts as $dept) {
                $roles[] = 'tech-' . strtolower($dept);
            }
            $tech->syncRoles($roles);
        }

        // ── Patient portal demo accounts ───────────────────────────────────
        $patients = [
            ['Liza Bautista',  'lizabautista',  'liza.bautista@email.com'],
            ['Ramon Mendoza',  'ramonmendoza',  'ramon.mendoza@email.com'],
        ];

        foreach ($patients as [$name, $username, $email]) {
            $pat = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'                  => $name,
                    'username'              => $username,
                    'email'                 => $email,
                    'panel'                 => 'patient',
                    'is_active'             => true,
                    'force_password_change' => false,
                    'password'              => Hash::make('password'),
                ]
            );
            $pat->syncRoles(['patient']);
        }

        $this->command->info('✓ Staff seeded with all roles and department assignments.');
    }
}