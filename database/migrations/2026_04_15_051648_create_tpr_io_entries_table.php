<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tpr_io_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nurse_name');

            $table->date('date');
            $table->string('shift');          // 7-3 | 3-11 | 11-7

            $table->unsignedSmallInteger('urine_count')->nullable();
            $table->unsignedTinyInteger('stool_count')->nullable();
            $table->string('stool_type')->nullable();   // formed | loose | watery | bloody | none

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['visit_id', 'date', 'shift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tpr_io_entries');
    }
};