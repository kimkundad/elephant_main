<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    protected $fillable = [
        'channel',
        'event',
        'level',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}
