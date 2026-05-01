<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')
                  ->unique()
                  ->constrained('visits')
                  ->cascadeOnDelete();

            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->cascadeOnDelete();

            $table->foreignId('written_by')          // doctor who filled the form
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Demographics (auto-filled, stored for historical accuracy) ───────
            $table->string('patient_family_name')->nullable();
            $table->string('patient_first_name')->nullable();
            $table->string('patient_middle_name')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('telephone_no', 60)->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('civil_status', 40)->nullable();
            $table->string('hospital_case_no', 60)->nullable();
            $table->string('ward_service', 120)->nullable();

            // ── Admission / Discharge dates ───────────────────────────────────────
            $table->dateTime('date_admitted')->nullable();
            $table->dateTime('date_discharged')->nullable();

            // ── Clinical fields ───────────────────────────────────────────────────
            $table->string('attending_physician')->nullable();
            $table->text('admitting_diagnosis')->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->text('chief_complaints')->nullable();
            $table->text('brief_clinical_history')->nullable();  // "Brief Clinical History And Pertinent P.E."
            $table->text('laboratory_findings')->nullable();     // "Laboratory Findings including EKG, X-ray..."
            $table->text('course_in_ward')->nullable();          // "Course in the Ward (Include medications)"
            $table->text('disposition')->nullable();             // "Disposition (home medication, special instruction...)"

            // ── Status flags ──────────────────────────────────────────────────────
            $table->boolean('is_finalized')->default(false);
            $table->dateTime('finalized_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discharge_summaries');
    }
};