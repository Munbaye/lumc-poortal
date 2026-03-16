<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('employee_id')->nullable()->unique();
            $table->enum('panel', ['admin','doctor','nurse','clerk','tech','patient'])->default('clerk');
            $table->string('specialty')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('force_password_change')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // patient_id added after patients table exists (see migration 03)
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};