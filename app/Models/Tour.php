<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'min_price',
        'max_price',
        'thumbnail',
        'gallery_images',
        'is_active',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function sessions()
    {
        return $this->hasMany(TourSession::class)
            ->orderBy('start_time', 'asc');
    }

    public function availabilities()
    {
        return $this->hasMany(TourAvailability::class);
    }

    public function tags()
    {
        return $this->belongsToMany(TourTag::class, 'tour_tag_tour');
    }

    public function translations()
    {
        return $this->hasMany(TourTranslation::class);
    }

    public function translation(?string $locale = null): ?TourTranslation
    {
        $locale = $locale ?: app()->getLocale();

        $translation = $this->translations->firstWhere('locale', $locale);
        if ($translation) {
            return $translation;
        }

        return $this->translations->firstWhere('locale', 'th')
            ?: $this->translations->firstWhere('locale', 'en');
    }
}
