<?php
/**
 * REPLACE the existing doctors_orders migration with this one.
 * Run: php artisan migrate:fresh
 *
 * Adds:
 *   status       enum('pending','carried','discontinued')  default 'pending'
 *   order_date   timestamp  — when the order was written (defaults to now)
 *   notes        text       — optional free-text note on the order set
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();

            // The actual clinical instruction
            $table->text('order_text');

            // Lifecycle status
            $table->enum('status', ['pending', 'carried', 'discontinued'])->default('pending');

            // When the doctor wrote the order
            $table->timestamp('order_date')->useCurrent();

            // Optional notes attached to this specific order
            $table->text('notes')->nullable();

            // Legacy boolean — kept for backward compat with existing clerk/nurse code
            $table->boolean('is_completed')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['visit_id', 'status']);
            $table->index(['visit_id', 'is_completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors_orders');
    }
};