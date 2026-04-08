<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Wards ─────────────────────────────────────────────────────────────
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // e.g. "Ward A", "Floor 14", "REPSI"
            $table->string('code')->nullable();            // short code if needed
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Rooms ─────────────────────────────────────────────────────────────
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->cascadeOnDelete();

            $table->string('room_number');                 // e.g. "1436", "REPSI 2805", "Aisle 1"

            $table->string('classification')->default('service');
            // service | pay_ward | private | aisle

            $table->boolean('is_aisle')->default(false);  // convenience flag for aisle-type rooms
            $table->boolean('is_under_maintenance')->default(false);
            $table->text('maintenance_notes')->nullable(); // optional reason for maintenance

            $table->unsignedSmallInteger('bed_capacity')->default(1);
            // private rooms: always 1 (enforced in model/resource)
            // aisle: flexible
            // service/pay_ward: nurse manages

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['ward_id', 'room_number']);
        });

        // ── Beds ──────────────────────────────────────────────────────────────
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained()->cascadeOnDelete(); // denormalized for easy queries

            $table->string('bed_label');                  // e.g. "Bed A", "Bed 1", "Aisle Bed 3"

            $table->string('status')->default('available');
            // available | occupied | maintenance

            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            // null = unoccupied

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['room_id', 'bed_label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('wards');
    }
};