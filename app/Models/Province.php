<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name_th',
        'name_en',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function pickupLocations()
    {
        return $this->hasMany(PickupLocation::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Province name in the given (or current) locale, falling back to Thai. */
    public function name(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'en' && $this->name_en) {
            return $this->name_en;
        }

        return $this->name_th ?: (string) $this->name_en;
    }
}
