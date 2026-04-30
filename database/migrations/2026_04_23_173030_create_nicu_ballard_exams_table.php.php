<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicu_ballard_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('examiner_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Exam number (1st exam = X, 2nd exam = O)
            $table->unsignedTinyInteger('exam_number')->default(1); // 1 or 2
            $table->dateTime('exam_datetime')->nullable();
            $table->unsignedSmallInteger('age_at_exam_hours')->nullable(); // Calculated
            
            // ── Neuromuscular Maturity (each 0–5) ─────────────────────────────
            $table->unsignedTinyInteger('nm_posture')->nullable();           // 0-5
            $table->unsignedTinyInteger('nm_square_window')->nullable();     // 0-5
            $table->unsignedTinyInteger('nm_arm_recoil')->nullable();        // 0-5
            $table->unsignedTinyInteger('nm_popliteal_angle')->nullable();   // 0-5
            $table->unsignedTinyInteger('nm_scarf_sign')->nullable();        // 0-5
            $table->unsignedTinyInteger('nm_heel_to_ear')->nullable();       // 0-5
            
            // ── Physical Maturity (each 0–5) ──────────────────────────────────
            $table->unsignedTinyInteger('pm_skin')->nullable();              // 0-5
            $table->unsignedTinyInteger('pm_lanugo')->nullable();            // 0-5
            $table->unsignedTinyInteger('pm_plantar_surface')->nullable();   // 0-5
            $table->unsignedTinyInteger('pm_breast')->nullable();            // 0-5
            $table->unsignedTinyInteger('pm_eye_ear')->nullable();           // 0-5
            $table->unsignedTinyInteger('pm_genitals')->nullable();          // 0-5
            
            // ── Calculated fields ─────────────────────────────────────────────
            $table->unsignedSmallInteger('total_score')->nullable();         // Sum of all 12 criteria
            $table->unsignedTinyInteger('estimated_ga_weeks')->nullable();   // Lookup from score
            
            $table->timestamps();
            
            // Unique constraint: only one exam number per visit
            $table->unique(['visit_id', 'exam_number']);
            $table->index('visit_id');
            $table->index('patient_id');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('nicu_ballard_exams');
    }
};