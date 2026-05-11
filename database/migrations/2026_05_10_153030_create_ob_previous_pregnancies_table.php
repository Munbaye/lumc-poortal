<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ob_previous_pregnancies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ob_record_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('gravida_order');        // 1st, 2nd, 3rd...
            $table->string('aog_term')->nullable();              // e.g. "38 wks" / "Preterm"
            $table->string('manner_of_delivery')->nullable();    // NSD / CS / Forceps / etc.
            $table->date('delivery_date')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Unknown'])->nullable();
            $table->unsignedSmallInteger('weight_grams')->nullable();
            $table->string('complications')->nullable();         // free-text

            $table->timestamps();

            $table->index('ob_record_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ob_previous_pregnancies');
    }
};