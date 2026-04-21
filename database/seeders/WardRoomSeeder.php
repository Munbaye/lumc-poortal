<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Visit;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardRoomSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌟 Seeding LUMC Wards, Rooms, Beds + Patient Assignment...');

        // Create Wards + Rooms + Beds
        $this->seedAllWards();

        // Assign already admitted patients to rooms/beds
        $this->assignPatientsToBeds();

        $this->command->info('✅ Wards, Rooms, Beds & Patient Assignments completed!');
    }

    private function seedAllWards(): void
    {
        $wardsData = [
            ['name' => 'Internal Medicine Ward',      'code' => 'IMW'],
            ['name' => 'Surgical Ward',               'code' => 'SURG'],
            ['name' => 'Pediatrics Ward',             'code' => 'PEDIA'],
            ['name' => 'OB-Gyne / Maternity Ward',    'code' => 'OBGY'],
            ['name' => 'Neonatal Intensive Care Unit','code' => 'NICU'],
            ['name' => 'Intensive Care Unit',         'code' => 'ICU'],
            ['name' => 'Isolation Ward',              'code' => 'ISOL'],
        ];

        foreach ($wardsData as $w) {
            $ward = Ward::firstOrCreate(
                ['code' => $w['code']],
                ['name' => $w['name'], 'is_active' => true]
            );

            $this->seedRoomsForWard($ward);
        }
    }

    private function seedRoomsForWard(Ward $ward): void
    {
        // Service Rooms (Charity) - max 4 beds
        $this->createRoomGroup($ward, 'service', [
            $ward->code . '01', $ward->code . '02', $ward->code . '03', $ward->code . '04'
        ], 4);

        // Pay Ward (Semi-Private) - max 4 beds
        $this->createRoomGroup($ward, 'pay', [
            'P-' . $ward->code . '01', 'P-' . $ward->code . '02'
        ], 4);

        // Private Rooms - 1 bed only
        $this->createRoomGroup($ward, 'private', [
            'PR-' . $ward->code . '01', 'PR-' . $ward->code . '02'
        ], 1);

        // Aisle (only in large wards)
        if (in_array($ward->code, ['IMW', 'PEDIA', 'SURG'])) {
            $this->createRoomGroup($ward, 'aisle', [
                'Aisle-1', 'Aisle-2'
            ], 15);
        }
    }

    private function createRoomGroup(Ward $ward, string $classification, array $roomNumbers, int $bedCapacity): void
    {
        foreach ($roomNumbers as $roomNo) {
            $room = Room::firstOrCreate(
                ['ward_id' => $ward->id, 'room_number' => $roomNo],
                [
                    'classification' => $classification,
                    'bed_capacity'   => $bedCapacity,
                    'is_active'      => true,
                ]
            );

            for ($i = 1; $i <= $bedCapacity; $i++) {
                Bed::firstOrCreate(
                    ['room_id' => $room->id, 'bed_label' => "Bed {$i}"],
                    ['ward_id' => $ward->id, 'status' => 'available']
                );
            }
        }
    }

    /**
     * Assign currently admitted patients to available beds
     */
    private function assignPatientsToBeds(): void
    {
        $admittedVisits = Visit::where('status', 'admitted')
            ->with('patient')
            ->get();

        foreach ($admittedVisits as $visit) {
            $classification = match ($visit->payment_class) {
                'Private' => 'private',
                default   => 'service',   // Charity & others go to service first
            };

            // Find available room
            $room = Room::where('classification', $classification)
                        ->where('is_active', true)
                        ->first();

            // Fallback to service if preferred type not available
            if (!$room) {
                $room = Room::where('classification', 'service')
                            ->where('is_active', true)
                            ->first();
            }

            if ($room) {
                $bed = Bed::where('room_id', $room->id)
                           ->where('status', 'available')
                           ->first();

                if ($bed) {
                    $bed->update([
                        'status'    => 'occupied',
                        'visit_id'  => $visit->id,
                    ]);

                    $this->command->info("   Assigned {$visit->patient->full_name} → {$room->room_number} ({$bed->bed_label})");
                }
            }
        }
    }
}