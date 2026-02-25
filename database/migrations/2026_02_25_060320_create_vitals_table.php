<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete(); // clerk user
            $table->string('nurse_name'); // free text — handles interns/volunteers
            $table->decimal('temperature', 4, 1)->nullable(); // in Celsius
            $table->enum('temperature_site', ['Axilla','Oral','Rectal'])->nullable();
            $table->integer('pulse_rate')->nullable(); // bpm
            $table->integer('respiratory_rate')->nullable(); // breaths/min
            $table->string('blood_pressure')->nullable(); // "120/80" — hidden if pedia
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->integer('o2_saturation')->nullable(); // %
            $table->string('pain_scale')->nullable(); // 0-10
            $table->text('notes')->nullable();
            $table->timestamp('taken_at')->useCurrent();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};
