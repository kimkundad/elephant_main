<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Tour;
use App\Models\PickupLocation;

use App\Models\TourSession;
use App\Models\TourAvailability; // ของคุณ map กับ table tour_session_availability
use App\Models\Agent;
use App\Services\BookingPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function export(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');
        $tourId   = $request->query('tour_id');

        $bookings = Booking::with(['customer', 'tour.province', 'session', 'agent', 'discountCode', 'pickupLocation'])
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($tourId,   fn($q) => $q->where('tour_id', $tourId))
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'bookings-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'ID', 'วันที่ไปทัวร์', 'ทัวร์', 'Session', 'เวลา',
            'ชื่อลูกค้า', 'อีเมล', 'โทรศัพท์',
            'ผู้ใหญ่', 'เด็ก', 'ทารก', 'รวม',
            'ราคารวม', 'ส่วนลด', 'โค้ด',
            'จังหวัด', 'จุดรับส่ง', 'รายละเอียดรับส่ง', 'ประเภทการรับส่ง',
            'พนักงานขาย', 'สถานะ', 'วันที่จอง',
        ];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                if ($b->self_drive) {
                    $pickupType = 'Self Drive';
                } elseif ($b->pickupLocation) {
                    $pickupType = $b->pickupLocation->is_meeting_point ? 'Meeting Point' : 'Hotel / Zone';
                } elseif ($b->pickup_place_name) {
                    $pickupType = 'Hotel / Address';
                } else {
                    $pickupType = '-';
                }

                fputcsv($file, [
                    $b->id,
                    $b->date,
                    $b->tour?->name ?? '-',
                    $b->session?->title ?? $b->session?->name ?? '-',
                    $b->session?->time_range ?? '',
                    $b->customer?->full_name ?? $b->customer_name ?? '-',
                    $b->customer?->email ?? $b->customer_email ?? '-',
                    $b->customer?->phone ?? $b->customer_phone ?? '-',
                    $b->adults ?? 0,
                    $b->children ?? 0,
                    $b->infants ?? 0,
                    $b->total_guests ?? 0,
                    number_format($b->total_price ?? 0, 2),
                    number_format($b->discount_amount ?? 0, 2),
                    $b->discount_code ?? '-',
                    $b->tour?->province?->name_th ?? '-',
                    $b->pickupLabel(),
                    $b->pickupDetail() ?? '-',
                    $pickupType,
                    $b->agent?->name ?? '-',
                    $b->status ?? '-',
                    $b->created_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');
        $tourId   = $request->query('tour_id');

        $query = Booking::with(['customer', 'tour', 'session', 'agent', 'discountCode'])
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($tourId,   fn($q) => $q->where('tour_id', $tourId))
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $totalCount = $query->count();
        $bookings   = $query->paginate(20)->withQueryString();
        $tours      = Tour::where('is_active', 1)->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'tours', 'totalCount', 'dateFrom', 'dateTo', 'tourId'));
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

        $agents = Agent::where('is_active', 1)->orderBy('name')->get();

        return view('admin.bookings.create', compact(
            'customers',
            'tours',
            'sessions',
            'pickupLocations',
            'agents'
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

        return response()->json($sessions->each->append('time_range'));
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
    $request->validate($this->bookingRules());

    $tour    = Tour::findOrFail($request->tour_id);

    if ($request->filled('pickup_location_id')
        && !PickupLocation::availableIn((int) $tour->province_id)->whereKey($request->pickup_location_id)->exists()) {
        return back()
            ->withErrors(['pickup_location_id' => 'จุดรับส่งนี้ไม่อยู่ในจังหวัดของทัวร์ที่เลือก'])
            ->withInput();
    }
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
    // 2) คำนวนราคา: ใช้สูตรเดียวกับหน้าบ้าน
    // ---------------------------
    $pricing = (new BookingPricing())->for($tour, $adults, $children, $infants, (float) $request->input('discount_amount', 0));

    // ---------------------------
    // 3) สร้าง Booking
    // ---------------------------
    $customer = Customer::findOrFail($request->customer_id);

    Booking::create(array_merge([
        'customer_id'        => $customer->id,
        // Snapshot the guest details, like the public booking form does, so a
        // later profile edit does not rewrite past bookings.
        'customer_name'      => $customer->full_name,
        'customer_phone'     => $customer->phone,
        'customer_email'     => $customer->email,
        'public_code'        => Str::random(32),

        'tour_id'            => $tour->id,
        'session_id'         => $session->id,
        'date'               => $request->date,
        'adults'             => $adults,
        'children'           => $children,
        'infants'            => $infants,
        'total_guests'       => $totalGuests,

        'status'             => $request->input('status', 'confirmed'),
        'created_by'         => Auth::id(),
    ], $this->pickupAttributes($request), $this->moneyAttributes($request, $pricing), $this->paymentAttributes($request, $pricing)));

    return redirect()
        ->route('admin.bookings.index')
        ->with('success', 'สร้าง Booking สำเร็จ');
}

/** Validation rules shared by the admin create and edit forms. */
private function bookingRules(): array
{
    return [
        'customer_id' => 'required|exists:customers,id',
        'tour_id'     => 'required|exists:tours,id',
        'session_id'  => 'required|exists:tour_sessions,id',
        'date'        => 'required|date',
        'adults'      => 'required|integer|min:1',
        'children'    => 'nullable|integer|min:0',
        'infants'     => 'nullable|integer|min:0',
        'pickup_location_id' => 'nullable|exists:pickup_locations,id',
        'pickup_note' => 'nullable|string|max:1000',
        'self_drive'  => 'nullable|boolean',
        'status'      => 'required|in:pending,confirmed,cancelled',
        'payment_status'  => 'required|in:pending,paid,failed',
        'payment_channel' => 'nullable|in:cash,transfer,card,promptpay',
        'agent_id'        => 'nullable|exists:agents,id',
        'discount_code'   => 'nullable|string|max:50',
        'discount_amount' => 'nullable|numeric|min:0',
    ];
}

/** @return array<string, mixed> */
private function pickupAttributes(Request $request): array
{
    $selfDrive = $request->boolean('self_drive');
    $pickupLocationId = $selfDrive ? null : $request->pickup_location_id;

    return [
        'pickup_location_id' => $pickupLocationId,
        'pickup_note'        => $selfDrive ? null : $request->pickup_note,
        'self_drive'         => $selfDrive,
        'pickup_source'      => $selfDrive ? 'self_drive' : ($pickupLocationId ? 'list' : null),
    ];
}

/**
 * @param  array{subtotal: float, vat: float, fee: float, discount: float, grand_total: float}  $pricing
 * @return array<string, mixed>
 */
private function moneyAttributes(Request $request, array $pricing): array
{
    return [
        'subtotal'        => $pricing['subtotal'],
        'vat_amount'      => $pricing['vat'],
        'fee_amount'      => $pricing['fee'],
        'discount_amount' => $pricing['discount'],
        'discount_code'   => $request->discount_code ?: null,
        'agent_id'        => $request->agent_id ?: null,
        'grand_total'     => $pricing['grand_total'],
        'total_price'     => $pricing['grand_total'],
    ];
}

/**
 * @param  array{grand_total: float}  $pricing
 * @return array<string, mixed>
 */
private function paymentAttributes(Request $request, array $pricing, ?Booking $booking = null): array
{
    $isPaid = $request->payment_status === 'paid';

    return [
        'payment_status'   => $request->payment_status,
        'payment_channel'  => $request->payment_channel ?: null,
        'amount_due_now'   => $pricing['grand_total'],
        'amount_pay_later' => 0,
        // Keep the original timestamp when a booking is already marked paid.
        'paid_at'          => $isPaid ? ($booking?->paid_at ?? now()) : null,
    ];
}


public function show($id)
{
    $booking = Booking::with(['customer', 'tour', 'session', 'pickupLocation', 'agent', 'discountCode'])->findOrFail($id);
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

    $agents = Agent::where('is_active', 1)->orderBy('name')->get();

    return view('admin.bookings.edit', compact(
        'booking',
        'customers',
        'tours',
        'sessions',
        'pickupLocations',
        'agents'
    ));
}


public function update(Request $request, $id)
{
    $request->validate($this->bookingRules());

    $booking = Booking::findOrFail($id);
    $tour    = Tour::findOrFail($request->tour_id);

    if ($request->filled('pickup_location_id')
        && !PickupLocation::availableIn((int) $tour->province_id)->whereKey($request->pickup_location_id)->exists()) {
        return back()
            ->withErrors(['pickup_location_id' => 'จุดรับส่งนี้ไม่อยู่ในจังหวัดของทัวร์ที่เลือก'])
            ->withInput();
    }
    $session = TourSession::findOrFail($request->session_id);

    $adults   = (int) $request->adults;
    $children = (int) ($request->children ?? 0);
    $infants  = (int) ($request->infants ?? 0);

    $totalGuests = $adults + $children + $infants;

    // 1) capacity
    $remaining = $session->remainingCapacity($request->date);

    // Give back the seats this booking already holds, but only when it stays
    // on the same session and date — otherwise a move would be credited twice.
    $staysInPlace = (int) $booking->session_id === (int) $session->id
        && (string) $booking->date === (string) $request->date;

    if ($staysInPlace) {
        $remaining += $booking->total_guests;
    }

    if ($remaining < $totalGuests) {
        return back()->withErrors([
            'date' => "ที่นั่งไม่เพียงพอ (เหลือ $remaining ที่นั่ง)",
        ])->withInput();
    }

    // 2) ราคาละเอียด: ใช้สูตรเดียวกับหน้าบ้าน
    $pricing = (new BookingPricing())->for($tour, $adults, $children, $infants, (float) $request->input('discount_amount', 0));

    $customer = Customer::findOrFail($request->customer_id);

    // 3) update booking
    $booking->update(array_merge([
        'customer_id'        => $customer->id,
        'customer_name'      => $customer->full_name,
        'customer_phone'     => $customer->phone,
        'customer_email'     => $customer->email,
        'public_code'        => $booking->public_code ?: Str::random(32),

        'tour_id'            => $tour->id,
        'session_id'         => $session->id,
        'date'               => $request->date,

        'adults'             => $adults,
        'children'           => $children,
        'infants'            => $infants,
        'total_guests'       => $totalGuests,

        'status'             => $request->status,
    ], $this->pickupAttributes($request), $this->moneyAttributes($request, $pricing), $this->paymentAttributes($request, $pricing, $booking)));

    return redirect()->route('admin.bookings.index')
        ->with('success', 'อัปเดต Booking สำเร็จ');
}


}
