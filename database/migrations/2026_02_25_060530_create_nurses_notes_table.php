<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurses_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('nurse_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // FDAR format
            // F — Focus  : the nursing diagnosis / patient problem / concern
            $table->text('focus')->nullable();

            // D — Data   : subjective and objective data supporting the focus
            $table->text('data')->nullable();

            // A — Action : nursing interventions performed
            $table->text('action')->nullable();

            // R — Response : patient's response to the interventions
            $table->text('response')->nullable();

            $table->timestamp('noted_at')->nullable();

            // Shift the nurse was working when the note was written.
            $table->enum('shift', ['7-3', '3-11', '11-7'])
                  ->nullable()
                  ->comment('Shift during which the note was written: 7-3, 3-11, or 11-7');

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