<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursesNote extends Model
{
    protected $fillable = ['visit_id', 'nurse_id', 'note'];

    public function visit() { return $this->belongsTo(Visit::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}