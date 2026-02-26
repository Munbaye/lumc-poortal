<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('visits', function (Blueprint $table) {
            $table->enum('payment_class', ['Charity', 'Private'])->default('Charity')->after('visit_type');
        });
        Schema::table('medical_histories', function (Blueprint $table) {
            // Replace single physical_exam text with granular fields from NUR-005
            $table->text('pe_skin')->nullable()->after('current_medications');
            $table->text('pe_head_eent')->nullable()->after('pe_skin');
            $table->text('pe_lymph_nodes')->nullable()->after('pe_head_eent');
            $table->text('pe_chest')->nullable()->after('pe_lymph_nodes');
            $table->text('pe_lungs')->nullable()->after('pe_chest');
            $table->text('pe_cardiovascular')->nullable()->after('pe_lungs');
            $table->text('pe_breast')->nullable()->after('pe_cardiovascular');
            $table->text('pe_abdomen')->nullable()->after('pe_breast');
            $table->text('pe_rectum')->nullable()->after('pe_abdomen');
            $table->text('pe_genitalia')->nullable()->after('pe_rectum');
            $table->text('pe_musculoskeletal')->nullable()->after('pe_genitalia');
            $table->text('pe_extremities')->nullable()->after('pe_musculoskeletal');
            $table->text('pe_neurology')->nullable()->after('pe_extremities');
            // NUR-006 history fields (replacing old merged fields)
            $table->text('occupation_environment')->nullable()->after('family_history');
            $table->text('drug_allergies')->nullable()->after('occupation_environment');
            $table->text('drug_therapy')->nullable()->after('drug_allergies');
            $table->text('other_allergies')->nullable()->after('drug_therapy');
        });
    }
    public function down(): void {
        Schema::table('visits', fn($t) => $t->dropColumn('payment_class'));
    }
};