<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
    //
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'is_active',
        'is_meeting_point', // ✅ เพิ่ม
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_meeting_point' => 'boolean', // ✅ cast เป็น boolean
    ];
}
