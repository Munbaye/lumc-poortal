<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * ResultUpload — a tech-uploaded result file linked to a LabRequest or RadiologyRequest.
 *
 * request_type: 'lab'      → $request_id references lab_requests.id
 * request_type: 'radiology' → $request_id references radiology_requests.id
 */
class ResultUpload extends Model
{
    protected $fillable = [
        'request_type',
        'request_id',
        'visit_id',
        'patient_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'interpretation',
        'notes',
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    /** Public URL to view / download the file. */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /** Human-readable file size (e.g. "2.3 MB"). */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024)       return $bytes . ' B';
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    /** True if this is a radiology result that has interpretation text. */
    public function hasInterpretation(): bool
    {
        return $this->request_type === 'radiology' && filled($this->interpretation);
    }

    /** File type category for icon display. */
    public function getFileTypeIconAttribute(): string
    {
        return match (true) {
            str_contains($this->file_mime ?? '', 'pdf')   => 'heroicon-o-document-text',
            str_contains($this->file_mime ?? '', 'image') => 'heroicon-o-photo',
            default                                      => 'heroicon-o-paper-clip',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function visit()      { return $this->belongsTo(Visit::class); }
    public function patient()    { return $this->belongsTo(Patient::class); }
    public function uploadedBy() { return $this->belongsTo(User::class, 'uploaded_by'); }

    /** Polymorphic-style: get the parent request. */
    public function getRequestAttribute(): LabRequest|RadiologyRequest|null
    {
        return match ($this->request_type) {
            'lab'       => LabRequest::find($this->request_id),
            'radiology' => RadiologyRequest::find($this->request_id),
            default     => null,
        };
    }
}