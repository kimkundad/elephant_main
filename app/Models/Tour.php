<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    //
    protected $fillable = [
        'province_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price_adult',
        'price_child',
        'pickup_lead_hours',
        'thumbnail',
        'gallery_images',
        'is_active',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'pickup_lead_hours' => 'float',
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

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /** Tours the public site may list and book: active, in an active province. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', 1)
            ->whereHas('province', fn (Builder $province) => $province->where('is_active', true));
    }

    public function tags()
    {
        return $this->belongsToMany(TourTag::class, 'tour_tag_tour');
    }

    public function translations()
    {
        return $this->hasMany(TourTranslation::class);
    }

    /**
     * When guests have to be at the pickup point for a session that starts at
     * $startTime, e.g. "08:30" for a 09:30 start on a tour with a one hour
     * lead. Null when the tour has no lead time set.
     */
    public function pickupTimeFor(?string $startTime): ?string
    {
        $lead = (float) $this->pickup_lead_hours;

        if (!$startTime || $lead <= 0) {
            return null;
        }

        return \Carbon\Carbon::parse($startTime)
            ->subMinutes((int) round($lead * 60))
            ->format(TourSession::TIME_FORMAT);
    }

    /** The pickup lead time as guests read it, e.g. "1 hr" or "2.5 ชม.". */
    public function pickupLeadLabel(?string $locale = null): ?string
    {
        $lead = (float) $this->pickup_lead_hours;

        if ($lead <= 0) {
            return null;
        }

        $number = rtrim(rtrim(number_format($lead, 2), '0'), '.');

        return $number . ' ' . __('tour_show.hours_short', [], $locale);
    }

    /**
     * The tour name in one language, falling back to the stored name.
     */
    public function nameIn(string $locale): string
    {
        return $this->translations->firstWhere('locale', $locale)?->name ?: (string) $this->name;
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
