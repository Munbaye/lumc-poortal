<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id', 'shift', 'schedule_date',
        'start_time', 'end_time', 'department', 'notes'
    ];

    protected $casts = ['schedule_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
}