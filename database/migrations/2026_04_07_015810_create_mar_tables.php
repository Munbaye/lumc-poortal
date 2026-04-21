<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MAR (Medication Administration Record) entries.
     *
     * Design: one row per medication per visit.
     * The per-date/shift administration data is stored as JSON in
     * `administration_data` — a keyed object:
     *
     *   {
     *     "2026-04-01": { "7-3": "08:00", "3-11": "",   "11-7": "" },
     *     "2026-04-02": { "7-3": "08:15", "3-11": "16:00", "11-7": "" },
     *     ...
     *   }
     *
     * Each value is either a time string (HH:MM) or empty string.
     * The set of active date columns is also stored per-visit in
     * `mar_date_columns` (a separate table) so all medication rows
     * share the same column set.
     */
    public function up(): void
    {
        // ── Date columns shared across all medications for a visit ───────────
        Schema::create('mar_date_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            /**
             * Ordered JSON array of date strings: ["2026-04-01","2026-04-02",...]
             * Max 31 days.
             */
            $table->json('dates')->default('[]');
            $table->timestamps();

            $table->unique('visit_id');
        });

        // ── One row per medication per visit ─────────────────────────────────
        Schema::create('mar_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /** Medication name / description as the nurse types it. */
            $table->string('medication_name');

            /**
             * Administration records keyed by date → shift → time.
             * e.g. {"2026-04-01":{"7-3":"08:00","3-11":"","11-7":""}}
             */
            $table->json('administration_data')->default('{}');

            /** Display order within the visit. */
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('visit_id');
            $table->index(['visit_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mar_entries');
        Schema::dropIfExists('mar_date_columns');
    }
};