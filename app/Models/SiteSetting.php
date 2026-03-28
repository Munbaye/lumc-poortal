<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // ── READ ──────────────────────────────────────────────────────────────────
    /**
     * Get a setting value by key, with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("site_setting:{$key}", function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    // ── WRITE ─────────────────────────────────────────────────────────────────
    /**
     * Set (upsert) a setting value and bust the cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_setting:{$key}");
    }

    // ── BULK READ ─────────────────────────────────────────────────────────────
    /**
     * Returns all settings as a flat key → value array.
     * Used by the welcome view so we only hit the DB once.
     */
    public static function allAsArray(): array
    {
        return Cache::rememberForever('site_settings_all', function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    // ── BULK BUST ─────────────────────────────────────────────────────────────
    public static function bustAll(): void
    {
        Cache::forget('site_settings_all');
        // Individual keys will be lazily re-cached on next read
    }
}