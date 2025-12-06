<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Tour;
use App\Models\PickupLocation;

use App\Models\TourSession;
use App\Models\TourAvailability; // ของคุณ map กับ table tour_session_availability
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'tour', 'session'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        $tours     = Tour::where('is_active', 1)->orderBy('name')->get();
        $sessions  = TourSession::with('tour')->orderBy('start_time')->get();

        // 👉 เพิ่มส่วนนี้
        $pickupLocations = PickupLocation::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.create', compact(
            'customers',
            'tours',
            'sessions',
            'pickupLocations'
        ));
    }


    public function ajaxSessions(Request $req)
    {
        $tour_id = $req->tour_id;
        $date = $req->date;

        $sessions = TourSession::where('tour_id', $tour_id)
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get()
            ->filter(function ($s) use ($date) {
                return $s->remainingCapacity($date) > 0;
            })
            ->values();

        return response()->json($sessions);
    }


    public function ajaxCapacity(Request $req)
    {
        $session = TourSession::findOrFail($req->session_id);
        $remaining = $session->remainingCapacity($req->date);

        return response()->json([
            'remaining' => $remaining
        ]);
    }

    public function pdf($id)
    {
        $booking = Booking::with(['customer','tour','session','pickupLocation'])
            ->findOrFail($id);

        $pdf = \PDF::loadView('admin.bookings.pdf', compact('booking'))
            ->setPaper('A4');

        return $pdf->stream("booking-{$booking->id}.pdf");
    }

public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'tour_id'     => 'required|exists:tours,id',
        'session_id'  => 'required|exists:tour_sessions,id',
        'date'        => 'required|date',
        'adults'      => 'required|integer|min:1',
        'children'    => 'nullable|integer|min:0',
        'infants'     => 'nullable|integer|min:0',
        'pickup_location_id' => 'nullable|exists:pickup_locations,id',
    ]);

    $tour    = Tour::findOrFail($request->tour_id);
    $session = TourSession::findOrFail($request->session_id);

    $adults   = (int) $request->adults;
    $children = (int) ($request->children ?? 0);
    $infants  = (int) ($request->infants ?? 0);

    $totalGuests = $adults + $children + $infants;

    // ---------------------------
    // 1) เช็คที่ว่างด้วย remainingCapacity()
    // ---------------------------
    $remaining = $session->remainingCapacity($request->date);

    if ($remaining < $totalGuests) {
        return back()
            ->withErrors([
                'date' => "ที่นั่งไม่เพียงพอ (เหลือ $remaining ที่นั่ง)",
            ])
            ->withInput();
    }

    // ---------------------------
    // 2) คำนวนราคา: subtotal, VAT, fee, total
    // ---------------------------
    $pricePerPerson = $tour->min_price ?? 0;
    $subtotal       = $pricePerPerson * $totalGuests;

    $vatRate    = 0.07; // VAT 7%
    $feeRate    = 0.05; // Fees 5%

    $vat_amount = $subtotal * $vatRate;
    $fee_amount = $subtotal * $feeRate;

    $grand_total = $subtotal + $vat_amount + $fee_amount;

    // total_price เดิม → ให้ = grand_total เพื่อความเข้ากันได้
    $totalPrice = $grand_total;

    // ---------------------------
    // 3) สร้าง Booking
    // ---------------------------
    Booking::create([
        'customer_id'        => $request->customer_id,
        'tour_id'            => $tour->id,
        'session_id'         => $session->id,
        'date'               => $request->date,
        'adults'             => $adults,
        'children'           => $children,
        'infants'            => $infants,
        'total_guests'       => $totalGuests,

        // ราคาแบบละเอียด
        'subtotal'           => $subtotal,
        'vat_amount'         => $vat_amount,
        'fee_amount'         => $fee_amount,
        'grand_total'        => $grand_total,

        'total_price'        => $totalPrice,

        'pickup_location_id' => $request->pickup_location_id,
        'status'             => 'confirmed',
        'created_by'         => Auth::id(),
    ]);

    return redirect()
        ->route('admin.bookings.index')
        ->with('success', 'สร้าง Booking สำเร็จ');
}


