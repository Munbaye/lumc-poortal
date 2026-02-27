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

            // Set by doctor on assessment ONLY when admitted + Private
            $table->foreignId('assigned_doctor_id')->nullable()->constrained('users')->nullOnDelete();

            // Entry point — set automatically at registration
            // OPD clerk → OPD, ER clerk → ER
            $table->enum('visit_type', ['OPD', 'ER'])->default('OPD');

            $table->text('chief_complaint');

            // ── Workflow status ───────────────────────────────────────────────
            $table->enum('status', [
                'registered',   // Clerk registered the patient
                'vitals_done',  // Nurse recorded vitals
                'assessed',     // Doctor assessed — outpatient (not admitted)
                'discharged',   // Discharged/HAMA/Expired
                'admitted',     // Doctor admitted to ward
                'referred',     // Referred to another facility
            ])->default('registered');

            // ── Set by doctor during assessment, ONLY when admitting ──────────
            // NULL = not yet assessed / not admitted
            $table->enum('payment_class', ['Charity', 'Private'])->nullable();
            $table->enum('disposition', ['Discharged','Admitted','Referred','HAMA','Expired'])->nullable();
            $table->string('admitted_ward')->nullable();
            $table->string('admitted_service')->nullable();

            // ── ER-specific ───────────────────────────────────────────────────
            $table->string('brought_by')->nullable();
            $table->string('condition_on_arrival')->nullable();

            $table->text('referral_notes')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('discharged_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['visit_type', 'status']);
            $table->index('registered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};