<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'footer_about',
        'address',
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
    ];
}
