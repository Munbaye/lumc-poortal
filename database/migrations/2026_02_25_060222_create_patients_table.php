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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('case_no')->unique(); // e.g., LUMC-2024-000001
            $table->string('family_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('age')->nullable(); // auto-calc or manual
            $table->enum('sex', ['Male', 'Female']);
            $table->text('address');
            $table->string('contact_number')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('civil_status', ['Single','Married','Widowed','Separated','Annulled'])->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nationality')->nullable()->default('Filipino');
            // ER-specific
            $table->enum('registration_type', ['OPD','ER'])->default('OPD');
            $table->enum('brought_by', ['Self','Family','Ambulance','Police','Other'])->nullable();
            $table->enum('condition_on_arrival', [
                'Good','Fair','Poor','Shock','Comatose','Hemorrhagic','DOA'
            ])->nullable();
            // Flags
            $table->boolean('is_pedia')->default(false); // auto-set if age < ~12
            $table->timestamps();
            $table->softDeletes();
            $table->index(['family_name', 'first_name']);
            $table->index('birthday');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
