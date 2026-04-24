<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NicuBreastfeedingObservation extends Model
{
    protected $table = 'nicu_breastfeeding_observations';
    
    protected $fillable = [
        'visit_id', 'patient_id', 'observed_by',
        'observation_date', 'observation_time',
        
        // General - Mother (Going Well)
        'general_mother_healthy', 'general_mother_relaxed', 'general_mother_bonding',
        
        // General - Mother (Difficulty)
        'general_mother_ill', 'general_mother_tense', 'general_mother_no_eye_contact',
        
        // General - Baby (Going Well)
        'general_baby_healthy', 'general_baby_calm', 'general_baby_roots',
        
        // General - Baby (Difficulty)
        'general_baby_sleepy_ill', 'general_baby_restless_crying', 'general_baby_no_root',
        
        // Breast (Going Well)
        'breast_healthy', 'breast_no_pain', 'breast_fingers_away',
        
        // Breast (Difficulty)
        'breast_red_swollen_sore', 'breast_painful', 'breast_fingers_on_areola',
        
        // Baby's Position (Going Well)
        'position_head_body_line', 'position_held_close', 'position_body_supported', 'position_nose_to_nipple',
        
        // Baby's Position (Difficulty)
        'position_neck_twisted', 'position_not_held_close', 'position_head_neck_only', 'position_chin_to_nipple',
        
        // Baby's Attachment (Going Well)
        'attachment_more_areola_above', 'attachment_mouth_open_wide', 'attachment_lip_turned_out', 'attachment_chin_touches_breast',
        
        // Baby's Attachment (Difficulty)
        'attachment_more_areola_below', 'attachment_mouth_not_wide', 'attachment_lips_forward_turned_in', 'attachment_chin_not_touching',
        
        // Suckling (Going Well)
        'suckling_slow_deep_pauses', 'suckling_cheeks_round', 'suckling_baby_releases', 'suckling_oxytocin_reflex',
        
        // Suckling (Difficulty)
        'suckling_rapid_shallow', 'suckling_cheeks_pulled_in', 'suckling_mother_takes_off', 'suckling_no_oxytocin_reflex',
        
        'notes', 'lactation_consultant_name',
    ];
    
    protected $casts = [
        'observation_date' => 'date',
        'observation_time' => 'datetime',
        
        'general_mother_healthy' => 'boolean',
        'general_mother_relaxed' => 'boolean',
        'general_mother_bonding' => 'boolean',
        'general_mother_ill' => 'boolean',
        'general_mother_tense' => 'boolean',
        'general_mother_no_eye_contact' => 'boolean',
        
        'general_baby_healthy' => 'boolean',
        'general_baby_calm' => 'boolean',
        'general_baby_roots' => 'boolean',
        'general_baby_sleepy_ill' => 'boolean',
        'general_baby_restless_crying' => 'boolean',
        'general_baby_no_root' => 'boolean',
        
        'breast_healthy' => 'boolean',
        'breast_no_pain' => 'boolean',
        'breast_fingers_away' => 'boolean',
        'breast_red_swollen_sore' => 'boolean',
        'breast_painful' => 'boolean',
        'breast_fingers_on_areola' => 'boolean',
        
        'position_head_body_line' => 'boolean',
        'position_held_close' => 'boolean',
        'position_body_supported' => 'boolean',
        'position_nose_to_nipple' => 'boolean',
        'position_neck_twisted' => 'boolean',
        'position_not_held_close' => 'boolean',
        'position_head_neck_only' => 'boolean',
        'position_chin_to_nipple' => 'boolean',
        
        'attachment_more_areola_above' => 'boolean',
        'attachment_mouth_open_wide' => 'boolean',
        'attachment_lip_turned_out' => 'boolean',
        'attachment_chin_touches_breast' => 'boolean',
        'attachment_more_areola_below' => 'boolean',
        'attachment_mouth_not_wide' => 'boolean',
        'attachment_lips_forward_turned_in' => 'boolean',
        'attachment_chin_not_touching' => 'boolean',
        
        'suckling_slow_deep_pauses' => 'boolean',
        'suckling_cheeks_round' => 'boolean',
        'suckling_baby_releases' => 'boolean',
        'suckling_oxytocin_reflex' => 'boolean',
        'suckling_rapid_shallow' => 'boolean',
        'suckling_cheeks_pulled_in' => 'boolean',
        'suckling_mother_takes_off' => 'boolean',
        'suckling_no_oxytocin_reflex' => 'boolean',
    ];
    
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
    
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    
    public function observer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observed_by');
    }
    
    /**
     * Get count of "Going Well" signs
     */
    public function getGoingWellCountAttribute(): int
    {
        $goingWellFields = [
            'general_mother_healthy', 'general_mother_relaxed', 'general_mother_bonding',
            'general_baby_healthy', 'general_baby_calm', 'general_baby_roots',
            'breast_healthy', 'breast_no_pain', 'breast_fingers_away',
            'position_head_body_line', 'position_held_close', 'position_body_supported', 'position_nose_to_nipple',
            'attachment_more_areola_above', 'attachment_mouth_open_wide', 'attachment_lip_turned_out', 'attachment_chin_touches_breast',
            'suckling_slow_deep_pauses', 'suckling_cheeks_round', 'suckling_baby_releases', 'suckling_oxytocin_reflex',
        ];
        
        $count = 0;
        foreach ($goingWellFields as $field) {
            if ($this->{$field}) $count++;
        }
        return $count;
    }
    
    /**
     * Get count of "Difficulty" signs
     */
    public function getDifficultyCountAttribute(): int
    {
        $difficultyFields = [
            'general_mother_ill', 'general_mother_tense', 'general_mother_no_eye_contact',
            'general_baby_sleepy_ill', 'general_baby_restless_crying', 'general_baby_no_root',
            'breast_red_swollen_sore', 'breast_painful', 'breast_fingers_on_areola',
            'position_neck_twisted', 'position_not_held_close', 'position_head_neck_only', 'position_chin_to_nipple',
            'attachment_more_areola_below', 'attachment_mouth_not_wide', 'attachment_lips_forward_turned_in', 'attachment_chin_not_touching',
            'suckling_rapid_shallow', 'suckling_cheeks_pulled_in', 'suckling_mother_takes_off', 'suckling_no_oxytocin_reflex',
        ];
        
        $count = 0;
        foreach ($difficultyFields as $field) {
            if ($this->{$field}) $count++;
        }
        return $count;
    }
    
    /**
     * Get alert level based on difficulty count
     */
    public function getAlertLevelAttribute(): string
    {
        $difficulty = $this->difficulty_count;
        if ($difficulty >= 5) return 'critical';
        if ($difficulty >= 3) return 'warning';
        if ($difficulty >= 1) return 'info';
        return 'good';
    }
}