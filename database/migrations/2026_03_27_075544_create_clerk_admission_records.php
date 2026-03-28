<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Add ER-level fields to visits ─────────────────────────────────
        Schema::table('visits', function (Blueprint $table) {
            $table->string('type_of_service')->nullable()->after('visit_type');
            $table->boolean('medico_legal')->default(false)->after('type_of_service');
            $table->string('notified_proper_authority')->nullable()->after('medico_legal'); // yes|no|na
        });

        // ── ER Record (ER-001) ────────────────────────────────────────────
        Schema::create('er_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filled_by')->nullable()->constrained('users')->nullOnDelete();

            // Header
            $table->string('health_record_no')->nullable();
            $table->string('type_of_service')->nullable();
            $table->boolean('medico_legal')->default(false);
            $table->string('case_type')->nullable();                // ER | Non-ER
            $table->string('notified_proper_authority')->nullable();// yes | no | na

            // Patient demographics (clerk can correct typos)
            $table->string('patient_family_name')->nullable();
            $table->string('patient_first_name')->nullable();
            $table->string('patient_middle_name')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('nationality')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();

            // Employer
            $table->string('employer_name')->nullable();
            $table->string('employer_phone')->nullable();

            // Registration
            $table->date('registration_date')->nullable();
            $table->time('registration_time')->nullable();

            // Brought by (checkboxes → store as string)
            $table->string('brought_by')->nullable();

            // Conditions on arrival
            $table->string('condition_on_arrival')->nullable();

            // Vitals (denormalized for the form)
            $table->decimal('temperature', 4, 1)->nullable();
            $table->string('temperature_site')->nullable();
            $table->unsignedSmallInteger('pulse_rate')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->unsignedSmallInteger('cardiac_rate')->nullable();
            $table->unsignedSmallInteger('respiratory_rate')->nullable();
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();

            // Clinical
            $table->text('chief_complaint')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('physical_findings_and_diagnosis')->nullable();
            $table->text('treatment')->nullable();

            // Disposition
            $table->date('disposition_date')->nullable();
            $table->time('disposition_time')->nullable();
            $table->string('disposition')->nullable();
            $table->text('condition_on_discharge')->nullable();

            $table->timestamps();
        });

        // ── Admission & Discharge Record (ADM-001) ────────────────────────
        Schema::create('admission_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filled_by')->nullable()->constrained('users')->nullOnDelete();

            // Patient (clerk-correctable copies)
            $table->string('patient_family_name')->nullable();
            $table->string('patient_first_name')->nullable();
            $table->string('patient_middle_name')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();

            // Demographics
            $table->date('birthdate')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();

            // Employer
            $table->string('employer_name')->nullable();
            $table->text('employer_address')->nullable();
            $table->string('employer_phone')->nullable();

            // Father
            $table->string('father_name')->nullable();
            $table->text('father_address')->nullable();
            $table->string('father_phone')->nullable();

            // Mother
            $table->string('mother_maiden_name')->nullable();
            $table->text('mother_address')->nullable();
            $table->string('mother_phone')->nullable();

            // Admission
            $table->date('admission_date')->nullable();
            $table->time('admission_time')->nullable();
            $table->date('discharge_date')->nullable();
            $table->time('discharge_time')->nullable();
            $table->unsignedSmallInteger('total_days')->nullable();
            $table->string('ward_service')->nullable();

            // Type & class
            $table->string('type_of_admission')->nullable(); // New | Old
            $table->string('social_service_class')->nullable();
            $table->string('payment_class')->nullable();

            // Alert & health insurance
            $table->string('alert')->nullable();
            $table->text('allergic_to')->nullable();
            $table->string('health_insurance_name')->nullable();

            // PhilHealth
            $table->string('philhealth_id')->nullable();
            $table->string('philhealth_type')->nullable();

            // Data furnished by
            $table->string('data_furnished_by')->nullable();
            $table->text('data_furnished_address')->nullable();
            $table->string('data_furnished_relation')->nullable();

            // Diagnoses
            $table->text('admission_diagnosis')->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->text('other_diagnosis')->nullable();
            $table->text('principal_operation')->nullable();

            // Disposition & results
            $table->string('disposition')->nullable();
            $table->string('results')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_records');
        Schema::dropIfExists('er_records');

        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['type_of_service', 'medico_legal', 'notified_proper_authority']);
        });
    }
};