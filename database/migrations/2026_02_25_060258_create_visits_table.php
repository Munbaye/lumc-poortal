<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Each time a patient comes to OPD/ER, a new visit is created
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clerk_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('visit_type', ['OPD','ER'])->default('OPD');
            $table->text('chief_complaint');
            $table->enum('status', [
                'registered','vitals_done','assessed','discharged','admitted','referred'
            ])->default('registered');
            $table->enum('disposition', ['Discharged','Admitted','Referred','HAMA','Expired'])->nullable();
            $table->string('admitted_ward')->nullable();
            $table->text('referral_notes')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('discharged_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
