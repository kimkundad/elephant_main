<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elephant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'rescued_at',
        'history',
        'images',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rescued_at' => 'date',
        'images' => 'array',
        'is_active' => 'boolean',
    ];
}
