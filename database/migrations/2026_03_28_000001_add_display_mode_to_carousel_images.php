<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousel_images', function (Blueprint $table) {
            // 'contain' = logo/icon style (object-fit: contain, transparent bg)
            // 'cover'   = photo style (object-fit: cover with blurred bg backdrop)
            $table->string('display_mode')->default('contain')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('carousel_images', function (Blueprint $table) {
            $table->dropColumn('display_mode');
        });
    }
};