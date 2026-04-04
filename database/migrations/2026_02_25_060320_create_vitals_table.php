<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nurse_name');

            $table->decimal('temperature', 4, 1)->nullable();
            $table->enum('temperature_site', ['Axilla','Oral','Rectal'])->nullable();

            $table->unsignedSmallInteger('pulse_rate')->nullable();

            $table->unsignedSmallInteger('cardiac_rate')
                ->nullable()
                ->comment('Cardiac rate (bpm) — used when distinct from pulse rate');

            $table->unsignedSmallInteger('respiratory_rate')->nullable();
            $table->string('blood_pressure')->nullable();

            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();

            $table->unsignedTinyInteger('o2_saturation')->nullable();
            $table->tinyInteger('pain_scale')->nullable();

            $table->text('neurological_vs')
                ->nullable()
                ->comment('GCS, pupil response, motor response, LOC, etc.');

            $table->text('others_vs')
                ->nullable()
                ->comment('Other observations like urine output, IV site, wounds, etc.');

            $table->text('notes')->nullable();

            $table->timestamp('taken_at')->useCurrent();
            $table->timestamps();

            $table->index('visit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};