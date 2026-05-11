<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ob_records', function (Blueprint $table) {
            $table->id();

            // ── Core links ────────────────────────────────────────────────────
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filled_by')->nullable()->constrained('users')->nullOnDelete();

            // ── Obstetric History ─────────────────────────────────────────────
            $table->unsignedTinyInteger('gravida')->nullable();
            $table->unsignedTinyInteger('para')->nullable();
            $table->unsignedTinyInteger('term')->nullable();
            $table->unsignedTinyInteger('preterm')->nullable();
            $table->unsignedTinyInteger('abortion')->nullable();
            $table->unsignedTinyInteger('living')->nullable();

            // ── Menstrual History ─────────────────────────────────────────────
            $table->string('menarche')->nullable();              // e.g. "13 years old"
            $table->string('menses_interval')->nullable();       // e.g. "28 days"
            $table->string('menses_duration')->nullable();       // e.g. "4-5 days"
            $table->boolean('dysmenorrhea')->default(false);

            // ── Prenatal ──────────────────────────────────────────────────────
            $table->enum('prenatal_checkup_type', [
                'LUMC', 'Health Center', 'Private Clinic', 'None', 'Others',
            ])->nullable();
            $table->string('prenatal_checkup_others')->nullable(); // if 'Others'
            $table->unsignedTinyInteger('prenatal_visit_count')->nullable();

            // ── Past Medical & Family History ─────────────────────────────────
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();

            // ── Present Pregnancy Dates ───────────────────────────────────────
            $table->date('lmp')->nullable();   // Last Menstrual Period
            $table->date('pmp')->nullable();   // Previous Menstrual Period
            $table->date('edc')->nullable();   // Estimated Date of Confinement
            $table->string('aog')->nullable(); // Age of Gestation, e.g. "38 weeks 2 days"
            $table->date('quickening_date')->nullable();

            // ── Symptoms & Signs ──────────────────────────────────────────────
            $table->enum('morning_sickness', ['None', 'Mild', 'Moderate', 'Severe'])->nullable();
            $table->json('abnormal_symptoms')->nullable(); // array of strings
            $table->json('edema')->nullable();             // array: ['feet','hands','face']
            $table->text('other_symptoms')->nullable();

            // ── Contractions ──────────────────────────────────────────────────
            $table->string('contraction_frequency')->nullable(); // e.g. "every 5 min"
            $table->string('contraction_duration')->nullable();  // e.g. "40 seconds"
            $table->boolean('bog')->default(false);              // Bag of Water

            // ── Physical Examination ──────────────────────────────────────────
            $table->string('condition_conscious')->nullable();   // e.g. "conscious, coherent"
            $table->string('condition_strength')->nullable();    // e.g. "good"
            $table->string('condition_ambulatory')->nullable();  // e.g. "ambulatory"

            $table->string('heent')->nullable();
            $table->string('skin')->nullable();
            $table->string('heart')->nullable();
            $table->string('lungs')->nullable();
            $table->text('abdomen')->nullable();

            // ── Fundic Height & Leopold Maneuvers ─────────────────────────────
            $table->string('fundic_height')->nullable();         // e.g. "34 cm"
            $table->string('fetal_presentation')->nullable();    // e.g. "Cephalic"
            $table->string('fetal_position')->nullable();        // e.g. "LOA"
            $table->string('fetal_heart_tone')->nullable();      // e.g. "142 bpm"
            $table->string('engagement')->nullable();            // e.g. "Engaged"

            // ── Internal Examination (IE) ──────────────────────────────────────
            $table->string('ie_cervical_dilation')->nullable();  // e.g. "4 cm"
            $table->string('ie_effacement')->nullable();         // e.g. "80%"
            $table->string('ie_station')->nullable();            // e.g. "-1"
            $table->string('ie_presentation')->nullable();
            $table->string('ie_membranes')->nullable();          // e.g. "Intact"
            $table->text('ie_other_findings')->nullable();

            // ── Diagnosis on Admission (Doctor fills this) ─────────────────────
            $table->text('diagnosis_on_admission')->nullable();

            // ── Nurse final notes ─────────────────────────────────────────────
            $table->text('nurses_notes')->nullable();

            $table->timestamps();

            $table->index('visit_id');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ob_records');
    }
};