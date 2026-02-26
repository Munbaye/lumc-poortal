<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialty')->nullable()->after('email');
            // e.g. 'General Practitioner', 'Internal Medicine', 'Pediatrics', etc.
        });
        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('assigned_doctor_id')
                ->nullable()
                ->after('clerk_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('users', fn($t) => $t->dropColumn('specialty'));
        Schema::table('visits', fn($t) => $t->dropForeignIdFor(\App\Models\User::class, 'assigned_doctor_id'));
    }
};