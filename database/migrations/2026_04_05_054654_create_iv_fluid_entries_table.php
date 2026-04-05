<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iv_fluid_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            // Nullable so a deleted nurse account doesn't block anything
            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // ── Immutable after save ─────────────────────────────────────────
            $table->date('date_started');
            $table->time('time_started');
            $table->unsignedSmallInteger('bottle_number');

            $table->string('iv_solution');

            // ── Editable after save ──────────────────────────────────────────
            $table->dateTime('consumed_at')->nullable()
                  ->comment('Date & time bottle was consumed / KVO');
            $table->text('remarks')->nullable();

            // ── Audit ────────────────────────────────────────────────────────
            $table->string('nurse_name');          // snapshot of recorder's name
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('editor_name')->nullable();
            $table->timestamp('edited_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('visit_id');
            $table->index(['visit_id', 'date_started', 'bottle_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iv_fluid_entries');
    }
};