<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageMedia extends Model
{
    protected $table = 'page_media';

    protected $fillable = [
        'key',
        'locale',
        'type',
        'disk',
        'path',
        'title',
        'alt_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function resolve(string $key, ?string $locale = null): ?self
    {
        $locale = static::normalizeLocale($locale ?: app()->getLocale());
        $cacheKey = $locale . '|' . $key;
        static $cache = [];

        if (!array_key_exists($cacheKey, $cache)) {
            $cache[$cacheKey] = static::query()
                ->where('key', $key)
                ->where('is_active', true)
                ->whereIn('locale', [$locale, ''])
                ->orderByRaw("CASE WHEN locale = ? THEN 0 ELSE 1 END", [$locale])
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->first();
        }

        return $cache[$cacheKey];
    }

    public static function url(string $key, string $fallback = '', ?string $locale = null): string
    {
        $media = static::resolve($key, $locale);
        if (!$media || !$media->path) {
            return $fallback;
        }

        try {
            return Storage::disk($media->disk ?: 'spaces')->url($media->path);
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    public static function alt(string $key, string $fallback = '', ?string $locale = null): string
    {
        $media = static::resolve($key, $locale);
        return $media?->alt_text ?: $fallback;
    }

    private static function normalizeLocale(?string $locale): string
    {
        return in_array($locale, ['th', 'en'], true) ? $locale : 'th';
    }
}

