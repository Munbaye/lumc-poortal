<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Sequence counter tables ─────────────────────────────────────────
        Schema::create('lab_request_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_sequence')->default(0);
            $table->unique('year');
            $table->timestamps();
        });

        Schema::create('radiology_request_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_sequence')->default(0);
            $table->unique('year');
            $table->timestamps();
        });

        // ── Lab Requests ────────────────────────────────────────────────────
        Schema::create('lab_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();

            // Relationships
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Lifecycle status — pending → in_progress → completed
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            // Form fields
            $table->string('ward')->nullable();
            $table->string('request_type')->default('routine'); // routine | stat
            $table->string('stat_justification')->nullable();
            $table->text('clinical_diagnosis')->nullable();
            $table->text('requesting_physician')->nullable();
            $table->json('tests')->nullable();
            $table->string('specimen')->nullable();
            $table->string('antibiotics_taken')->nullable();
            $table->string('antibiotics_duration')->nullable();
            $table->text('other_tests')->nullable();

            // Timing
            $table->date('date_requested')->nullable();
            $table->timestamp('request_received_at')->nullable();
            $table->string('specimen_collected_by')->nullable();
            $table->timestamp('test_started_at')->nullable();
            $table->timestamp('test_done_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['visit_id', 'status']);
            $table->index(['visit_id', 'created_at']);
        });

        // ── Radiology Requests ──────────────────────────────────────────────
        Schema::create('radiology_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();

            // Relationships
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Lifecycle status — pending → in_progress → completed
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            // Form fields
            $table->string('modality')->nullable();
            $table->string('source')->nullable();
            $table->string('ward')->nullable();
            $table->text('examination_desired')->nullable();
            $table->text('clinical_diagnosis')->nullable();
            $table->text('clinical_findings')->nullable();
            $table->text('radiologist_interpretation')->nullable();
            $table->string('requesting_physician')->nullable();

            // Timing
            $table->date('date_requested')->nullable();
            $table->timestamp('exam_done_at')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['visit_id', 'status']);
            $table->index(['visit_id', 'created_at']);
        });

        // ── Result Uploads ──────────────────────────────────────────────────
        Schema::create('result_uploads', function (Blueprint $table) {
            $table->id();
            $table->enum('request_type', ['lab', 'radiology']);
            $table->unsignedBigInteger('request_id');
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_mime')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('interpretation')->nullable(); // radiology only
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['request_type', 'request_id']);
            $table->index(['visit_id', 'request_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_uploads');
        Schema::dropIfExists('radiology_requests');
        Schema::dropIfExists('lab_requests');
        Schema::dropIfExists('radiology_request_sequences');
        Schema::dropIfExists('lab_request_sequences');
    }
};