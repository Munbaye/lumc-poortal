<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clerk_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_doctor_id')->nullable()->constrained('users')->nullOnDelete();

            // ── Updated visit_type to include NICU ────────────────────────────
            $table->enum('visit_type', ['OPD', 'ER', 'NICU'])->default('OPD');
            $table->text('chief_complaint');

            // ── Workflow status (updated for NICU provisional) ────────────────
            $table->enum('status', [
                'registered',           // Clerk registered patient
                'provisional_registration', // NICU: created by nurse, needs clerk
                'vitals_done',          // Vitals recorded
                'assessed',             // Doctor assessed — not admitted
                'discharged',           // Discharged / HAMA / Expired
                'admitted',             // Admitted to ward
                'referred',             // Referred out
            ])->default('registered');

            // ── Flag for provisional records ─────────────────────────────
            $table->boolean('is_provisionally_registered')->default(false);

            // ── For transferred babies ───────────────────────────────────
            $table->string('referring_facility')->nullable();
            $table->enum('admission_type', [
                'born_at_lumc',
                'transferred',
            ])->nullable();

            // ── Doctor sets on assessment ─────────────────────────────────────
            $table->text('admitting_diagnosis')->nullable();   // shown to clerk
            $table->string('admitted_service')->nullable();    // e.g. "NICU", "Internal Medicine"
            $table->enum('disposition', ['Discharged','Admitted','Referred','HAMA','Expired'])->nullable();
            $table->text('referral_notes')->nullable();

            // ── Clerk sets on Complete Admission ──────────────────────────────
            $table->enum('payment_class', ['Charity', 'Private'])->nullable();

            // ── ER-specific ───────────────────────────────────────────────────
            $table->string('brought_by')->nullable();
            $table->string('condition_on_arrival')->nullable();

            // ── Timestamps ───────────────────────────────────────────────────
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('discharged_at')->nullable();
            $table->timestamp('doctor_admitted_at')->nullable()
                  ->comment('Set by doctor when ADMIT decision is saved');
            $table->timestamp('clerk_admitted_at')->nullable()
                  ->comment('Set ONLY by clerk when CompleteAdmission is submitted');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['visit_type', 'status']);
            $table->index('registered_at');
            $table->index('doctor_admitted_at');
            $table->index('is_provisionally_registered');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};