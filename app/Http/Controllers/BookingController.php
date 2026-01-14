<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSession;
use App\Models\PickupLocation; // ถ้ามี model นี้
use Carbon\Carbon;


class BookingController extends Controller
{
    public function create(Request $request)
    {
        $tourId = $request->query('tour');
        $sessionId = $request->query('session');
        $date = $request->query('date');

        // กันเลือกย้อนหลัง
        if ($date && Carbon::parse($date)->lt(now()->startOfDay())) {
            $date = now()->toDateString();
        }

        $tour = Tour::findOrFail($tourId);
        $session = TourSession::findOrFail($sessionId);

        // ราคา (เริ่มง่าย ๆ: ผู้ใหญ่ = min_price, เด็ก = ครึ่งราคา, infant = 0)
        $prices = [
            'adult' => (int)($tour->min_price ?? 0),
            'child' => (int)round(($tour->min_price ?? 0) * 0.5),
            'infant' => 0,
        ];

        $meetingPoints = PickupLocation::query()
            ->where('is_active', 1)
            ->where('is_meeting_point', 1)
            ->orderBy('name')
            ->get();

        // pickup locations (ถ้าคุณมีตารางนี้อยู่แล้ว)


        return view('frontend.pages.booking.create', compact(
            'tour', 'session', 'date', 'prices', 'meetingPoints'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_id' => 'required|integer|exists:tours,id',
            'session_id' => 'required|integer|exists:tour_sessions,id',
            'date' => 'required|date',

            'qty_adult' => 'required|integer|min:0',
            'qty_child' => 'required|integer|min:0',
            'qty_infant' => 'required|integer|min:0',

            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',

            'pickup_location_id' => 'nullable|integer|exists:pickup_locations,id',
            'meeting_point_id' => 'nullable|integer|exists:pickup_locations,id',
        ]);

        $adults = (int)$data['qty_adult'];
        $children = (int)$data['qty_child'];
        $infants = (int)$data['qty_infant'];
        $totalGuests = $adults + $children + $infants;

        // เลือก pickup_location_id จาก hotel หรือ meeting point
        $pickupLocationId = $data['pickup_location_id'] ?? null;
        if (!$pickupLocationId && !empty($data['meeting_point_id'])) {
            $pickupLocationId = (int)$data['meeting_point_id'];
        }

        // ✅ บังคับ: ต้องเลือกอย่างใดอย่างหนึ่ง
        if (!$pickupLocationId) {
            return back()->withErrors([
                'pickup_location_id' => 'กรุณาเลือกโรงแรม (ในเขตรับส่ง) หรือเลือกจุดนัดรับ',
            ])->withInput();
        }

        // TODO: คำนวณราคาแบบจริงจังตามระบบคุณ (ตอนนี้ใส่ 0 กันพังก่อน)
        $subtotal = 0; $vat = 0; $fee = 0; $grand = 0;

        $booking = \App\Models\Booking::create([
            'customer_id' => null, // ✅ guest
            'customer_name' => $data['full_name'],
            'customer_phone' => $data['phone'],
            'customer_email' => $data['email'],

            'tour_id' => $data['tour_id'],
            'session_id' => $data['session_id'],
            'date' => $data['date'],

            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_guests' => $totalGuests,

            'subtotal' => $subtotal,
            'vat_amount' => $vat,
            'fee_amount' => $fee,
            'grand_total' => $grand,
            'total_price' => $grand,

            'pickup_location_id' => $pickupLocationId,
            'status' => 'pending', // หรือค่า default ของคุณ
            'created_by' => null,  // ✅ frontend guest
        ]);

        return redirect()->route('frontend.booking.confirmed', $booking->id);
    }

    public function confirmed(\App\Models\Booking $booking)
    {
        return view('frontend.pages.booking.confirmed', compact('booking'));
    }

}
