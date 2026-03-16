<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('case_no')->unique();

            // ── Identity ──────────────────────────────────────────────────────
            $table->string('family_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('birthplace')->nullable();
            $table->unsignedTinyInteger('age')->nullable(); // auto-calculated from birthday
            $table->enum('sex', ['Male', 'Female']);
            $table->string('nationality')->nullable()->default('Filipino');
            $table->string('religion')->nullable();

            // ── Contact & Address ─────────────────────────────────────────────
            $table->text('address');
            $table->string('contact_number')->nullable();

            // ── Personal ──────────────────────────────────────────────────────
            $table->string('occupation')->nullable();
            $table->enum('civil_status', ['Single','Married','Widowed','Separated','Annulled'])->nullable();
            $table->string('spouse_name')->nullable();

            // ── Employer (filled at admission) ────────────────────────────────
            $table->string('employer_name')->nullable();
            $table->text('employer_address')->nullable();
            $table->string('employer_phone')->nullable();

            // ── Father ────────────────────────────────────────────────────────
            $table->string('father_name')->nullable();  // from registration (brief)
            $table->string('father_full_name')->nullable(); // from admission (full)
            $table->text('father_address')->nullable();
            $table->string('father_phone')->nullable();

            // ── Mother ────────────────────────────────────────────────────────
            $table->string('mother_name')->nullable();  // from registration (brief)
            $table->string('mother_maiden_name')->nullable(); // from admission
            $table->text('mother_address')->nullable();
            $table->string('mother_phone')->nullable();

            // ── PhilHealth & Social Service (filled at admission) ─────────────
            $table->string('philhealth_id')->nullable();
            $table->enum('philhealth_type', ['Government','Indigent','Private','Self-Employed'])->nullable();
            $table->enum('social_service_class', ['A','B','C1','C2','C3','D'])->nullable();

            // ── ER-specific ───────────────────────────────────────────────────
            $table->enum('registration_type', ['OPD','ER'])->default('OPD');
            $table->enum('brought_by', ['Self','Family','Ambulance','Police','Other'])->nullable();
            $table->enum('condition_on_arrival', ['Good','Fair','Poor','Shock','Comatose','Hemorrhagic','DOA'])->nullable();

            // ── Flags ─────────────────────────────────────────────────────────
            $table->boolean('is_pedia')->default(false);
            $table->boolean('has_incomplete_info')->default(false);
            $table->boolean('is_unknown')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['family_name', 'first_name']);
            $table->index('birthday');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};