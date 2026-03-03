<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'has_incomplete_info')) {
                $table->boolean('has_incomplete_info')->default(false)->after('is_pedia');
            }
            if (!Schema::hasColumn('patients', 'is_unknown')) {
                $table->boolean('is_unknown')->default(false)->after('has_incomplete_info');
            }
            if (!Schema::hasColumn('patients', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('is_unknown');
            }
            if (!Schema::hasColumn('patients', 'registration_type')) {
                $table->string('registration_type')->default('OPD')->after('age');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumnIfExists('has_incomplete_info');
            $table->dropColumnIfExists('is_unknown');
            $table->dropColumnIfExists('age');
            $table->dropColumnIfExists('registration_type');
        });
    }
};