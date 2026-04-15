<?php
// File: app/Filament/Admin/Resources/RoomResource/Pages/CreateRoom.php

namespace App\Filament\Admin\Resources\RoomResource\Pages;

use App\Filament\Admin\Resources\RoomResource;
use App\Models\Bed;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * After creating a private room, auto-create its single bed.
     */
    protected function afterCreate(): void
    {
        $room = $this->record;

        if ($room->classification === 'private') {
            // Auto-create 1 bed for private rooms
            Bed::firstOrCreate(
                ['room_id' => $room->id, 'bed_label' => 'Bed 1'],
                [
                    'ward_id'   => $room->ward_id,
                    'bed_label' => 'Bed 1',
                    'status'    => 'available',
                    'is_active' => true,
                ]
            );
        }
    }
}