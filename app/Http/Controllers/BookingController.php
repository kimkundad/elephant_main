<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSession;
use App\Models\PickupLocation; // ถ้ามี model นี้
use Carbon\Carbon;
use App\Models\Booking;
use Stripe\StripeClient;


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


    private function isWithinChiangMaiBounds(float $lat, float $lng): bool
    {
        // ===== Chiang Mai City bounds (ตามที่คุณใช้ใน frontend) =====
        $swLat = 18.730; $swLng = 98.930;
        $neLat = 18.840; $neLng = 99.050;

        return $lat >= $swLat && $lat <= $neLat
            && $lng >= $swLng && $lng <= $neLng;
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

            // Google / Manual pickup
            'google_place_id' => 'nullable|string|max:255',
            'google_place_name' => 'nullable|string|max:255',
            'google_place_address' => 'nullable|string|max:255',
            'google_lat' => 'nullable|numeric',
            'google_lng' => 'nullable|numeric',
            'pickup_source' => 'nullable|in:google,manual',
            'manual_address' => 'nullable|string|max:500',

            // Meeting point (required if out of bounds)
            'meeting_point_id' => 'nullable|integer|exists:pickup_locations,id',

            // การชำระเงิน
            'pay_type' => 'required|in:full,deposit',
            'payment_channel' => 'required|in:card,promptpay',
        ]);

        $tour = Tour::findOrFail($data['tour_id']);

        $adults = (int)$data['qty_adult'];
        $children = (int)$data['qty_child'];
        $infants = (int)$data['qty_infant'];
        $totalGuests = $adults + $children + $infants;

        // ===== Pickup logic (สำคัญ) =====
        $lat = isset($data['google_lat']) ? (float)$data['google_lat'] : null;
        $lng = isset($data['google_lng']) ? (float)$data['google_lng'] : null;

        $hasLatLng = ($lat !== null && $lng !== null);

        // ถ้าไม่ได้เลือกจาก Google และไม่ได้ปักหมุด -> บังคับให้ทำอย่างใดอย่างหนึ่ง
        if (!$hasLatLng) {
            return back()->withErrors([
                'google_place_name' => 'กรุณาเลือกโรงแรม/ที่พักจากรายการ หรือกรอกที่อยู่และปักหมุดบนแผนที่',
            ])->withInput();
        }

        $inBounds = $this->isWithinChiangMaiBounds($lat, $lng);

        // ถ้าอยู่นอก bounds -> ต้องเลือก meeting point
        if (!$inBounds && empty($data['meeting_point_id'])) {
            return back()->withErrors([
                'meeting_point_id' => 'ที่พักอยู่นอกเขตรับส่ง กรุณาเลือก “จุดนัดรับ”',
            ])->withInput();
        }

        // เก็บ pickup_location_id เฉพาะกรณี meeting point (นอกเขต)
        $pickupLocationId = null;
        if (!$inBounds) {
            $pickupLocationId = (int)$data['meeting_point_id'];
        }

        // ===== ราคา (adult=min_price, child=50%, infant=0) =====
        $priceAdult = (int)($tour->min_price ?? 0);
        $priceChild = (int)round($priceAdult * 0.5);
        $subtotal = ($adults * $priceAdult) + ($children * $priceChild); // infants ฟรี

        $vatRate = 0.07;
        $vat = round($subtotal * $vatRate, 2);
        $fee = 0;
        $grand = round($subtotal + $vat + $fee, 2);

        // ===== จ่ายเต็ม / มัดจำ =====
        $depositPercent = (float) (config('booking.deposit_percent') ?? env('BOOKING_DEPOSIT_PERCENT', 0.30));
        $amountDueNow = $grand;
        $amountPayLater = 0;

        if ($data['pay_type'] === 'deposit') {
            $amountDueNow = round($grand * $depositPercent, 2);
            $amountPayLater = round($grand - $amountDueNow, 2);
        }

        // ===== สร้าง Booking =====
        $booking = Booking::create([
            'customer_id' => null,
            'customer_name' => $data['full_name'],
            'customer_phone' => $data['phone'],
            'customer_email' => $data['email'],
            'public_code' => \Illuminate\Support\Str::random(32),

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

            // ถ้าอยู่นอกเขต -> เก็บเป็น meeting point id
            'pickup_location_id' => $pickupLocationId,

            'status' => 'pending',
            'created_by' => null,

            'payment_status' => $data['payment_channel'] === 'promptpay' ? 'awaiting_qr' : 'pending',
            'payment_channel' => $data['payment_channel'],
            'amount_due_now' => $amountDueNow,
            'amount_pay_later' => $amountPayLater,
        ]);

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $currency = env('STRIPE_CURRENCY', 'thb');

        // Stripe ใช้หน่วย "สตางค์"
        $amountSatang = (int) round($amountDueNow * 100);

        // ===== 1) จ่ายด้วยบัตร: Stripe Checkout =====
        if ($data['payment_channel'] === 'card') {
            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => $currency,
                        'unit_amount' => $amountSatang,
                        'product_data' => [
                            'name' => 'Booking #' . $booking->id . ' - ' . ($tour->name ?? 'Tour'),
                        ],
                    ],
                ]],
                'customer_email' => $booking->customer_email,
                'metadata' => [
                    'booking_id' => (string)$booking->id,
                    'pay_type' => $data['pay_type'],
                ],
                'success_url' => route('frontend.booking.success', $booking->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('frontend.booking.cancel', $booking->id),
            ]);

            $booking->update([
                'stripe_session_id' => $session->id,
            ]);

            return redirect()->away($session->url);
        }

        // ===== 2) จ่ายด้วย QR (PromptPay): PaymentIntent =====
        $intent = $stripe->paymentIntents->create([
            'amount' => $amountSatang,
            'currency' => $currency,
            'payment_method_types' => ['promptpay'],

            'confirm' => true,
            'payment_method_data' => [
                'type' => 'promptpay',
                'billing_details' => [
                    'email' => $booking->customer_email,
                    'name'  => $booking->customer_name,
                    'phone' => $booking->customer_phone,
                ],
            ],

            'receipt_email' => $booking->customer_email,
            'metadata' => [
                'booking_id' => (string)$booking->id,
                'pay_type' => $data['pay_type'],
            ],
        ]);

        $booking->update([
            'stripe_payment_intent_id' => $intent->id,
        ]);

        $qrPng = data_get($intent, 'next_action.promptpay_display_qr_code.image_url_png');
        $expiresAt = data_get($intent, 'next_action.promptpay_display_qr_code.expires_at');

        return view('frontend.pages.booking.promptpay', [
            'booking' => $booking,
            'qrPng' => $qrPng,
            'expiresAt' => $expiresAt,
        ]);
    }

    public function confirmed(\App\Models\Booking $booking)
    {
        return view('frontend.pages.booking.confirmed', compact('booking'));
    }

    public function paymentStatus(\App\Models\Booking $booking)
    {
        // สถานะจาก DB (สำคัญสุด เพราะ webhook เป็นคนอัปเดต)
        $data = [
            'booking_status' => $booking->status,              // pending / confirmed
            'payment_status' => $booking->payment_status,      // awaiting_qr / paid / failed
        ];

        // ถ้าไม่มี PI ก็ส่งแค่นี้
        if (!$booking->stripe_payment_intent_id) {
            return response()->json($data);
        }

        // (optional) แนบสถานะจาก Stripe เพื่อ debug
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $intent = $stripe->paymentIntents->retrieve($booking->stripe_payment_intent_id, []);
            $data['stripe_status'] = $intent->status; // requires_action / processing / succeeded
        } catch (\Throwable $e) {
            $data['stripe_status'] = 'unknown';
        }

        return response()->json($data);
    }


}
