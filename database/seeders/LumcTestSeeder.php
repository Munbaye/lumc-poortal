<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\Vital;

class LumcTestSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. ROLES ──────────────────────────────────────────────────────
        $roleNames = ['admin', 'doctor', 'nurse', 'clerk', 'clerk-opd', 'clerk-er', 'tech', 'patient'];
        foreach ($roleNames as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // ── 2. USERS ──────────────────────────────────────────────────────

        // Admin
        $admin = User::updateOrCreate(['email' => 'admin@lumc.gov.ph'], [
            'name'        => 'LUMC Administrator',
            'password'    => Hash::make('password'),
            'panel'       => 'admin',
            'is_active'   => true,
            'employee_id' => 'ADMIN-001',
            'specialty'   => null,
        ]);
        $admin->syncRoles(['admin']);

        // Clerk (OPD)
        $clerk = User::updateOrCreate(['email' => 'clerk@lumc.gov.ph'], [
            'name'        => 'Maria Reyes',
            'password'    => Hash::make('password'),
            'panel'       => 'clerk',
            'is_active'   => true,
            'employee_id' => 'CLK-001',
            'specialty'   => null,
        ]);
        $clerk->syncRoles(['clerk']);

        // Nurse
        $nurse = User::updateOrCreate(['email' => 'nurse@lumc.gov.ph'], [
            'name'        => 'Ana Santos',
            'password'    => Hash::make('password'),
            'panel'       => 'nurse',
            'is_active'   => true,
            'employee_id' => 'NUR-001',
            'specialty'   => null,
        ]);
        $nurse->syncRoles(['nurse']);

        // Doctors — multiple specialties (for private patient dropdown testing)
        $doctors = [
            ['email' => 'doctor@lumc.gov.ph',        'name' => 'Juan dela Cruz',      'specialty' => 'General Practitioner',  'emp' => 'MD-001'],
            ['email' => 'internist@lumc.gov.ph',      'name' => 'Roberto Villanueva',  'specialty' => 'Internal Medicine',     'emp' => 'MD-002'],
            ['email' => 'pedia@lumc.gov.ph',          'name' => 'Liza Ocampo',         'specialty' => 'Pediatrics',            'emp' => 'MD-003'],
            ['email' => 'obgyne@lumc.gov.ph',         'name' => 'Carmen Flores',       'specialty' => 'OB-Gynecology',         'emp' => 'MD-004'],
            ['email' => 'surgeon@lumc.gov.ph',        'name' => 'Eduardo Mendoza',     'specialty' => 'General Surgery',       'emp' => 'MD-005'],
            ['email' => 'cardio@lumc.gov.ph',         'name' => 'Alfredo Bautista',    'specialty' => 'Cardiology',            'emp' => 'MD-006'],
            ['email' => 'ent@lumc.gov.ph',            'name' => 'Patricia Dimaculangan','specialty' => 'ENT',                  'emp' => 'MD-007'],
        ];

        $doctorUsers = [];
        foreach ($doctors as $d) {
            $u = User::updateOrCreate(['email' => $d['email']], [
                'name'        => 'Dr. ' . $d['name'],
                'password'    => Hash::make('password'),
                'panel'       => 'doctor',
                'is_active'   => true,
                'employee_id' => $d['emp'],
                'specialty'   => $d['specialty'],
            ]);
            $u->syncRoles(['doctor']);
            $doctorUsers[] = $u;
        }

        // Tech
        $tech = User::updateOrCreate(['email' => 'tech@lumc.gov.ph'], [
            'name'        => 'Tech Sample',
            'password'    => Hash::make('password'),
            'panel'       => 'tech',
            'is_active'   => true,
            'employee_id' => 'TEC-001',
            'specialty'   => null,
        ]);
        $tech->syncRoles(['tech']);

        // ── 3. PATIENTS ───────────────────────────────────────────────────
        // Realistic Filipino names covering different scenarios

        $patientData = [
            // [family, first, middle, birthday, sex, address, contact, civil_status, occupation]
            ['Dela Cruz',  'Juan',      'Santos',    '1985-03-15', 'Male',   'Brgy. Poblacion, Agoo, La Union',          '09171234567', 'Married',  'Farmer'],
            ['Reyes',      'Maria',     'Garcia',    '1992-07-22', 'Female', 'Brgy. Sta. Barbara, San Fernando, La Union','09281234567', 'Single',   'Teacher'],
            ['Santos',     'Jose',      'Villanueva','1975-11-08', 'Male',   'Brgy. Catbangen, San Fernando, La Union',  '09391234567', 'Married',  'Driver'],
            ['Bautista',   'Rosario',   'Cruz',      '1998-01-30', 'Female', 'Brgy. Calumbaya, Bauang, La Union',        '09501234567', 'Single',   'Student'],
            ['Ramos',      'Pedro',     'Aquino',    '1960-05-12', 'Male',   'Brgy. Lingsat, San Fernando, La Union',    '09611234567', 'Married',  'Retired'],
            // Pedia (age < 12)
            ['Fernandez',  'Angelo',    'Lopez',     '2018-09-04', 'Male',   'Brgy. Magsaysay, Agoo, La Union',          '09721234567', 'Single',   null],
            // Elderly
            ['Garcia',     'Lourdes',   'Mendoza',   '1945-12-25', 'Female', 'Brgy. Central East, Agoo, La Union',       '09831234567', 'Widowed',  'Retired'],
            // ER candidate
            ['Magno',      'Carlos',    'Batungbakal','1988-06-18','Male',   'Brgy. Caoayan, Bauang, La Union',          '09941234567', 'Married',  'Construction Worker'],
            // Private patient
            ['Soriano',    'Isabella',  'Reyes',     '1990-04-11', 'Female', 'Brgy. Ilocanos Norte, San Fernando, La Union','09051234567','Married','Business Owner'],
            // Another private
            ['Aquino',     'Miguel',    'Santos',    '1978-08-27', 'Male',   'Brgy. Tanqui, San Fernando, La Union',     '09161234567', 'Married',  'Lawyer'],
        ];

        $patients = [];
        foreach ($patientData as $i => $pd) {
            // Use updateOrCreate to avoid duplicates on re-seeding
            $p = Patient::where('family_name', $pd[0])
                ->where('first_name', $pd[1])
                ->first();

            if (!$p) {
                $birthday = \Carbon\Carbon::parse($pd[3]);
                $age      = (int) $birthday->diffInYears(now());
                $isPedia  = $age < 12;

                // Manually set case_no to avoid sequence issues on re-seed
                $year   = now()->year;
                $seq    = str_pad($i + 1, 6, '0', STR_PAD_LEFT);
                $caseNo = "LUMC-{$year}-{$seq}";

                $p = Patient::create([
                    'case_no'       => $caseNo,
                    'family_name'   => $pd[0],
                    'first_name'    => $pd[1],
                    'middle_name'   => $pd[2],
                    'birthday'      => $pd[3],
                    'age'           => $age,
                    'sex'           => $pd[4],
                    'address'       => $pd[5],
                    'contact_number'=> $pd[6],
                    'civil_status'  => $pd[7],
                    'occupation'    => $pd[8],
                    'is_pedia'      => $isPedia,
                ]);
            }
            $patients[] = $p;
        }

        // ── 4. VISITS (today) ─────────────────────────────────────────────
        // Mix of: OPD Charity, OPD Private, ER Charity, ER Private
        // Mix of statuses: registered, vitals_done, assessed

        $gp       = $doctorUsers[0]; // General Practitioner
        $internist= $doctorUsers[1]; // Internal Medicine

        $visitScenarios = [
            // [patient_idx, type, payment_class, status, chief_complaint, assigned_doctor_id, brought_by, condition]
            [0, 'OPD', 'Charity',  'vitals_done', 'Fever and headache for 2 days',              null,           null,        null],
            [1, 'OPD', 'Charity',  'vitals_done', 'Cough with yellowish phlegm for 1 week',     null,           null,        null],
            [2, 'OPD', 'Charity',  'assessed',    'Hypertension follow-up, dizziness',           null,           null,        null],
            [3, 'OPD', 'Private',  'vitals_done', 'Abdominal pain, right lower quadrant',        $gp->id,        null,        null],
            [4, 'OPD', 'Charity',  'registered',  'Diabetes mellitus, routine check-up',         null,           null,        null],
            [5, 'OPD', 'Charity',  'vitals_done', 'Fever, runny nose, cough for 3 days',         null,           null,        null], // pedia
            [6, 'OPD', 'Private',  'vitals_done', 'Chest tightness, shortness of breath',        $internist->id, null,        null],
            [7, 'ER',  'Charity',  'vitals_done', 'Laceration on left hand after work accident', null,           'Family',    'Fair'],
            [8, 'OPD', 'Private',  'registered',  'Annual physical examination, general check-up',$gp->id,      null,        null],
            [9, 'ER',  'Charity',  'registered',  'Severe abdominal pain, vomiting',             null,           'Ambulance', 'Poor'],
        ];

        foreach ($visitScenarios as $vs) {
            [$pIdx, $type, $payClass, $status, $cc, $docId, $broughtBy, $condition] = $vs;
            $patient = $patients[$pIdx];

            // Skip if this patient already has a visit today (re-seed safe)
            $existing = Visit::where('patient_id', $patient->id)
                ->whereDate('registered_at', today())
                ->first();
            if ($existing) continue;

            $visit = Visit::create([
                'patient_id'           => $patient->id,
                'clerk_id'             => $clerk->id,
                'assigned_doctor_id'   => $docId,
                'visit_type'           => $type,
                'payment_class'        => $payClass,
                'chief_complaint'      => $cc,
                'brought_by'           => $broughtBy,
                'condition_on_arrival' => $condition,
                'status'               => $status,
                'registered_at'        => now()->subMinutes(rand(10, 120)),
            ]);

            // Add vitals for patients with status vitals_done or assessed
            if (in_array($status, ['vitals_done', 'assessed'])) {
                $isPedia = $patient->is_pedia;

                // Intentionally make some vitals abnormal for testing
                $isAbnormal = in_array($pIdx, [2, 6, 7]); // some patients have abnormal vitals

                Vital::create([
                    'visit_id'         => $visit->id,
                    'patient_id'       => $patient->id,
                    'recorded_by'      => $clerk->id,
                    'nurse_name'       => 'Nurse Ana Santos',
                    'temperature'      => $isAbnormal ? 38.9 : 36.8,        // 38.9 = febrile
                    'temperature_site' => 'Axilla',
                    'pulse_rate'       => $isAbnormal ? 112 : 82,            // 112 = tachycardic
                    'respiratory_rate' => $isAbnormal ? 24 : 18,             // 24 = tachypneic
                    'blood_pressure'   => $isPedia ? null : ($isAbnormal ? '150/100' : '120/80'),
                    'height_cm'        => $isPedia ? 115.0 : rand(155, 175),
                    'weight_kg'        => $isPedia ? 8.5 : rand(50, 80),     // pedia < 10kg
                    'o2_saturation'    => $isAbnormal ? 92 : 98,             // 92 = low O2
                    'pain_scale'       => $isAbnormal ? '7' : '2',
                    'notes'            => $isAbnormal ? 'Patient appears distressed' : null,
                    'taken_at'         => now()->subMinutes(rand(5, 60)),
                ]);

                $visit->update(['status' => $status]); // restore after vitals set it
            }
        }

        $this->command->info('✅ LUMC test data seeded successfully!');
        $this->command->newLine();
        $this->command->info('─── LOGIN CREDENTIALS ───────────────────────────');
        $this->command->info('  Clerk:    clerk@lumc.gov.ph     / password');
        $this->command->info('  Doctor:   doctor@lumc.gov.ph    / password  (GP)');
        $this->command->info('  Internist:internist@lumc.gov.ph / password');
        $this->command->info('  Nurse:    nurse@lumc.gov.ph     / password');
        $this->command->info('  Admin:    admin@lumc.gov.ph     / password');
        $this->command->newLine();
        $this->command->info('─── TEST SCENARIOS SEEDED ───────────────────────');
        $this->command->info('  OPD Charity (vitals done) × 3  → ready for doctor');
        $this->command->info('  OPD Charity (registered)  × 2  → need vitals first');
        $this->command->info('  OPD Private (vitals done)  × 2  → assigned to GP / Internist');
        $this->command->info('  ER  Charity (vitals done)  × 1  → abnormal vitals');
        $this->command->info('  ER  Charity (registered)   × 1  → need vitals');
        $this->command->info('  OPD Private (registered)   × 1  → need vitals');
        $this->command->info('  Patients with ABNORMAL vitals: Santos, Garcia, Magno');
        $this->command->newLine();
        $this->command->info('─── SWITCH USERS ─────────────────────────────────');
        $this->command->info('  http://127.0.0.1:8000/switch/clerk');
        $this->command->info('  http://127.0.0.1:8000/switch/doctor');
        $this->command->info('  http://127.0.0.1:8000/switch/nurse');
    }
}