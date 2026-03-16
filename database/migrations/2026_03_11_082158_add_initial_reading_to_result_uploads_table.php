<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add initial reading fields to result_uploads.
     *
     * Covers the three phases a MedTech validates before releasing a result:
     *   - Pre-Analytical  : specimen, labeling, patient match, collection time
     *   - Analytical      : QC, reference ranges, instrument errors, reportable range
     *   - Post-Analytical : legibility, consistency, critical values, previous results
     */
    public function up(): void
    {
        Schema::table('result_uploads', function (Blueprint $table) {

            // ── Initial Reading ───────────────────────────────────────────────
            // Note: requested_by and performed_by already exist from a previous migration.

            // Stores all 13 checklist items as JSON:
            // { "pre": { "patient_match": true, ... }, "analytical": {...}, "post": {...} }
            $table->json('readability_checks')
                  ->nullable()
                  ->after('notes')
                  ->comment('Three-phase initial reading checklist: pre-analytical, analytical, post-analytical');

            // Required short impression written by the tech before submitting
            $table->text('initial_impression')
                  ->nullable()
                  ->after('readability_checks');

            // ── Critical Value Flagging ───────────────────────────────────────
            $table->boolean('is_critical')
                  ->default(false)
                  ->after('initial_impression');

            $table->text('critical_reason')
                  ->nullable()
                  ->after('is_critical')
                  ->comment('Required if is_critical = true');

            $table->timestamp('critical_notified_at')
                  ->nullable()
                  ->after('critical_reason')
                  ->comment('Timestamp when the doctor was immediately notified of a critical value');

            // Result Status
            // pending_validation = uploaded, awaiting senior tech / pathologist sign-off
            // released           = validated and visible to doctor
            $table->enum('status', ['pending_validation', 'released'])
                  ->default('pending_validation')
                  ->after('critical_notified_at');
        });
    }

    public function down(): void
    {
        Schema::table('result_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'readability_checks',
                'initial_impression',
                'is_critical',
                'critical_reason',
                'critical_notified_at',
                'status',
            ]);
        });
    }
};