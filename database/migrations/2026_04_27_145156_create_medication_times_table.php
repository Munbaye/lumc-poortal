<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;z

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('medication_times', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('mar_entry_id');
        $table->date('date');
        $table->string('shift');
        $table->time('time');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_times');
    }
};
