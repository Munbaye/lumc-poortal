<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who did it (null = system / unauthenticated)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Action category + specific action
            // category:  auth | patient | vitals | clinical | orders | uploads | admin | system
            // action:    login | logout | login_failed | created_patient | recorded_vitals | etc.
            $table->string('category')->default('system');
            $table->string('action');

            // What record was affected
            $table->string('subject_type')->nullable();   // 'Patient', 'Visit', 'User', …
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label')->nullable();  // human-readable: "Juan Garcia (LUMC-2026-000001)"

            // Before / after snapshots
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request metadata
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('panel')->nullable();          // admin | doctor | nurse | clerk | tech

            $table->timestamps();

            $table->index(['category', 'created_at']);
            $table->index(['user_id',  'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};