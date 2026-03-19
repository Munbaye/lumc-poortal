<?php
/**
 * This migration is intentionally empty.
 *
 * The `status` column on lab_requests / radiology_requests and the
 * `result_uploads` table were consolidated into the earlier migration:
 *   2026_03_18_103032_create_request_tables.php
 *
 * This file is kept as a no-op so Laravel's migration history stays intact
 * (the record in the migrations table remains, preventing re-run errors).
 *
 * DO NOT add anything here — it runs after the tables already exist.
 */

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // no-op — columns already created in 2026_03_18_103032_create_request_tables
    }

    public function down(): void
    {
        // no-op
    }
};