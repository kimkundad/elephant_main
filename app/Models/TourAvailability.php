<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourAvailability extends Model
{
    protected $table = 'tour_session_availability'; // ← แก้ชื่อ table ให้ตรง

    protected $fillable = [
        'tour_id',
        'session_id',
        'date',
        'is_open',
        'capacity_override'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function session()
    {
        return $this->belongsTo(TourSession::class, 'session_id');
    }
}
