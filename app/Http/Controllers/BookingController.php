<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DiscountCode;
use App\Models\PickupLocation;
use App\Models\Tour;
use App\Models\TourSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $tourId = $request->query('tour');
        $sessionId = $request->query('session');
        $date = $request->query('date');

        if ($date && Carbon::parse($date)->lt(now()->startOfDay())) {
            $date = now()->toDateString();
        }

        $tour = Tour::with('translations')->findOrFail($tourId);
        $session = TourSession::findOrFail($sessionId);

        $prices = [
            'adult' => (int) ($tour->min_price ?? 0),
            'child' => (int) round(($tour->min_price ?? 0) * 0.5),
            'infant' => 0,
        ];

        $meetingPoints = PickupLocation::query()
            ->where('is_active', 1)
            ->where('is_meeting_point', 1)
            ->orderBy('name')
            ->get();

        $availablePaymentChannels = $this->availablePaymentChannels();

        return view('frontend.pages.booking.create', compact(
            'tour',
            'session',
            'date',
            'prices',
            'meetingPoints',
            'availablePaymentChannels'
        ));
    }

    public function createV2(Request $request)
    {
        $tourId = $request->query('tour');
        $sessionId = $request->query('session');
        $date = $request->query('date');

        if ($date && Carbon::parse($date)->lt(now()->startOfDay())) {
            $date = now()->toDateString();
        }

        $tour = Tour::with('translations')->findOrFail($tourId);
        $session = TourSession::findOrFail($sessionId);

        $prices = [
            'adult' => (int) ($tour->min_price ?? 0),
            'child' => (int) round(($tour->min_price ?? 0) * 0.5),
            'infant' => 0,
        ];

        $meetingPoints = PickupLocation::query()
            ->where('is_active', 1)
            ->where('is_meeting_point', 1)
            ->orderBy('name')
            ->get();

        $availablePaymentChannels = $this->availablePaymentChannels();

        return view('frontend_v2.pages.booking.create', compact(
            'tour',
            'session',
            'date',
            'prices',
            'meetingPoints',
            'availablePaymentChannels'
        ));
    }

    private function availablePaymentChannels(): array
    {
        $configured = config('services.stripe.enabled_payment_channels', ['card']);

        if (!is_array($configured)) {
            $configured = ['card'];
        }

        $allowed = ['card', 'promptpay'];

        return array_values(array_intersect($allowed, array_unique($configured))) ?: ['card'];
    }

    private function promptPayUnavailableMessage(): string
    {
        if (app()->getLocale() === 'th') {
            return 'PromptPay ยังไม่เปิดใช้งานบน Stripe account นี้ กรุณาเลือกชำระด้วยบัตร';
        }

        return 'PromptPay is not enabled for this Stripe account. Please choose card payment.';
    }

    private function isWithinChiangMaiBounds(float $lat, float $lng): bool
    {
        $swLat = 18.730;
        $swLng = 98.930;
        $neLat = 18.840;
        $neLng = 99.050;

        return $lat >= $swLat && $lat <= $neLat
            && $lng >= $swLng && $lng <= $neLng;
    }

    public function store(Request $request)
    {
      //  dd($request->all());
        $availablePaymentChannels = $this->availablePaymentChannels();

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
            'self_drive' => 'nullable|boolean',

            'google_place_id' => 'nullable|string|max:255',
            'google_place_name' => 'nullable|string|max:255',
            'google_place_address' => 'nullable|string|max:255',
            'google_lat' => 'nullable|numeric',
            'google_lng' => 'nullable|numeric',
            'pickup_source' => 'nullable|in:google,manual,meeting_point,self_drive',
            'manual_address' => 'nullable|string|max:500',

            'meeting_point_id' => 'nullable|integer|exists:pickup_locations,id',

            'payment_channel' => ['required', Rule::in($availablePaymentChannels)],
            'discount_code' => 'nullable|string|max:50',
        ]);

        $isV2 = $request->boolean('booking_v2');
        $tour = Tour::with('translations')->findOrFail($data['tour_id']);

        $adults = (int) $data['qty_adult'];
        $children = (int) $data['qty_child'];
        $infants = (int) $data['qty_infant'];
        $totalGuests = $adults + $children + $infants;
        $selfDrive = $request->boolean('self_drive');

        $lat = isset($data['google_lat']) ? (float) $data['google_lat'] : null;
        $lng = isset($data['google_lng']) ? (float) $data['google_lng'] : null;
        $hasLatLng = ($lat !== null && $lng !== null);
        $selectedMeetingPointId = !empty($data['meeting_point_id']) ? (int) $data['meeting_point_id'] : null;

        if (!$selfDrive && !$hasLatLng && !$selectedMeetingPointId) {
            return back()->withErrors([
                'google_place_name' => __('booking.errors.pickup_required'),
            ])->withInput();
        }

        $inBounds = (!$selfDrive && $hasLatLng) ? $this->isWithinChiangMaiBounds($lat, $lng) : false;
        if (!$selfDrive && !$selectedMeetingPointId && !$inBounds) {
            return back()->withErrors([
                'meeting_point_id' => __('booking.errors.meeting_point_required'),
            ])->withInput();
        }

        $pickupLocationId = $selfDrive ? null : $selectedMeetingPointId;
        $pickupSource = null;
        $pickupPlaceName = null;
        $pickupPlaceAddress = null;

        if ($selfDrive) {
            $pickupSource = 'self_drive';
        } elseif ($pickupLocationId) {
            $meetingPoint = PickupLocation::find($pickupLocationId);
            $pickupSource = 'meeting_point';
            $pickupPlaceName = $meetingPoint?->name;
        } else {
            $pickupSource = $data['pickup_source'] ?? 'google';
            $pickupPlaceName = $data['google_place_name'] ?? null;
            $pickupPlaceAddress = $data['google_place_address'] ?? null;
        }

        $priceAdult = (int) ($tour->min_price ?? 0);
        $priceChild = (int) round($priceAdult * 0.5);
        $subtotal = ($adults * $priceAdult) + ($children * $priceChild);

        $vatRate = 0.07;
        $vat = round($subtotal * $vatRate, 2);
        $fee = 0;
        $grand = round($subtotal + $vat + $fee, 2);
        $discountAmount = 0;
        $discountCode = null;
        $agentId = null;

        if (!empty($data['discount_code'])) {
            $discountCode = strtoupper(trim($data['discount_code']));
            $discount = $this->resolveDiscountCode($discountCode);

            if (!$discount['valid']) {
                return back()->withErrors([
                    'discount_code' => $discount['message'] ?? __('booking.errors.discount_invalid'),
                ])->withInput();
            }

            $discountAmount = min($grand, (float) $discount['amount']);
            $agentId = $discount['agent_id'] ?? null;
        }

        $grandAfterDiscount = max(0, round($grand - $discountAmount, 2));
        if ($grandAfterDiscount < 10.00) {
            return back()->withErrors([
                'discount_code' => __('booking.errors.min_charge'),
            ])->withInput();
        }

        $booking = null;

        try {
            DB::transaction(function () use (
                &$booking,
                $data,
                $adults,
                $children,
                $infants,
                $totalGuests,
                $subtotal,
                $vat,
                $fee,
                $grandAfterDiscount,
                $discountAmount,
                $discountCode,
                $agentId,
                $pickupLocationId,
                $selfDrive,
                $pickupSource,
                $pickupPlaceName,
                $pickupPlaceAddress
            ) {
                if ($discountCode) {
                    $discountRow = DiscountCode::where('code', $discountCode)->lockForUpdate()->first();
                    if (!$discountRow || !$this->isDiscountCodeUsable($discountRow)) {
                        throw new \RuntimeException(__('booking.errors.discount_invalid'));
                    }
                    if ($discountRow->max_uses > 0 && $discountRow->used_count >= $discountRow->max_uses) {
                        throw new \RuntimeException(__('booking.errors.discount_limit_reached'));
                    }
                }

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
                    'grand_total' => $grandAfterDiscount,
                    'total_price' => $grandAfterDiscount,

                    'discount_code' => $discountCode,
                    'discount_amount' => $discountAmount,
                    'agent_id' => $agentId,
                    'pickup_location_id' => $pickupLocationId,
                    'self_drive' => $selfDrive,
                    'pickup_source' => $pickupSource,
                    'pickup_place_name' => $pickupPlaceName,
                    'pickup_place_address' => $pickupPlaceAddress,

                    'status' => 'pending',
                    'created_by' => null,

                    'payment_status' => $data['payment_channel'] === 'promptpay' ? 'awaiting_qr' : 'pending',
                    'payment_channel' => $data['payment_channel'],
                    'amount_due_now' => $grandAfterDiscount,
                    'amount_pay_later' => 0,
                ]);

                if ($discountCode) {
                    $discountRow = DiscountCode::where('code', $discountCode)->lockForUpdate()->first();
                    $discountRow->increment('used_count');
                    $booking->update([
                        'discount_code_id' => $discountRow->id,
                        'agent_id' => $discountRow->agent_id,
                    ]);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors([
                'discount_code' => $e->getMessage(),
            ])->withInput();
        }

        $stripe = new StripeClient(config('services.stripe.secret'));
        $currency = env('STRIPE_CURRENCY', 'thb');
        $amountSatang = (int) round($grandAfterDiscount * 100);

        if ($data['payment_channel'] === 'card') {
            $tourName = optional($tour->translation(app()->getLocale()))->name ?: ($tour->name ?? __('booking.labels.tour_fallback'));
            $successRoute = $isV2 ? route('frontend.booking.success.v2', $booking->id) : route('frontend.booking.success', $booking->id);
            $cancelRoute = $isV2 ? route('frontend.booking.cancel.v2', $booking->id) : route('frontend.booking.cancel', $booking->id);

            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => $currency,
                        'unit_amount' => $amountSatang,
                        'product_data' => [
                            'name' => __('booking.stripe.product_name', ['id' => $booking->id, 'tour' => $tourName]),
                        ],
                    ],
                ]],
                'customer_email' => $booking->customer_email,
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                    'discount_code' => $discountCode,
                    'discount_amount' => (string) $discountAmount,
                ],
                'success_url' => $successRoute . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelRoute,
            ]);

            $booking->update([
                'stripe_session_id' => $session->id,
            ]);

            return redirect()->away($session->url);
        }

        try {
            $intent = $stripe->paymentIntents->create([
                'amount' => $amountSatang,
                'currency' => $currency,
                'payment_method_types' => ['promptpay'],
                'confirm' => true,
                'payment_method_data' => [
                    'type' => 'promptpay',
                    'billing_details' => [
                        'email' => $booking->customer_email,
                        'name' => $booking->customer_name,
                        'phone' => $booking->customer_phone,
                    ],
                ],
                'receipt_email' => $booking->customer_email,
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                    'discount_code' => $discountCode,
                    'discount_amount' => (string) $discountAmount,
                ],
            ]);
        } catch (InvalidRequestException $e) {
            $message = strtolower($e->getMessage());

            if (str_contains($message, 'payment_method_types') || str_contains($message, 'promptpay')) {
                return back()->withErrors([
                    'payment_channel' => $this->promptPayUnavailableMessage(),
                ])->withInput();
            }

            throw $e;
        }

        $booking->update([
            'stripe_payment_intent_id' => $intent->id,
        ]);

        return view($isV2 ? 'frontend_v2.pages.booking.promptpay' : 'frontend.pages.booking.promptpay', [
            'booking' => $booking,
            'qrPng' => data_get($intent, 'next_action.promptpay_display_qr_code.image_url_png'),
            'expiresAt' => data_get($intent, 'next_action.promptpay_display_qr_code.expires_at'),
        ]);
    }

    public function confirmed(\App\Models\Booking $booking)
    {
        return view('frontend.pages.booking.confirmed', compact('booking'));
    }

    public function confirmedV2(\App\Models\Booking $booking)
    {
        return view('frontend_v2.pages.booking.confirmed', compact('booking'));
    }

    public function paymentStatus(\App\Models\Booking $booking)
    {
        $data = [
            'booking_status' => $booking->status,
            'payment_status' => $booking->payment_status,
        ];

        if (!$booking->stripe_payment_intent_id) {
            return response()->json($data);
        }

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $intent = $stripe->paymentIntents->retrieve($booking->stripe_payment_intent_id, []);
            $data['stripe_status'] = $intent->status;
        } catch (\Throwable $e) {
            $data['stripe_status'] = 'unknown';
        }

        return response()->json($data);
    }

    public function validateDiscount(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('code', '')));
        if ($code === '') {
            return response()->json([
                'valid' => false,
                'message' => __('booking.errors.discount_required'),
            ], 422);
        }

        $result = $this->resolveDiscountCode($code);
        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'] ?? __('booking.errors.discount_invalid'),
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'amount' => (float) $result['amount'],
            'agent_id' => $result['agent_id'] ?? null,
        ]);
    }

    private function resolveDiscountCode(string $code): array
    {
        $discount = DiscountCode::where('code', $code)->first();
        if (!$discount || !$this->isDiscountCodeUsable($discount)) {
            return ['valid' => false, 'message' => __('booking.errors.discount_invalid')];
        }

        if ($discount->max_uses > 0 && $discount->used_count >= $discount->max_uses) {
            return ['valid' => false, 'message' => __('booking.errors.discount_limit_reached')];
        }

        return [
            'valid' => true,
            'amount' => (float) $discount->amount,
            'agent_id' => $discount->agent_id,
        ];
    }

    private function isDiscountCodeUsable(DiscountCode $discount): bool
    {
        if (!$discount->is_active) {
            return false;
        }

        $now = now();
        if ($discount->starts_at && $now->lt($discount->starts_at)) {
            return false;
        }
        if ($discount->ends_at && $now->gt($discount->ends_at)) {
            return false;
        }

        return true;
    }
}
