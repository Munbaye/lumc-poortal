<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saved_by')->nullable()->constrained('users')->nullOnDelete();

            // Which section was used (1 = patient signs, 2 = guardian signs)
            $table->unsignedTinyInteger('active_section')->default(1);

            // ── Section 1 — Patient consent ─────────────────────────────────
            $table->string('patient_name')->nullable();
            $table->string('doctor_name_sec1')->nullable();
            $table->string('witness_sec1')->nullable();
            $table->string('signed_date_sec1')->nullable();

            // ── Section 2 — Guardian / Next-of-kin consent ──────────────────
            $table->string('guardian_name')->nullable();
            $table->string('nok_sig_name')->nullable();
            $table->string('being_the')->nullable();
            $table->string('doctor_name_sec2')->nullable();
            $table->string('witness_sec2')->nullable();
            $table->string('signed_date_sec2')->nullable();
            $table->string('relation_to_patient')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_records');
    }
};