<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The marketing tags an admin can switch on without a deploy: GA4, Google Tag
 * Manager, Meta Pixel, TikTok, Google Ads, plus free-form snippets for anything
 * we have not given a field of its own.
 *
 * One row holds the lot, like site_settings.
 */
class TrackingSetting extends Model
{
    protected $fillable = [
        'ga4_measurement_id',
        'gtm_container_id',
        'meta_pixel_id',
        'tiktok_pixel_id',
        'google_ads_conversion_id',
        'google_ads_purchase_label',
        'facebook_domain_verification',
        'track_purchase',
        'head_html',
        'body_html',
    ];

    protected $casts = [
        'track_purchase' => 'boolean',
    ];

    private static ?self $current = null;

    /** The single row, read once per request. */
    public static function current(): self
    {
        return self::$current ??= self::first() ?? new self();
    }

    /** Tests and the admin form need the cache dropped after a save. */
    public static function forgetCurrent(): void
    {
        self::$current = null;
    }

    public function ga4Id(): ?string
    {
        return $this->clean($this->ga4_measurement_id);
    }

    public function gtmId(): ?string
    {
        return $this->clean($this->gtm_container_id);
    }

    public function metaPixelId(): ?string
    {
        return $this->clean($this->meta_pixel_id);
    }

    public function tiktokPixelId(): ?string
    {
        return $this->clean($this->tiktok_pixel_id);
    }

    public function googleAdsId(): ?string
    {
        return $this->clean($this->google_ads_conversion_id);
    }

    /** The Google Ads conversion label, e.g. "AbC-D_efGh", without its id. */
    public function googleAdsPurchaseLabel(): ?string
    {
        return $this->clean($this->google_ads_purchase_label);
    }

    /** Google's own "AW-123/AbC-D" send_to value, when both halves are set. */
    public function googleAdsSendTo(): ?string
    {
        $id = $this->googleAdsId();
        $label = $this->googleAdsPurchaseLabel();

        return $id && $label ? $id . '/' . $label : null;
    }

    public function facebookDomainVerification(): ?string
    {
        return $this->clean($this->facebook_domain_verification);
    }

    /** Whether anything at all is set, so the layout can skip the whole block. */
    public function hasAnyTag(): bool
    {
        return (bool) (
            $this->ga4Id()
            || $this->gtmId()
            || $this->metaPixelId()
            || $this->tiktokPixelId()
            || $this->googleAdsId()
        );
    }

    /** Whether a purchase is worth reporting: a tag that listens, and the switch on. */
    public function sendsPurchase(): bool
    {
        return $this->track_purchase
            && ($this->ga4Id() || $this->metaPixelId() || $this->tiktokPixelId() || $this->googleAdsSendTo());
    }

    private function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
