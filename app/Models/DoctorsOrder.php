<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorsOrder extends Model
{
    protected $fillable = [
        'visit_id', 'doctor_id', 'order_text',
        'is_completed', 'completed_by', 'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function visit()       { return $this->belongsTo(Visit::class); }
    public function doctor()      { return $this->belongsTo(User::class, 'doctor_id'); }
    public function completedBy() { return $this->belongsTo(User::class, 'completed_by'); }
}