<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use App\Mail\BookingConfirmedMail;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Throwable $e) {
            Log::error('Stripe webhook invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        $isTestMode = str_starts_with((string) env('STRIPE_SECRET'), 'sk_test_');

        /**
         * 1) Card (Checkout)
         * event: checkout.session.completed
         */
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;

            if (!$bookingId) {
                Log::warning('checkout.session.completed missing booking_id', [
                    'stripe_session_id' => $session->id ?? null
                ]);
                return response('ok', 200);
            }

            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found', [
                    'booking_id' => $bookingId,
                    'stripe_session_id' => $session->id ?? null
                ]);
                return response('ok', 200);
            }

            $this->markPaidAndSendOnce($booking, [
                'payment_channel' => 'card',
                'stripe_session_id' => $session->id,
            ]);

            return response('ok', 200);
        }

        /**
         * 2.5) PromptPay TEST MODE auto-paid
         * event: payment_intent.requires_action (เฉพาะ test mode เท่านั้น)
         */
        if ($isTestMode && $event->type === 'payment_intent.requires_action') {
            $pi = $event->data->object;

            $booking = Booking::where('stripe_payment_intent_id', $pi->id)->first();

            if (!$booking) {
                Log::warning('Test requires_action: Booking not found by stripe_payment_intent_id', [
                    'pi_id' => $pi->id ?? null
                ]);
                return response('ok', 200);
            }

            $this->markPaidAndSendOnce($booking, [
                'payment_channel' => 'promptpay',
                'stripe_payment_intent_id' => $pi->id,
            ]);

            return response('ok', 200);
        }

        /**
         * 2) PromptPay (PaymentIntent)
         * event: payment_intent.succeeded
         */
        if ($event->type === 'payment_intent.succeeded') {
            $pi = $event->data->object;

            $bookingId = $pi->metadata->booking_id ?? null;

            $booking = null;

            if ($bookingId) {
                $booking = Booking::find($bookingId);
            }

            // fallback: หา booking จาก pi id
            if (!$booking) {
                $booking = Booking::where('stripe_payment_intent_id', $pi->id)->first();
            }

            if (!$booking) {
                Log::warning('payment_intent.succeeded: Booking not found', [
                    'booking_id' => $bookingId,
                    'pi_id' => $pi->id ?? null
                ]);
                return response('ok', 200);
            }

            $this->markPaidAndSendOnce($booking, [
                'payment_channel' => 'promptpay',
                'stripe_payment_intent_id' => $pi->id,
            ]);

            return response('ok', 200);
        }

        /**
         * (option) failed
         * event: payment_intent.payment_failed
         */
        if ($event->type === 'payment_intent.payment_failed') {
            $pi = $event->data->object;
            $bookingId = $pi->metadata->booking_id ?? null;

            $booking = null;

            if ($bookingId) {
                $booking = Booking::find($bookingId);
            }

            if (!$booking) {
                $booking = Booking::where('stripe_payment_intent_id', $pi->id)->first();
            }

            if ($booking) {
                // ถ้าเคย paid แล้ว ไม่ควร downgrade เป็น failed
                if ($booking->payment_status !== 'paid') {
                    $booking->payment_status = 'failed';
                    $booking->save();
                }
            } else {
                Log::warning('payment_failed: Booking not found', [
                    'booking_id' => $bookingId,
                    'pi_id' => $pi->id ?? null
                ]);
            }

            return response('ok', 200);
        }

        return response('ok', 200);
    }

    /**
     * ✅ mark paid + ส่งเมลครั้งเดียว
     * - กัน webhook ซ้ำด้วย confirmation_email_sent_at
     * - สร้าง public_code ถ้ายังไม่มี
     * - สร้าง QR (public URL) ใส่อีเมล
     */
    private function markPaidAndSendOnce(Booking $booking, array $updateData = []): void
    {
        // อัปเดตสถานะจ่ายเงิน (อย่าเขียนทับ paid_at ถ้ามีอยู่แล้ว)
        $booking->fill(array_merge([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'paid_at' => $booking->paid_at ?? now(),
        ], $updateData));

        // สร้าง public_code สำหรับ QR (ถ้ายังไม่มี)
        if (!$booking->public_code) {
            $booking->public_code = Str::random(32);
        }

        // ถ้าเคยส่งเมลแล้ว -> save update แล้วจบ
        if ($booking->confirmation_email_sent_at) {
            $booking->save();
            return;
        }

        // save ก่อน เพื่อให้ public_code ถูกบันทึกแน่ ๆ
        $booking->save();

        // สร้าง URL สำหรับ QR
        $publicUrl = route('booking.public', ['code' => $booking->public_code]);

        // QR เป็น PNG → embed ด้วย data URI (ใช้ได้ดีกับ email)
        
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($publicUrl)
            ->encoding(new Encoding('UTF-8'))
            ->size(220)
            ->margin(1)
            ->build();

        $qrPngBinary = $result->getString(); // ✅ PNG binary

        // ส่งเมล (แก้ field email ให้ตรงกับของคุณ)
        $to = $booking->customer_email ?? null;

        if (!$to) {
            Log::error('Cannot send email: customer_email is null', [
                'booking_id' => $booking->id
            ]);
            return;
        }

        try {
            Mail::to($to)->send(
                new BookingConfirmedMail($booking, $qrPngBinary, $publicUrl)
            );

            // เซ็ตว่า “ส่งแล้ว” กันซ้ำ
            $booking->confirmation_email_sent_at = now();
            $booking->save();
        } catch (\Throwable $e) {
            // ถ้าส่งเมลไม่สำเร็จ อย่า mark ว่าส่งแล้ว
            Log::error('Send email failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
