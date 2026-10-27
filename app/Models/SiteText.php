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

    /** Values already read this request, so one page does not query the same key twice. */
    private static array $valueCache = [];

    public static function getValue(string $key, string $fallback = '', ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if (!in_array($locale, ['th', 'en'], true)) {
            $locale = 'th';
        }

        $cacheKey = $locale . '|' . $key;

        if (!array_key_exists($cacheKey, static::$valueCache)) {
            static::$valueCache[$cacheKey] = static::query()
                ->where('key', $key)
                ->where('locale', $locale)
                ->value('value');
        }

        return static::$valueCache[$cacheKey] ?: $fallback;
    }

    /** Drop what has been read, after a save and between tests. */
    public static function forgetCached(): void
    {
        static::$valueCache = [];
    }
}
