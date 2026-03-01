<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourTag extends Model
{
    protected $fillable = [
        'slug',
        'name_th',
        'name_en',
        'is_active',
        'sort_order',
    ];

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_tag_tour');
    }

    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en') {
            return $this->name_en ?: $this->name_th;
        }

        return $this->name_th ?: $this->name_en;
    }
}

