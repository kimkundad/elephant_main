<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
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
        'logo_path',
        'logo_header_path',
        'logo_footer_path',
    ];

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
}
