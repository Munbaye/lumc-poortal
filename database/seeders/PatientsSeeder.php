<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\UnknownPatientSequence;
use Illuminate\Database\Seeder;

class PatientsSeeder extends Seeder
{
    /**
     * Enum reference (from migrations):
     *
     * patients.registration_type : 'OPD' | 'ER'
     * patients.sex                : 'Male' | 'Female'
     * patients.civil_status       : 'Single' | 'Married' | 'Widowed' | 'Separated' | 'Annulled'
     * patients.philhealth_type    : 'Government' | 'Indigent' | 'Private' | 'Self-Employed'
     * patients.social_service_class: 'A' | 'B' | 'C1' | 'C2' | 'C3' | 'D'
     * patients.brought_by         : 'Self' | 'Family' | 'Ambulance' | 'Police' | 'Other'
     * patients.condition_on_arrival: 'Good' | 'Fair' | 'Poor' | 'Shock' | 'Comatose' | 'Hemorrhagic' | 'DOA'
     */
    public function run(): void
    {
        $patients = [

            // 1 — Adult female, repeat visitor (Charity)
            [
                'family_name'         => 'Dela Cruz',
                'first_name'          => 'Maria',
                'middle_name'         => 'Santos',
                'birthday'            => '1978-03-15',
                'sex'                 => 'Female',
                'address'             => 'Brgy. Bungro, San Fernando, La Union',
                'contact_number'      => '09171234501',
                'civil_status'        => 'Married',
                'occupation'          => 'Housewife',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',      // ✓ enum: 'OPD'|'ER'
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'philhealth_id'       => '12-345678901-1',
                'philhealth_type'     => 'Government', // ✓ enum: 'Government'|'Indigent'|'Private'|'Self-Employed'
                'religion'            => 'Roman Catholic',
                'social_service_class'=> 'C1',        // ✓ enum: 'A'|'B'|'C1'|'C2'|'C3'|'D'
            ],

            // 2 — Adult male, multiple admissions (Private)
            [
                'family_name'         => 'Reyes',
                'first_name'          => 'Eduardo',
                'middle_name'         => 'Bautista',
                'birthday'            => '1955-11-22',
                'sex'                 => 'Male',
                'address'             => '14 Rizal St., Agoo, La Union',
                'contact_number'      => '09281234502',
                'civil_status'        => 'Married',
                'occupation'          => 'Retired',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'philhealth_id'       => '12-345678902-2',
                'philhealth_type'     => 'Private',   // ✓ private-paying patient
                'religion'            => 'Roman Catholic',
                'employer_name'       => 'Self-employed (retired)',
                'social_service_class'=> 'B',
            ],

            // 3 — Pediatric patient (Charity, pedia)
            [
                'family_name'         => 'Torres',
                'first_name'          => 'Miguel',
                'middle_name'         => 'Andres',
                'birthday'            => '2019-07-04',
                'sex'                 => 'Male',
                'address'             => 'Brgy. Caarosipan, Agoo, La Union',
                'contact_number'      => '09331234503',
                'civil_status'        => 'Single',
                'occupation'          => 'Student',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'religion'            => 'Roman Catholic',
                'social_service_class'=> 'C2',
            ],

            // 4 — Adult female, repeat visitor (Indigent/Charity)
            [
                'family_name'         => 'Villanueva',
                'first_name'          => 'Rosario',
                'middle_name'         => 'Hernandez',
                'birthday'            => '1962-05-30',
                'sex'                 => 'Female',
                'address'             => 'Brgy. Ilocanos Norte, San Fernando, La Union',
                'contact_number'      => '09151234504',
                'civil_status'        => 'Widowed',
                'occupation'          => 'Vendor',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'philhealth_id'       => '12-345678904-4',
                'philhealth_type'     => 'Indigent',  // ✓ enum value
                'social_service_class'=> 'D',
            ],

            // 5 — Adult male, incomplete info (Charity)
            [
                'family_name'         => 'Castillo',
                'first_name'          => 'Roberto',
                'middle_name'         => null,
                'birthday'            => '1985-08-12',
                'sex'                 => 'Male',
                'address'             => 'Unknown (transient)',
                'contact_number'      => null,
                'civil_status'        => 'Single',
                'occupation'          => 'Construction Worker',
                'nationality'         => 'Filipino',
                'registration_type'   => 'ER',        // ✓ walk-in ER
                'has_incomplete_info' => true,
                'is_unknown'          => false,
                'brought_by'          => 'Self',      // ✓ enum: 'Self'|'Family'|'Ambulance'|'Police'|'Other'
                'condition_on_arrival'=> 'Good',      // ✓ enum: 'Good'|'Fair'|'Poor'|'Shock'|'Comatose'|'Hemorrhagic'|'DOA'
                'social_service_class'=> 'D',
            ],

            // 6 — Young adult female, Private
            [
                'family_name'         => 'Aquino',
                'first_name'          => 'Jennifer',
                'middle_name'         => 'Pascual',
                'birthday'            => '1995-01-18',
                'sex'                 => 'Female',
                'address'             => '22 Burgos St., Bauang, La Union',
                'contact_number'      => '09491234506',
                'civil_status'        => 'Single',
                'occupation'          => 'Teacher',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'philhealth_id'       => '12-345678906-6',
                'philhealth_type'     => 'Self-Employed', // ✓ enum value (closest to employed teacher)
                'employer_name'       => 'DepEd La Union',
                'social_service_class'=> 'B',
            ],

            // 7 — Elderly male, multiple admissions (Charity)
            [
                'family_name'         => 'Mendoza',
                'first_name'          => 'Ernesto',
                'middle_name'         => 'Luzon',
                'birthday'            => '1945-09-28',
                'sex'                 => 'Male',
                'address'             => 'Brgy. Poblacion, Sto. Tomas, La Union',
                'contact_number'      => '09081234507',
                'civil_status'        => 'Married',
                'occupation'          => 'Farmer (retired)',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => false,
                'is_unknown'          => false,
                'philhealth_id'       => '12-345678907-7',
                'philhealth_type'     => 'Indigent',  // ✓ enum value
                'social_service_class'=> 'D',
            ],

            // 8 — Adult female, incomplete info (Charity)
            [
                'family_name'         => 'Estrada',
                'first_name'          => 'Lina',
                'middle_name'         => 'Ocampo',
                'birthday'            => '1973-12-03',
                'sex'                 => 'Female',
                'address'             => 'Brgy. Catbangen, San Fernando, La Union',
                'contact_number'      => '09201234508',
                'civil_status'        => 'Separated',
                'occupation'          => 'Laundrywoman',
                'nationality'         => 'Filipino',
                'registration_type'   => 'OPD',
                'has_incomplete_info' => true,
                'is_unknown'          => false,
                'social_service_class'=> 'D',
            ],

        ];

        foreach ($patients as $data) {
            Patient::firstOrCreate(
                [
                    'family_name' => $data['family_name'],
                    'first_name'  => $data['first_name'],
                    'birthday'    => $data['birthday'] ?? null,
                ],
                $data
            );
        }

        // ── Unknown patient via UnknownPatientSequence ─────────────────────────
        // sex enum is 'Male'|'Female' — unknown patients must use one of these.
        // We default to 'Female' as a neutral placeholder; the is_unknown flag
        // communicates the real situation. The clerk will update when identified.
        $year = 2026;
        $seq  = UnknownPatientSequence::nextForYear($year);
        Patient::firstOrCreate(
            ['is_unknown' => true, 'family_name' => 'Unknown'],
            [
                'family_name'         => 'Unknown',
                'first_name'          => 'Patient-' . str_pad($seq, 4, '0', STR_PAD_LEFT),
                'birthday'            => null,
                'sex'                 => 'Female',    // ✓ enum: 'Male'|'Female' — no 'Unknown' option
                'address'             => 'Unknown',
                'has_incomplete_info' => true,
                'is_unknown'          => true,
                'registration_type'   => 'ER',        // ✓ enum: 'OPD'|'ER'
                'brought_by'          => 'Ambulance', // ✓ enum value
                'condition_on_arrival'=> 'Poor',      // ✓ enum value
            ]
        );

        $total = Patient::count();
        $this->command->info("✅ {$total} patients seeded (including 1 unknown).");
    }
}