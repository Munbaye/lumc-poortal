<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultUpload extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'uploaded_by', 'result_type',
        'test_name', 'file_path', 'file_name', 'mime_type', 'notes'
    ];

    public function visit()    { return $this->belongsTo(Visit::class); }
    public function patient()  { return $this->belongsTo(Patient::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}