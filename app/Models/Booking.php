<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
    'customer_id',
    'customer_name', 'customer_phone', 'customer_email', // ✅ เพิ่ม
    'tour_id','session_id','date',
    'adults','children','infants','total_guests',
    'subtotal','vat_amount','fee_amount','grand_total',
    'total_price','status','pickup_location_id','created_by'
];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function session()
    {
        return $this->belongsTo(TourSession::class, 'session_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }
}
