<?php
/**
 * Migration: create_nurses_notes_table
 *
 * Replaces the old nurses_notes migration (which had a single `note` text column).
 * This version adds proper SOAP-format columns.
 *
 * Run: php artisan migrate:fresh
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurses_notes', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('visit_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('nurse_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // SOAP format
            // S — Subjective: what the patient/family reports (symptoms, complaints, feelings)
            $table->text('subjective')->nullable();

            // O — Objective: measurable / observable data (vitals, exam findings, lab values)
            $table->text('objective')->nullable();

            // A — Assessment: nurse's clinical judgment and interpretation
            $table->text('assessment')->nullable();

            // P — Plan: nursing interventions and actions taken or planned
            $table->text('plan')->nullable();

            // When the note was formally made (defaults to created_at but can be set explicitly)
            $table->timestamp('noted_at')->nullable();

            $table->timestamps();

            $table->index(['visit_id', 'noted_at']);
            $table->index('nurse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurses_notes');
    }
};