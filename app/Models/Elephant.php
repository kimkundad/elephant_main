<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elephant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tour_id',
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

    /** The program a guest books to meet this elephant. */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Where "Read more" leads: the elephant's program while it can be booked,
     * otherwise this elephant on the Our Elephants page.
     */
    public function programUrl(): string
    {
        $tour = $this->tour;

        if ($tour && Tour::visible()->whereKey($tour->id)->exists()) {
            return route('frontend.tours.show.v2', $tour->slug);
        }

        return route('frontend.elephants') . '#' . $this->slug;
    }

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

    /** The fallback may be an empty column (history is optional), so null reads as ''. */
    public function getTranslated(string $field, ?string $fallback = '', ?string $locale = null): string
    {
        $t = $this->translation($locale);
        if ($t && isset($t->{$field}) && $t->{$field} !== '') {
            return (string) $t->{$field};
        }

        if (isset($this->{$field}) && $this->{$field}) {
            return (string) $this->{$field};
        }

        return $fallback ?? '';
    }
}
