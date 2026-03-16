<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'patient_id')) {
                $table->foreignId('patient_id')
                    ->nullable()
                    ->after('specialty')
                    ->constrained('patients')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check foreign key exists before dropping to avoid crash on migrate:refresh
            if (Schema::hasColumn('users', 'patient_id')) {
                try {
                    $table->dropForeign(['patient_id']);
                } catch (\Exception $e) {
                    // Foreign key may not exist if migration was partially run
                }
                $table->dropColumn('patient_id');
            }
        });
    }
};