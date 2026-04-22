<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Str;
use App\Support\IntegrationLogger;
use App\Services\BookingNotificationService;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        IntegrationLogger::info('stripe', 'webhook_received', 'STRIPE WEBHOOK RECEIVED', [
            'has_signature' => !empty($sigHeader),
            'payload_length' => strlen($payload),
        ]);

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Throwable $e) {
            IntegrationLogger::error('stripe', 'invalid_signature', 'Stripe webhook invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        IntegrationLogger::info('stripe', 'webhook_event', 'STRIPE WEBHOOK EVENT', [
            'type' => $event->type,
        ]);

        $isTestMode = str_starts_with((string) env('STRIPE_SECRET'), 'sk_test_');

        /**
         * 1) Card (Checkout)
         * event: checkout.session.completed
         */
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;

            IntegrationLogger::info('stripe', 'checkout_session_completed', 'STRIPE WEBHOOK MATCHED', [
                'type' => 'checkout.session.completed',
                'booking_id' => $bookingId,
                'stripe_session_id' => $session->id ?? null,
            ]);

            if (!$bookingId) {
                IntegrationLogger::warning('stripe', 'checkout_missing_booking_id', 'checkout.session.completed missing booking_id', [
                    'stripe_session_id' => $session->id ?? null
                ]);
                return response('ok', 200);
            }

            $booking = Booking::find($bookingId);

            if (!$booking) {
                IntegrationLogger::warning('stripe', 'booking_not_found', 'Booking not found', [
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

            IntegrationLogger::info('stripe', 'payment_intent_requires_action', 'STRIPE WEBHOOK MATCHED', [
                'type' => 'payment_intent.requires_action',
                'pi_id' => $pi->id ?? null,
            ]);

            $booking = Booking::where('stripe_payment_intent_id', $pi->id)->first();

            if (!$booking) {
                IntegrationLogger::warning('stripe', 'test_requires_action_booking_not_found', 'Test requires_action: Booking not found by stripe_payment_intent_id', [
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

            IntegrationLogger::info('stripe', 'payment_intent_succeeded', 'STRIPE WEBHOOK MATCHED', [
                'type' => 'payment_intent.succeeded',
                'booking_id' => $bookingId,
                'pi_id' => $pi->id ?? null,
            ]);

            $booking = null;

            if ($bookingId) {
                $booking = Booking::find($bookingId);
            }

            // fallback: หา booking จาก pi id
            if (!$booking) {
                $booking = Booking::where('stripe_payment_intent_id', $pi->id)->first();
            }

            if (!$booking) {
                IntegrationLogger::warning('stripe', 'payment_intent_succeeded_booking_not_found', 'payment_intent.succeeded: Booking not found', [
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

            IntegrationLogger::info('stripe', 'payment_intent_failed', 'STRIPE WEBHOOK MATCHED', [
                'type' => 'payment_intent.payment_failed',
                'booking_id' => $bookingId,
                'pi_id' => $pi->id ?? null,
            ]);

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
                IntegrationLogger::warning('stripe', 'payment_failed_booking_not_found', 'payment_failed: Booking not found', [
                    'booking_id' => $bookingId,
                    'pi_id' => $pi->id ?? null
                ]);
            }

            return response('ok', 200);
        }

        return response('ok', 200);
    }

    private function markPaidAndSendOnce(Booking $booking, array $updateData = []): void
    {
        $booking->fill(array_merge([
            'payment_status' => 'paid',
            'status'         => 'confirmed',
            'paid_at'        => $booking->paid_at ?? now(),
        ], $updateData));

        if (!$booking->public_code) {
            $booking->public_code = Str::random(32);
        }

        $booking->save();

        (new BookingNotificationService())->sendConfirmation($booking);
    }
}
