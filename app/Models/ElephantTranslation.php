<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElephantTranslation extends Model
{
    protected $fillable = [
        'elephant_id',
        'locale',
        'name',
        'history',
    ];

    public function elephant()
    {
        return $this->belongsTo(Elephant::class);
    }
}
