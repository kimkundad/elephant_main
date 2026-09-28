<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'company_name',
        'footer_about',
        'address',
        'address_th',
        'address_en',
        'phone',
        'phone_secondary',
        'email',
        'hours',
        'facebook_url',
        'instagram_url',
        'contact_office_hours',
        'contact_whatsapp_line',
        'map_embed_url',
        'copyright_text',
        'checkin_pin',
        'logo_path',
        'logo_header_path',
        'logo_footer_path',
        'og_image_path',
    ];

    /**
     * The WhatsApp contact as it should be shown, e.g. "+66 95 846 7417".
     */
    public function whatsappLabel(): ?string
    {
        $value = trim((string) $this->contact_whatsapp_line);

        if ($value === '' || $value === '#') {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            $digits = $this->whatsappDigits($value);

            return $digits ? '+' . $digits : $value;
        }

        return $value;
    }

    /**
     * A clickable wa.me link, built from whatever the admin typed in the
     * WhatsApp field: a phone number, or a ready-made link.
     */
    public function whatsappUrl(): ?string
    {
        $value = trim((string) $this->contact_whatsapp_line);

        if ($value === '' || $value === '#') {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        $digits = $this->whatsappDigits($value);

        return $digits ? 'https://wa.me/' . $digits : null;
    }

    private function whatsappDigits(string $value): ?string
    {
        $digits = preg_replace('/\D/', '', $value);

        return strlen((string) $digits) >= 8 ? $digits : null;
    }

    public function getLogoHeaderUrlAttribute(): ?string
    {
        $path = $this->logo_header_path ?: $this->logo_path;

        if (!$path) {
            return null;
        }

        return Storage::disk('spaces')->url($path);
    }

    public function getLogoFooterUrlAttribute(): ?string
    {
        if (!$this->logo_footer_path) {
            return null;
        }

        return Storage::disk('spaces')->url($this->logo_footer_path);
    }

    public function getOgImageUrlAttribute(): ?string
    {
        if (!$this->og_image_path) {
            return null;
        }

        return Storage::disk('spaces')->url($this->og_image_path);
    }
}
