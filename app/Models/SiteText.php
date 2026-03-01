<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteText extends Model
{
    protected $fillable = [
        'page',
        'section',
        'key',
        'locale',
        'value',
    ];

    public static function getValue(string $key, string $fallback = '', ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if (!in_array($locale, ['th', 'en'], true)) {
            $locale = 'th';
        }

        static $cache = [];
        $cacheKey = $locale . '|' . $key;

        if (!array_key_exists($cacheKey, $cache)) {
            $cache[$cacheKey] = static::query()
                ->where('key', $key)
                ->where('locale', $locale)
                ->value('value');
        }

        return $cache[$cacheKey] ?: $fallback;
    }
}
