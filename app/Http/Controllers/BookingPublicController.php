<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingPublicController extends Controller
{
    public function show(string $code)
    {
        return view('public.booking', [
            'booking' => $this->booking($code),
            'checkInEnabled' => $this->pin() !== '',
        ]);
    }

    /**
     * The front desk marks the guest as arrived. The page is public, so the
     * staff PIN is what separates staff from guests; without it configured
     * the action stays off.
     */
    public function checkIn(Request $request, string $code)
    {
        $booking = $this->booking($code);
        $pin = $this->pin();

        if ($pin === '') {
            return back()->withErrors(['pin' => 'ยังไม่ได้ตั้งรหัสพนักงานสำหรับเช็คอิน']);
        }

        if (!hash_equals($pin, trim((string) $request->input('pin')))) {
            throw ValidationException::withMessages(['pin' => 'รหัสพนักงานไม่ถูกต้อง']);
        }

        if ($booking->payment_status !== 'paid') {
            return back()->withErrors(['pin' => 'การจองนี้ยังไม่ได้ชำระเงิน จึงเช็คอินไม่ได้']);
        }

        if ($booking->status === 'cancelled') {
            return back()->withErrors(['pin' => 'การจองนี้ถูกยกเลิกแล้ว']);
        }

        // Pressing it twice keeps the first arrival time.
        if (!$booking->checked_in_at) {
            $booking->update([
                'checked_in_at' => now(),
                'checked_in_by' => trim((string) $request->input('staff_name')) ?: null,
            ]);
        }

        return back()->with('checkin_success', true);
    }

    public function undoCheckIn(Request $request, string $code)
    {
        $booking = $this->booking($code);
        $pin = $this->pin();

        if ($pin === '' || !hash_equals($pin, trim((string) $request->input('pin')))) {
            throw ValidationException::withMessages(['pin' => 'รหัสพนักงานไม่ถูกต้อง']);
        }

        $booking->update(['checked_in_at' => null, 'checked_in_by' => null]);

        return back()->with('checkin_undone', true);
    }

    private function booking(string $code): Booking
    {
        return Booking::with(['tour.province', 'session', 'pickupLocation'])
            ->where('public_code', $code)
            ->firstOrFail();
    }

    /**
     * Set in the admin's site settings; the .env value stays as a fallback
     * for installs that configured it there before.
     */
    private function pin(): string
    {
        $pin = trim((string) SiteSetting::first()?->checkin_pin);

        return $pin !== '' ? $pin : trim((string) config('services.checkin.pin'));
    }
}
