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
        'is_active'
    ];
}
