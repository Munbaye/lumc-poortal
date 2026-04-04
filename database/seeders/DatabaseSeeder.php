<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StaffSeeder::class,
            VisitSeeder::class,   // creates patients + all visits + clinical records
            LabRadSeeder::class,  // adds demo completed lab/rad results
        ]);

        $this->command->info('');
        $this->command->info('✅  LUMC Portal seeded successfully!');
        $this->command->info('   All passwords: password');
        $this->command->line('');
        $this->command->table(
            ['Role', 'Email'],
            [
                ['Admin',              'admin@lumc.gov.ph'],
                ['Doctor (Int. Med)',  'doctor@lumc.gov.ph'],
                ['Doctor (Pediatrics)','mreyes@lumc.gov.ph'],
                ['Doctor (Surgery)',   'jdelacruz@lumc.gov.ph'],
                ['Doctor (OB-Gyne)',   'amendoza@lumc.gov.ph'],
                ['Doctor (Emergency)', 'rbautista@lumc.gov.ph'],
                ['Doctor (Cardiology)','lcastillo@lumc.gov.ph'],
                ['Nurse',              'nurse@lumc.gov.ph'],
                ['Nurse',              'btorres@lumc.gov.ph'],
                ['Nurse',              'cramos@lumc.gov.ph'],
                ['Clerk',              'clerk@lumc.gov.ph'],
                ['Clerk',              'maquino@lumc.gov.ph'],
                ['MedTech',            'medtech@lumc.gov.ph'],
                ['RadTech',            'radtech@lumc.gov.ph'],
                ['Tech (generic)',     'tech@lumc.gov.ph'],
                ['Patient portal',     'liza.bautista@email.com'],
                ['Patient portal',     'ramon.mendoza@email.com'],
            ]
        );
    }
}