public function show($id)
{
    $booking = Booking::with(['customer', 'tour', 'session', 'pickupLocation'])->findOrFail($id);
    return view('admin.bookings.show', compact('booking'));
}


public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    $booking->update(['status' => 'cancelled']);

    return back()->with('success', 'ยกเลิกการจองเรียบร้อย');
}


    /**
     * ฟังก์ชันคำนวณ Capacity ของ Session ในวันนั้น
     */
    protected function getCapacityForDate(Tour $tour, TourSession $session, string $date): int
    {
        // 1) หา Availability รายวันก่อน
        $availability = TourAvailability::where('tour_id', $tour->id)
            ->where('session_id', $session->id)
            ->where('date', $date)
            ->first();

        // 2) ถ้ามี availability และ is_open = 0 → หมายถึงปิด
        if ($availability && !$availability->is_open) {
            return 0; // ปิดเลย
        }

        // 3) ตัดสินใจ capacity
        if ($availability && $availability->capacity_override) {
            return (int) $availability->capacity_override;
        }

        // 4) ถ้าใน session เองมี capacity override
        if (!empty($session->capacity)) {
            return (int) $session->capacity;
        }

        // 5) ค่า default
        return (int) $session->default_capacity;
    }

    public function edit($id)
{
    $booking = Booking::with(['customer','tour','session','pickupLocation'])->findOrFail($id);

    $customers = Customer::orderBy('full_name')->get();
    $tours     = Tour::where('is_active', 1)->orderBy('name')->get();

    // sessions ของทัวร์นี้ทั้งหมด
    $sessions  = TourSession::where('tour_id', $booking->tour_id)
        ->orderBy('start_time')
        ->get();

    $pickupLocations = PickupLocation::where('is_active', 1)
        ->orderBy('name')
        ->get();

    return view('admin.bookings.edit', compact(
        'booking',
        'customers',
        'tours',
        'sessions',
        'pickupLocations'
    ));
}


public function update(Request $request, $id)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'tour_id'     => 'required|exists:tours,id',
        'session_id'  => 'required|exists:tour_sessions,id',
        'date'        => 'required|date',
        'adults'      => 'required|integer|min:1',
        'children'    => 'nullable|integer|min:0',
        'infants'     => 'nullable|integer|min:0',
        'pickup_location_id' => 'nullable|exists:pickup_locations,id',
        'status'      => 'required|string',
    ]);

    $booking = Booking::findOrFail($id);
    $tour    = Tour::findOrFail($request->tour_id);
    $session = TourSession::findOrFail($request->session_id);

    $adults   = (int) $request->adults;
    $children = (int) ($request->children ?? 0);
    $infants  = (int) ($request->infants ?? 0);

    $totalGuests = $adults + $children + $infants;

    // 1) capacity
    $remaining = $session->remainingCapacity($request->date);

    // ถ้า guest ของ booking เดิมยังอยู่ ต้องกัน capacity เดิมไว้
    $remaining += $booking->total_guests;

    if ($remaining < $totalGuests) {
        return back()->withErrors([
            'date' => "ที่นั่งไม่เพียงพอ (เหลือ $remaining ที่นั่ง)",
        ])->withInput();
    }

    // 2) ราคาละเอียด
    $pricePerPerson = $tour->min_price;
    $subtotal = $pricePerPerson * $totalGuests;

    $vat = $subtotal * 0.07;
    $fee = $subtotal * 0.05;

    $grand_total = $subtotal + $vat + $fee;

    // 3) update booking
    $booking->update([
        'customer_id'        => $request->customer_id,
        'tour_id'            => $tour->id,
        'session_id'         => $session->id,
        'date'               => $request->date,

        'adults'             => $adults,
        'children'           => $children,
        'infants'            => $infants,
        'total_guests'       => $totalGuests,

        'subtotal'           => $subtotal,
        'vat_amount'         => $vat,
        'fee_amount'         => $fee,
        'grand_total'        => $grand_total,
        'total_price'        => $grand_total,

        'pickup_location_id' => $request->pickup_location_id,
        'status'             => $request->status,
    ]);

    return redirect()->route('admin.bookings.index')
        ->with('success', 'อัปเดต Booking สำเร็จ');
}


}
