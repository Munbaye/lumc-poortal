<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── SITE SETTINGS ─────────────────────────────────────────────────────
        // Key-value store for all editable landing page text / numbers
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        // ── CAROUSEL IMAGES ───────────────────────────────────────────────────
        Schema::create('carousel_images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');         // e.g. "abc123.png"
            $table->string('label')->nullable(); // optional alt / caption
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_images');
        Schema::dropIfExists('site_settings');
    }
};