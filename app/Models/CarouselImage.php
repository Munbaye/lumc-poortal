<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarouselImage extends Model
{
    protected $fillable = ['filename', 'label', 'sort_order', 'is_active', 'display_mode'];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Full public URL for this image.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url('carousel/' . $this->filename);
    }

    /**
     * Active images sorted for the landing page carousel.
     */
    public static function forCarousel()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}