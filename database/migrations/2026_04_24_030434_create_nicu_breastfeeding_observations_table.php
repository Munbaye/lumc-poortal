<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicu_breastfeeding_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('observed_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Observation date
            $table->date('observation_date')->nullable();
            $table->time('observation_time')->nullable();
            
            // ── General - Mother (Going Well) ─────────────────────────────────────
            $table->boolean('general_mother_healthy')->default(false);
            $table->boolean('general_mother_relaxed')->default(false);
            $table->boolean('general_mother_bonding')->default(false);
            
            // ── General - Mother (Difficulty) ─────────────────────────────────────
            $table->boolean('general_mother_ill')->default(false);
            $table->boolean('general_mother_tense')->default(false);
            $table->boolean('general_mother_no_eye_contact')->default(false);
            
            // ── General - Baby (Going Well) ──────────────────────────────────────
            $table->boolean('general_baby_healthy')->default(false);
            $table->boolean('general_baby_calm')->default(false);
            $table->boolean('general_baby_roots')->default(false);
            
            // ── General - Baby (Difficulty) ──────────────────────────────────────
            $table->boolean('general_baby_sleepy_ill')->default(false);
            $table->boolean('general_baby_restless_crying')->default(false);
            $table->boolean('general_baby_no_root')->default(false);
            
            // ── Breast (Going Well) ───────────────────────────────────────────────
            $table->boolean('breast_healthy')->default(false);
            $table->boolean('breast_no_pain')->default(false);
            $table->boolean('breast_fingers_away')->default(false);
            
            // ── Breast (Difficulty) ───────────────────────────────────────────────
            $table->boolean('breast_red_swollen_sore')->default(false);
            $table->boolean('breast_painful')->default(false);
            $table->boolean('breast_fingers_on_areola')->default(false);
            
            // ── Baby's Position (Going Well) ──────────────────────────────────────
            $table->boolean('position_head_body_line')->default(false);
            $table->boolean('position_held_close')->default(false);
            $table->boolean('position_body_supported')->default(false);
            $table->boolean('position_nose_to_nipple')->default(false);
            
            // ── Baby's Position (Difficulty) ──────────────────────────────────────
            $table->boolean('position_neck_twisted')->default(false);
            $table->boolean('position_not_held_close')->default(false);
            $table->boolean('position_head_neck_only')->default(false);
            $table->boolean('position_chin_to_nipple')->default(false);
            
            // ── Baby's Attachment (Going Well) ────────────────────────────────────
            $table->boolean('attachment_more_areola_above')->default(false);
            $table->boolean('attachment_mouth_open_wide')->default(false);
            $table->boolean('attachment_lip_turned_out')->default(false);
            $table->boolean('attachment_chin_touches_breast')->default(false);
            
            // ── Baby's Attachment (Difficulty) ────────────────────────────────────
            $table->boolean('attachment_more_areola_below')->default(false);
            $table->boolean('attachment_mouth_not_wide')->default(false);
            $table->boolean('attachment_lips_forward_turned_in')->default(false);
            $table->boolean('attachment_chin_not_touching')->default(false);
            
            // ── Suckling (Going Well) ─────────────────────────────────────────────
            $table->boolean('suckling_slow_deep_pauses')->default(false);
            $table->boolean('suckling_cheeks_round')->default(false);
            $table->boolean('suckling_baby_releases')->default(false);
            $table->boolean('suckling_oxytocin_reflex')->default(false);
            
            // ── Suckling (Difficulty) ────────────────────────────────────────────
            $table->boolean('suckling_rapid_shallow')->default(false);
            $table->boolean('suckling_cheeks_pulled_in')->default(false);
            $table->boolean('suckling_mother_takes_off')->default(false);
            $table->boolean('suckling_no_oxytocin_reflex')->default(false);
            
            $table->timestamps();
            
            $table->index('visit_id');
            $table->index('observation_date');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('nicu_breastfeeding_observations');
    }
};