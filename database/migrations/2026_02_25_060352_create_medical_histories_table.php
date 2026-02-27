<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();

            // ── NUR-006: Medical History ──────────────────────────────────────
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('occupation_environment')->nullable();
            $table->text('drug_allergies')->nullable();
            $table->text('drug_therapy')->nullable();
            $table->text('other_allergies')->nullable();

            // ── NUR-005: Physical Examination ─────────────────────────────────
            $table->text('pe_skin')->nullable();
            $table->text('pe_head_eent')->nullable();
            $table->text('pe_lymph_nodes')->nullable();
            $table->text('pe_chest')->nullable();
            $table->text('pe_lungs')->nullable();
            $table->text('pe_cardiovascular')->nullable();
            $table->text('pe_breast')->nullable();
            $table->text('pe_abdomen')->nullable();
            $table->text('pe_rectum')->nullable();
            $table->text('pe_genitalia')->nullable();
            $table->text('pe_musculoskeletal')->nullable();
            $table->text('pe_extremities')->nullable();
            $table->text('pe_neurology')->nullable();

            // ── Assessment ────────────────────────────────────────────────────
            $table->text('admitting_impression')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('differential_diagnosis')->nullable();
            $table->text('plan')->nullable();

            // ── Disposition (mirrors visits) ──────────────────────────────────
            $table->enum('disposition', ['Discharged','Admitted','Referred','HAMA','Expired'])->nullable();
            $table->string('admitted_ward')->nullable();
            $table->string('service')->nullable();
            $table->string('payment_type')->nullable(); // mirrors visits.payment_class

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};