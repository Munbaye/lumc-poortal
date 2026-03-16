<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('force_password_change');
                $table->foreign('patient_id')->references('id')->on('patients')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }
            // Fixed: dropColumnIfExists does not exist in Laravel Blueprint
            if (Schema::hasColumn('users', 'force_password_change')) {
                $table->dropColumn('force_password_change');
            }
        });
    }
};