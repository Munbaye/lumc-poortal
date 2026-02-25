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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            // History
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('social_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            // Physical Exam
            $table->text('physical_exam')->nullable();
            // Assessment
            $table->text('diagnosis')->nullable();
            $table->text('differential_diagnosis')->nullable();
            $table->enum('disposition', ['Discharged','Admitted','Referred','HAMA','Expired'])->nullable();
            $table->string('admitted_ward')->nullable();
            $table->string('service')->nullable(); // e.g., Medicine, Surgery, Pedia
            $table->string('payment_type')->nullable(); // e.g., PhilHealth, Private, Indigent
            $table->text('plan')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
