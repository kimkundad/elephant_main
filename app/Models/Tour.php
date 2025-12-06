<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'min_price',
        'max_price',
        'thumbnail',
        'is_active',
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
}
