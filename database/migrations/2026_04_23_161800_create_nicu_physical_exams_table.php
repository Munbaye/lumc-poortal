<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicu_physical_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('examined_by')->nullable()->constrained('users')->nullOnDelete();

            // Exam metadata
            $table->date('exam_date')->nullable();
            $table->integer('hours_after_birth')->nullable();

            // APGAR
            $table->unsignedTinyInteger('apgar_birth')->nullable();
            $table->unsignedTinyInteger('apgar_5min')->nullable();
            $table->unsignedTinyInteger('apgar_10min')->nullable();

            // General
            $table->text('general_condition')->nullable();

            // Measurements
            $table->decimal('head_circumference_cm', 5, 1)->nullable();
            $table->decimal('chest_circumference_cm', 5, 1)->nullable();
            $table->decimal('abdominal_circumference_cm', 5, 1)->nullable();
            $table->decimal('birth_weight_g', 7, 2)->nullable();
            $table->decimal('birth_length_cm', 5, 1)->nullable();

            // Neuromuscular
            $table->string('general_muscular_tonus')->nullable();

            // Skin
            $table->string('skin_color')->nullable();
            $table->string('skin_turgor')->nullable();
            $table->string('skin_rash')->nullable();
            $table->string('skin_desquamation')->nullable();

            // Head
            $table->string('head_molding')->nullable();
            $table->string('head_scalp')->nullable();
            $table->string('head_fontanelles')->nullable();
            $table->string('head_suture')->nullable();

            // Face
            $table->string('face')->nullable();

            // Eyes
            $table->string('eyes_conjunctiva')->nullable();
            $table->string('eyes_sclera')->nullable();
            $table->string('eyes_pupils')->nullable();
            $table->string('eyes_discharge')->nullable();

            // Ears
            $table->string('ears')->nullable();

            // Nose
            $table->string('nose')->nullable();

            // Mouth
            $table->string('mouth_lip')->nullable();
            $table->string('mouth_tongue')->nullable();
            $table->string('mouth_palate')->nullable();

            // Neck
            $table->string('neck_sternocleidomastoid')->nullable();
            $table->string('neck_fistula')->nullable();
            $table->string('neck_other')->nullable();

            // Chest
            $table->string('chest_shape')->nullable();
            $table->string('chest_respiration')->nullable();
            $table->string('chest_clavicles')->nullable();
            $table->string('chest_breast')->nullable();
            $table->string('chest_heart')->nullable();
            $table->string('chest_lungs')->nullable();

            // Abdomen
            $table->text('abdomen')->nullable();

            // Internal organs
            $table->string('spleen')->nullable();
            $table->string('kidneys')->nullable();
            $table->string('liver')->nullable();
            $table->string('umbilical_cord')->nullable();

            // Hernia
            $table->string('inguinal_hernia')->nullable();
            $table->string('diastasis_recti')->nullable();

            // Genitals
            $table->string('genitals_male')->nullable();
            $table->string('genitals_female')->nullable();

            // Extremities
            $table->text('extremities')->nullable();

            // Orthopaedic
            $table->string('clubfoot')->nullable();
            $table->string('hip_dislocation')->nullable();
            $table->string('femoral_pulse')->nullable();
            $table->string('spine')->nullable();
            $table->string('anus')->nullable();

            // Impression
            $table->text('impression')->nullable();

            // Signature
            $table->string('pediatrician_name')->nullable();
            $table->text('pediatrician_signature')->nullable();

            $table->timestamps();

            $table->index('visit_id');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nicu_physical_exams');
    }
};