<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
    protected $fillable = [
        'province_id',
        'name',
        'latitude',
        'longitude',
        'is_active',
        'is_meeting_point',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_meeting_point' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /** Pickup points a guest may choose for a tour in this province. */
    public function scopeAvailableIn(Builder $query, int $provinceId): Builder
    {
        return $query->where('is_active', true)->where('province_id', $provinceId);
    }
}
