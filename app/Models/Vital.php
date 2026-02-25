<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'recorded_by', 'nurse_name',
        'temperature', 'temperature_site', 'pulse_rate', 'respiratory_rate',
        'blood_pressure', 'height_cm', 'weight_kg', 'o2_saturation',
        'pain_scale', 'notes', 'taken_at'
    ];

    protected $casts = ['taken_at' => 'datetime'];

    public function visit()    { return $this->belongsTo(Visit::class); }
    public function patient()  { return $this->belongsTo(Patient::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}