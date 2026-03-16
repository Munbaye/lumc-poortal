<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('result_uploads', function (Blueprint $table) {
            $table->foreignId('amended_from_id')
                ->nullable()
                ->after('notes')
                ->constrained('result_uploads')
                ->nullOnDelete();

            $table->text('amendment_reason')
                ->nullable()
                ->after('amended_from_id');
        });
    }

    public function down(): void
    {
        Schema::table('result_uploads', function (Blueprint $table) {
            // Check before dropping to avoid errors on migrate:refresh
            if (Schema::hasColumn('result_uploads', 'amended_from_id')) {
                $table->dropForeign(['amended_from_id']);
                $table->dropColumn('amended_from_id');
            }

            if (Schema::hasColumn('result_uploads', 'amendment_reason')) {
                $table->dropColumn('amendment_reason');
            }
        });
    }
};