<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Ward;
use Illuminate\Database\Seeder;

/**
 * WardRoomSeeder
 *
 * Seeds wards and rooms based on known room numbers from LUMC.
 * Run: php artisan db:seed --class=WardRoomSeeder
 */
class WardRoomSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ward 14 (service rooms + aisle) ──────────────────────────────────
        $this->seedWard('Ward 14', 'W14', [
            ['room_number' => '1418', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1421', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1424', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1425', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1426', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1427', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1432', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1433', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1434', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1435', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1436', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1437', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1438', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1444', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1448', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1450', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '1451', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => 'Aisle 1', 'classification' => 'aisle', 'bed_capacity' => 10],
        ]);

        // ── Ward 12 Private ───────────────────────────────────────────────────
        $this->seedWard('Ward 12 Private', 'W12P', [
            ['room_number' => '1218', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1224', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1225', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1227', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1232', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1233', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1234', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1235', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1236', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1237', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1238', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1239', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1250', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1251', 'classification' => 'private', 'bed_capacity' => 1],
            ['room_number' => '1288', 'classification' => 'private', 'bed_capacity' => 1],
        ]);

        // ── Ward 16 ───────────────────────────────────────────────────────────
        $this->seedWard('Ward 16', 'W16', [
            ['room_number' => '1416', 'classification' => 'service', 'bed_capacity' => 4],
        ]);

        // ── REPSI Ward ────────────────────────────────────────────────────────
        $this->seedWard('REPSI Ward', 'REPSI', [
            ['room_number' => 'REPSI 2801', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => 'REPSI 2802', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => 'REPSI 2803', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => 'REPSI 2804', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => 'REPSI 2805', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '2806',       'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '2807',       'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '2808',       'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '2809',       'classification' => 'service', 'bed_capacity' => 4],
        ]);

        // ── Pay Ward ──────────────────────────────────────────────────────────
        $this->seedWard('Pay Ward', 'PW', [
            ['room_number' => '2810', 'classification' => 'pay_ward', 'bed_capacity' => 4],
            ['room_number' => '2811', 'classification' => 'pay_ward', 'bed_capacity' => 4],
        ]);

        // ── Ward 26 ───────────────────────────────────────────────────────────
        $this->seedWard('Ward 26', 'W26', [
            ['room_number' => '2602', 'classification' => 'service', 'bed_capacity' => 4],
            ['room_number' => '2603', 'classification' => 'service', 'bed_capacity' => 4],
        ]);

        // ── General Aisles ────────────────────────────────────────────────────
        $this->seedWard('General Aisles', 'AISLE', [
            ['room_number' => 'Aisle A-K', 'classification' => 'aisle', 'bed_capacity' => 11],
            ['room_number' => 'Aisle L-U', 'classification' => 'aisle', 'bed_capacity' => 9],
            ['room_number' => 'Aisle 1-5', 'classification' => 'aisle', 'bed_capacity' => 5],
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    protected function seedWard(string $name, string $code, array $rooms): void
    {
        $ward = Ward::firstOrCreate(
            ['code' => $code],
            ['name' => $name, 'is_active' => true]
        );

        if ($ward->name !== $name) {
            $ward->update(['name' => $name]);
        }

        foreach ($rooms as $roomData) {
            $room = Room::firstOrCreate(
                ['ward_id' => $ward->id, 'room_number' => $roomData['room_number']],
                [
                    'classification'       => $roomData['classification'],
                    'bed_capacity'         => $roomData['bed_capacity'],
                    'is_aisle'             => $roomData['classification'] === 'aisle',
                    'is_under_maintenance' => false,
                    'is_active'            => true,
                ]
            );

            // Auto-create 1 bed for private rooms
            if ($room->classification === 'private') {
                Bed::firstOrCreate(
                    ['room_id' => $room->id, 'bed_label' => 'Bed 1'],
                    [
                        'ward_id'   => $ward->id,
                        'status'    => 'available',
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info("Seeded ward: {$name}");
    }
}