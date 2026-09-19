<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Customer extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'phone_country',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
