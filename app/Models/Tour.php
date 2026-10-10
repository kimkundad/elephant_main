<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    /** How early a guest driving themselves is asked to reach the camp. */
    public const SELF_DRIVE_ARRIVE_MINUTES = 20;

    /**
     * How long a tour runs. One of the four groups in the filter panel; the
     * keys are stored, the labels come from the tour_filter translations.
     */
    public const DURATIONS = [
        'full_day',
        'half_day_morning',
        'one_hour',
    ];

    /** How close the guests get to the elephants. */
    public const EXPERIENCE_TYPES = [
        'observation',
        'interactive',
    ];

    protected $fillable = [
        'province_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price_adult',
        'price_child',
        'pickup_lead_hours',
        'duration',
        'experience_type',
        'allows_self_drive',
        'map_embed_url',
        'thumbnail',
        'gallery_images',
        'is_active',
    ];

    /** Matches the column default, so a tour built in memory behaves like a saved one. */
    protected $attributes = [
        'allows_self_drive' => true,
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'pickup_lead_hours' => 'float',
        'allows_self_drive' => 'boolean',
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

    /**
     * The map as an iframe src. Admins paste anything Google gives them, so
     * accept a whole <iframe> snippet, an embed URL, coordinates or a place
     * name. Share links (maps.app.goo.gl, /maps/place/...) cannot be framed,
     * so those return null and only mapLink() is shown.
     */
    public function mapEmbedSrc(): ?string
    {
        $value = trim((string) $this->map_embed_url);

        if ($value === '') {
            return null;
        }

        if (preg_match('/<iframe[^>]*\ssrc=["\']([^"\']+)["\']/i', $value, $matches)) {
            return $matches[1];
        }

        if (str_contains($value, '/maps/embed')) {
            return $value;
        }

        if (str_starts_with($value, 'http')) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . urlencode($value) . '&output=embed';
    }

    /** The map as a link to open in Google Maps. */
    public function mapLink(): ?string
    {
        $value = trim((string) $this->map_embed_url);

        if ($value === '') {
            return null;
        }

        if (preg_match('/<iframe[^>]*\ssrc=["\']([^"\']+)["\']/i', $value, $matches)) {
            return $matches[1];
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return 'https://www.google.com/maps?q=' . urlencode($value);
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
