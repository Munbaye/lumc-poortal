<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('unknown_patient_sequences')) {
            Schema::create('unknown_patient_sequences', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('year');
                $table->unsignedSmallInteger('last_sequence')->default(0);
                $table->unique('year');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('unknown_patient_sequences');
    }
};