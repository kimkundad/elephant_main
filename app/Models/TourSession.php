<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourSession extends Model
{
    //
    protected $fillable = [
        'tour_id',
        'name',
        'start_time',
        'end_time',
        'default_capacity',
        'is_active',
        'start_time',
    'end_time',
    'capacity',
    'title',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TourAvailability::class, 'session_id');
    }

    public function remainingCapacity($date)
    {
        $sessionCapacity = $this->capacity ?? $this->default_capacity;

        // Availability override (daily)
        $availability = \App\Models\TourAvailability::where('tour_id', $this->tour_id)
            ->where('session_id', $this->id)
            ->where('date', $date)
            ->first();

        // ถ้า availability ถูกปิดทั้งวัน
        if ($availability && $availability->is_open == 0) {
            return 0;
        }

        // ถ้ามี capacity_override ให้ใช้ค่านั้นแทน
        if ($availability && !is_null($availability->capacity_override)) {
            $sessionCapacity = $availability->capacity_override;
        }

        // ยอดที่จองแล้วในวันนั้น
        $booked = \App\Models\Booking::where('tour_id', $this->tour_id)
            ->where('session_id', $this->id)
            ->where('date', $date)
            ->sum('total_guests');

        // คำนวณที่เหลือ
        $remaining = $sessionCapacity - $booked;

        return max($remaining, 0);
    }
}
