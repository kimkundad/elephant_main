<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingPublicController extends Controller
{
    public function show(string $code)
    {
        $booking = Booking::with(['tour','session'])
            ->where('public_code', $code)
            ->firstOrFail();

        return view('public.booking', compact('booking'));
    }
}