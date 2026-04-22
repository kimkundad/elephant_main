<?php

namespace App\Services;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Providers\LineServiceProvider;
use App\Support\IntegrationLogger;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingNotificationService
{
    public function sendConfirmation(Booking $booking): void
    {
        if (!$booking->public_code) {
            $booking->public_code = Str::random(32);
            $booking->save();
        }

        $this->sendEmailOnce($booking);
        $this->sendLineOnce($booking);
    }

    private function sendEmailOnce(Booking $booking): void
    {
        if ($booking->confirmation_email_sent_at) {
            return;
        }

        $to = $booking->customer_email;
        if (!$to) {
            IntegrationLogger::error('mail', 'missing_email', 'Cannot send email: customer_email is null', [
                'booking_id' => $booking->id,
            ]);
            return;
        }

        try {
            $publicUrl = route('booking.public', ['code' => $booking->public_code]);

            $qrPngBinary = Builder::create()
                ->writer(new PngWriter())
                ->data($publicUrl)
                ->encoding(new Encoding('UTF-8'))
                ->size(220)
                ->margin(1)
                ->build()
                ->getString();

            Mail::to($to)->send(new BookingConfirmedMail($booking, $qrPngBinary, $publicUrl));

            $booking->confirmation_email_sent_at = now();
            $booking->save();

            IntegrationLogger::info('mail', 'confirmation_sent', 'Booking confirmation email sent', [
                'booking_id' => $booking->id,
                'to' => $to,
            ]);
        } catch (\Throwable $e) {
            IntegrationLogger::error('mail', 'confirmation_failed', 'Booking confirmation email failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendLineOnce(Booking $booking): void
    {
        if (!config('services.line.enabled')) {
            return;
        }

        $groupId = trim((string) config('services.line.payment_group_id'));
        if ($groupId === '') {
            return;
        }

        $cacheKey = 'booking_paid_line_notified:' . $booking->id;
        if (Cache::has($cacheKey)) {
            return;
        }

        $booking->loadMissing(['tour', 'session']);

        $message = $this->buildLineMessage($booking);
        $result = LineServiceProvider::sendToGroupId($groupId, $message);

        if ($result !== null) {
            Cache::put($cacheKey, now()->toDateTimeString(), now()->addYear());
            IntegrationLogger::info('line', 'payment_notify_success', 'LINE notification sent', [
                'booking_id' => $booking->id,
            ]);
        }
    }

    private function buildLineMessage(Booking $booking): string
    {
        $tourName    = $booking->tour?->name ?: '-';
        $sessionName = $booking->session?->title ?: ($booking->session?->name ?: '-');
        $guestCount  = (int) ($booking->total_guests ?: (($booking->adults ?? 0) + ($booking->children ?? 0) + ($booking->infants ?? 0)));
        $travelDate  = $booking->date ? Carbon::parse($booking->date)->format('d/m/Y') : '-';
        $paidAt      = $booking->paid_at ? Carbon::parse($booking->paid_at)->format('d/m/Y H:i') : now()->format('d/m/Y H:i');
        $amount      = number_format((float) $booking->grand_total, 2);

        return implode("\n", [
            '✅ Payment received',
            'Booking #' . $booking->id,
            'Customer: ' . ($booking->customer_name ?: '-'),
            'Phone: ' . ($booking->customer_phone ?: '-'),
            'Tour: ' . $tourName,
            'Session: ' . $sessionName,
            'Date: ' . $travelDate,
            'Guests: ' . $guestCount,
            'Amount: THB ' . $amount,
            'Channel: ' . strtoupper((string) $booking->payment_channel),
            'Paid at: ' . $paidAt,
        ]);
    }
}
