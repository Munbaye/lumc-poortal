<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Email / password scheme:
     *
     *  admin          → admin@lumc.gov.ph        password
     *  doctor (main)  → doctor@lumc.gov.ph       password   ← dr.santos (Internal Medicine, primary presenter)
     *  doctor (extra) → doctor2@lumc.gov.ph      password   ← dr.dela   (Surgery)
     *  nurse (main)   → nurse@lumc.gov.ph        password   ← nurse.dela
     *  nurse (extra)  → nurse2@lumc.gov.ph       password   ← nurse.santos
     *  clerk (main)   → clerk@lumc.gov.ph        password   ← clerk.main
     *  clerk-opd      → opd@lumc.gov.ph          password   ← clerk.opd
     *  clerk-er       → er@lumc.gov.ph           password   ← clerk.er
     *  tech-med (×2)  → medtech@lumc.gov.ph      password   ← tech.lab1
     *                   medtech2@lumc.gov.ph     password   ← tech.lab2
     *  tech-rad       → radtech@lumc.gov.ph      password   ← tech.rad
     *  tech (generic) → tech@lumc.gov.ph         password   ← tech.main
     */

    public function run(): void
    {
        $pw      = Hash::make('password');
        $summary = [];

        // ── ADMIN ──────────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(['username' => 'admin'], [
            'name'        => 'System Administrator',
            'first_name'  => 'System',
            'last_name'   => 'Administrator',
            'email'       => 'admin@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'admin',
            'employee_id' => 'EMP-0001',
            'is_active'   => true,
        ]);
        $admin->assignRole('admin');
        $summary[] = ['role' => 'admin', 'username' => 'admin', 'email' => 'admin@lumc.gov.ph', 'name' => $admin->name];

        // ── DOCTORS ────────────────────────────────────────────────────────────
        // Primary demo doctor — always use doctor@lumc.gov.ph for presentations
        $drSantos = User::firstOrCreate(['username' => 'dr.santos'], [
            'name'        => 'Dr. Ricardo Santos',
            'first_name'  => 'Ricardo',
            'middle_name' => 'Bautista',
            'last_name'   => 'Santos',
            'email'       => 'doctor@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'doctor',
            'specialty'   => 'Internal Medicine',
            'employee_id' => 'MD-0001',
            'gender'      => 'Male',
            'is_active'   => true,
        ]);
        $drSantos->assignRole('doctor');
        $summary[] = ['role' => 'doctor', 'username' => 'dr.santos', 'email' => 'doctor@lumc.gov.ph', 'name' => 'Dr. Ricardo Santos (Internal Medicine)'];

        // Secondary doctor (Pediatrics) — uses dr.reyes username, doctor2 email
        $drReyes = User::firstOrCreate(['username' => 'dr.reyes'], [
            'name'        => 'Dr. Maria Reyes',
            'first_name'  => 'Maria',
            'middle_name' => 'Cruz',
            'last_name'   => 'Reyes',
            'email'       => 'doctor2@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'doctor',
            'specialty'   => 'Pediatrics',
            'employee_id' => 'MD-0002',
            'gender'      => 'Female',
            'is_active'   => true,
        ]);
        $drReyes->assignRole('doctor');
        $summary[] = ['role' => 'doctor', 'username' => 'dr.reyes', 'email' => 'doctor2@lumc.gov.ph', 'name' => 'Dr. Maria Reyes (Pediatrics)'];

        // Extra doctor — Surgery, gets a small set of non-Private visits so dr.santos stays
        // the clear primary. Private patients (Reyes/Eduardo, Aquino) stay with dr.santos.
        $drGarcia = User::firstOrCreate(['username' => 'dr.garcia'], [
            'name'        => 'Dr. Jose Garcia',
            'first_name'  => 'Jose',
            'middle_name' => 'Dela',
            'last_name'   => 'Garcia',
            'email'       => 'doctor3@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'doctor',
            'specialty'   => 'Surgery',
            'employee_id' => 'MD-0003',
            'gender'      => 'Male',
            'is_active'   => true,
        ]);
        $drGarcia->assignRole('doctor');
        $summary[] = ['role' => 'doctor', 'username' => 'dr.garcia', 'email' => 'doctor3@lumc.gov.ph', 'name' => 'Dr. Jose Garcia (Surgery)'];

        // ── NURSES ─────────────────────────────────────────────────────────────
        // Primary nurse — nurse@lumc.gov.ph
        $nurse1 = User::firstOrCreate(['username' => 'nurse.dela'], [
            'name'        => 'Anna Dela Cruz',
            'first_name'  => 'Anna',
            'last_name'   => 'Dela Cruz',
            'email'       => 'nurse@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'nurse',
            'employee_id' => 'RN-0001',
            'is_active'   => true,
        ]);
        $nurse1->assignRole('nurse');
        $summary[] = ['role' => 'nurse', 'username' => 'nurse.dela', 'email' => 'nurse@lumc.gov.ph', 'name' => 'Anna Dela Cruz'];

        // Extra nurse — nurse2@lumc.gov.ph
        $nurse4 = User::firstOrCreate(['username' => 'nurse.santos'], [
            'name'        => 'Carla Santos',
            'first_name'  => 'Carla',
            'middle_name' => 'Reyes',
            'last_name'   => 'Santos',
            'email'       => 'nurse2@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'nurse',
            'employee_id' => 'RN-0004',
            'gender'      => 'Female',
            'is_active'   => true,
        ]);
        $nurse4->assignRole('nurse');
        $summary[] = ['role' => 'nurse', 'username' => 'nurse.santos', 'email' => 'nurse2@lumc.gov.ph', 'name' => 'Carla Santos'];

        // Supporting nurses (used internally by VisitsSeeder for realistic multi-shift data)
        $nurse2 = User::firstOrCreate(['username' => 'nurse.flores'], [
            'name'        => 'Leonora Flores',
            'first_name'  => 'Leonora',
            'last_name'   => 'Flores',
            'email'       => 'nurse3@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'nurse',
            'employee_id' => 'RN-0002',
            'is_active'   => true,
        ]);
        $nurse2->assignRole('nurse');
        $summary[] = ['role' => 'nurse', 'username' => 'nurse.flores', 'email' => 'nurse3@lumc.gov.ph', 'name' => 'Leonora Flores'];

        $nurse3 = User::firstOrCreate(['username' => 'nurse.torres'], [
            'name'        => 'Patricia Torres',
            'first_name'  => 'Patricia',
            'last_name'   => 'Torres',
            'email'       => 'nurse4@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'nurse',
            'employee_id' => 'RN-0003',
            'is_active'   => true,
        ]);
        $nurse3->assignRole('nurse');
        $summary[] = ['role' => 'nurse', 'username' => 'nurse.torres', 'email' => 'nurse4@lumc.gov.ph', 'name' => 'Patricia Torres'];

        // ── CLERKS ─────────────────────────────────────────────────────────────
        $clerkMain = User::firstOrCreate(['username' => 'clerk.main'], [
            'name'        => 'Maricel Estrada',
            'first_name'  => 'Maricel',
            'last_name'   => 'Estrada',
            'email'       => 'clerk@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'clerk',
            'employee_id' => 'CLK-0003',
            'is_active'   => true,
        ]);
        $clerkMain->assignRole('clerk');
        $summary[] = ['role' => 'clerk', 'username' => 'clerk.main', 'email' => 'clerk@lumc.gov.ph', 'name' => 'Maricel Estrada'];

        $clerkOpd = User::firstOrCreate(['username' => 'clerk.opd'], [
            'name'        => 'Grace Mendoza',
            'first_name'  => 'Grace',
            'last_name'   => 'Mendoza',
            'email'       => 'opd@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'clerk',
            'employee_id' => 'CLK-0001',
            'is_active'   => true,
        ]);
        $clerkOpd->assignRole('clerk-opd');
        $summary[] = ['role' => 'clerk-opd', 'username' => 'clerk.opd', 'email' => 'opd@lumc.gov.ph', 'name' => 'Grace Mendoza'];

        $clerkEr = User::firstOrCreate(['username' => 'clerk.er'], [
            'name'        => 'Ronald Aquino',
            'first_name'  => 'Ronald',
            'last_name'   => 'Aquino',
            'email'       => 'er@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'clerk',
            'employee_id' => 'CLK-0002',
            'is_active'   => true,
        ]);
        $clerkEr->assignRole('clerk-er');
        $summary[] = ['role' => 'clerk-er', 'username' => 'clerk.er', 'email' => 'er@lumc.gov.ph', 'name' => 'Ronald Aquino'];

        // ── TECHS ──────────────────────────────────────────────────────────────
        $techLab1 = User::firstOrCreate(['username' => 'tech.lab1'], [
            'name'        => 'Jerome Castillo',
            'first_name'  => 'Jerome',
            'last_name'   => 'Castillo',
            'email'       => 'medtech@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'tech',
            'employee_id' => 'TCH-0001',
            'is_active'   => true,
        ]);
        $techLab1->assignRole('tech-med');
        $summary[] = ['role' => 'tech-med', 'username' => 'tech.lab1', 'email' => 'medtech@lumc.gov.ph', 'name' => 'Jerome Castillo'];

        $techLab2 = User::firstOrCreate(['username' => 'tech.lab2'], [
            'name'        => 'Cristina Villanueva',
            'first_name'  => 'Cristina',
            'last_name'   => 'Villanueva',
            'email'       => 'medtech2@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'tech',
            'employee_id' => 'TCH-0002',
            'is_active'   => true,
        ]);
        $techLab2->assignRole('tech-med');
        $summary[] = ['role' => 'tech-med', 'username' => 'tech.lab2', 'email' => 'medtech2@lumc.gov.ph', 'name' => 'Cristina Villanueva'];

        $techRad = User::firstOrCreate(['username' => 'tech.rad'], [
            'name'        => 'Roberto Domingo',
            'first_name'  => 'Roberto',
            'last_name'   => 'Domingo',
            'email'       => 'radtech@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'tech',
            'employee_id' => 'TCH-0003',
            'is_active'   => true,
        ]);
        $techRad->assignRole('tech-rad');
        $summary[] = ['role' => 'tech-rad', 'username' => 'tech.rad', 'email' => 'radtech@lumc.gov.ph', 'name' => 'Roberto Domingo'];

        // Generic tech (tech-tech role, accessed via tech panel)
        $techMain = User::firstOrCreate(['username' => 'tech.main'], [
            'name'        => 'Alvin Bautista',
            'first_name'  => 'Alvin',
            'last_name'   => 'Bautista',
            'email'       => 'tech@lumc.gov.ph',
            'password'    => $pw,
            'panel'       => 'tech',
            'employee_id' => 'TCH-0004',
            'is_active'   => true,
        ]);
        $techMain->assignRole('tech-tech');
        $summary[] = ['role' => 'tech-tech', 'username' => 'tech.main', 'email' => 'tech@lumc.gov.ph', 'name' => 'Alvin Bautista'];

        // ── PRINT CREDENTIALS ──────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════════════════');
        $this->command->info('  LUMC SYSTEM — USER CREDENTIALS  (password: password)');
        $this->command->info('═══════════════════════════════════════════════════════════════════');
        foreach ($summary as $u) {
            $this->command->line(sprintf(
                '  %-12s %-20s %-32s %s',
                '[' . strtoupper($u['role']) . ']',
                $u['username'],
                $u['email'],
                $u['name']
            ));
        }
        $this->command->info('═══════════════════════════════════════════════════════════════════');
        $this->command->info('  ' . count($summary) . ' users created.');
        $this->command->newLine();
    }
}