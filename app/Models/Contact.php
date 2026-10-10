<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name','email','phone','phone_country','subject','message',
        'ip_address','user_agent','submitted_at','handled_at','handled_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'handled_at' => 'datetime',
    ];

    /** An enquiry nobody has answered yet. */
    public function scopeOpen($query)
    {
        return $query->whereNull('handled_at');
    }
}
