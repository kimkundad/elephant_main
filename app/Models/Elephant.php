<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elephant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'rescued_at',
        'history',
        'images',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rescued_at' => 'date',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(ElephantTranslation::class);
    }

    public function translation(?string $locale = null): ?ElephantTranslation
    {
        $locale = $locale ?: app()->getLocale();
        if (!in_array($locale, ['th', 'en'], true)) {
            $locale = 'th';
        }

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'th');
    }

    public function getTranslated(string $field, string $fallback = '', ?string $locale = null): string
    {
        $t = $this->translation($locale);
        if ($t && isset($t->{$field}) && $t->{$field} !== '') {
            return (string) $t->{$field};
        }

        if (isset($this->{$field}) && $this->{$field}) {
            return (string) $this->{$field};
        }

        return $fallback;
    }
}
