<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('avatar')->nullable()->after('signature');
            // stores base64 PNG or chosen icon key e.g. "icon:stethoscope"
            $table->string('avatar_initials', 4)->nullable()->after('avatar');
            // custom initials override (max 2 chars displayed)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'avatar_initials']);
        });
    }
};