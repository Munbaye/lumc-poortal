<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicu_admissions', function (Blueprint $table) {
            $table->id();
            
            // Core links
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filled_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Birth details
            $table->dateTime('date_time_of_birth')->nullable();
            $table->enum('type_of_delivery', [
                'NSD', 'CS', 'Forceps', 'Vacuum', 'Breech', 'Other'
            ])->nullable();
            $table->string('delivered_at')->nullable();
            $table->string('birth_attendant')->nullable();
            
            // Admission context
            $table->enum('admission_status', ['born_here', 'transferred'])->default('born_here');
            $table->string('referring_facility')->nullable();
            $table->text('reason_for_nicu_admission')->nullable();
            
            // Measurements
            $table->decimal('birth_weight_grams', 7, 2)->nullable();
            $table->decimal('birth_length_cm', 5, 1)->nullable();
            $table->decimal('head_circumference_cm', 5, 1)->nullable();
            $table->decimal('chest_circumference_cm', 5, 1)->nullable();
            $table->decimal('abdominal_circumference_cm', 5, 1)->nullable();
            
            // APGAR
            $table->unsignedTinyInteger('apgar_1min')->nullable();
            $table->unsignedTinyInteger('apgar_5min')->nullable();
            $table->unsignedTinyInteger('apgar_10min')->nullable();
            
            // Gestational age
            $table->unsignedTinyInteger('ga_by_dates_weeks')->nullable();
            $table->unsignedTinyInteger('ga_by_ballard_weeks')->nullable();
            $table->string('newborn_classification')->nullable();
            
            // Maternal history (NUR-022-0)
            $table->string('mother_name_raw')->nullable();
            $table->string('mother_age')->nullable();
            $table->unsignedTinyInteger('mother_gravida')->nullable();
            $table->unsignedTinyInteger('mother_para')->nullable();
            $table->enum('prenatal_checkup_site', ['LUMC', 'Health Center', 'Private Clinic', 'None'])->nullable();
            $table->unsignedTinyInteger('prenatal_visit_count')->nullable();
            $table->text('maternal_history')->nullable();
            $table->text('maternal_signs_symptoms')->nullable();
            $table->boolean('took_multivitamins')->nullable();
            $table->boolean('had_ultrasound')->nullable();
            $table->boolean('had_preterm_labor')->nullable();
            $table->string('steroids_given')->nullable();
            
            $table->timestamps();
            
            $table->index('visit_id');
            $table->index('patient_id');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('nicu_admissions');
    }
};