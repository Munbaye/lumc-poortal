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
        Schema::create('patient_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->string('consent_type'); // e.g., 'care', 'data_privacy', 'procedure'
            $table->boolean('consented')->default(false);
            $table->string('consented_by')->nullable(); // patient name or guardian
            $table->string('relationship')->nullable(); // if guardian
            $table->timestamp('consented_at')->nullable();
            $table->foreignId('witnessed_by')->nullable()->constrained('users');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_consents');
    }
};
