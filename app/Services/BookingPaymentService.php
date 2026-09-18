<?php

namespace App\Services;

use App\Models\Booking;
use App\Support\IntegrationLogger;
use Illuminate\Support\Str;

class BookingPaymentService
{
    /**
     * Mark a booking as paid and fire the confirmation notifications.
     *
     * Safe to call more than once: the paid state is claimed with a
     * conditional update, and the notification service keeps its own
     * "sent once" guards. Returns true when this call is the one that
     * flipped the booking to paid.
     */
    public function markPaidAndNotify(Booking $booking, array $updateData = []): bool
    {
        $claimed = Booking::where('id', $booking->id)
            ->where('payment_status', '!=', 'paid')
            ->update(array_merge([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
                'paid_at'        => now(),
                'public_code'    => $booking->public_code ?: Str::random(32),
            ], $updateData));

        $booking->refresh();

        if ($claimed === 0 && $booking->payment_status !== 'paid') {
            // The booking row disappeared or the update was rejected.
            IntegrationLogger::warning('stripe', 'mark_paid_failed', 'Could not mark booking as paid', [
                'booking_id' => $booking->id,
            ]);

            return false;
        }

        (new BookingNotificationService())->sendConfirmation($booking);

        return $claimed > 0;
    }
}
