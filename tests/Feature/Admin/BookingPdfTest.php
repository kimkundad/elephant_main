<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingPdfTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_the_footer_uses_the_real_contact_details(): void
    {
        SiteSetting::create([
            'email' => 'info@smallelephants.com',
            'phone' => '0863261564',
            'phone_secondary' => '0922560213',
            'address' => '504/53 Kanchanabhisek Road, Bangkok',
        ]);

        $province = $this->makeProvince('phuket');
        $tour = $this->makeTour($province);
        $booking = Booking::create([
            'public_code' => 'PDFCODE123',
            'customer_name' => 'Anna',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 1,
            'subtotal' => 1000,
            'vat_amount' => 70,
            'fee_amount' => 0,
            'grand_total' => 1070,
            'total_price' => 1070,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $html = view('admin.bookings.pdf', ['booking' => $booking->fresh(['tour', 'session', 'pickupLocation'])])->render();

        $this->assertStringNotContainsString('support@example.com', $html);
        $this->assertStringContainsString('info@smallelephants.com', $html);
        $this->assertStringContainsString('0863261564', $html);
        $this->assertStringContainsString('0922560213', $html);
        $this->assertStringContainsString('504/53 Kanchanabhisek Road, Bangkok', $html);
        $this->assertStringContainsString('Small Elephants', $html);
        // The 5% fee was dropped, so the row only appears when there is one.
        $this->assertStringNotContainsString('Fees', $html);
    }

    public function test_fee_and_discount_rows_appear_when_they_have_a_value(): void
    {
        $province = $this->makeProvince('phuket');
        $tour = $this->makeTour($province);
        $booking = Booking::create([
            'public_code' => 'PDFCODE789',
            'customer_name' => 'Anna',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 1,
            'subtotal' => 1000,
            'vat_amount' => 70,
            'fee_amount' => 50,
            'discount_amount' => 200,
            'discount_code' => 'AGENT10',
            'grand_total' => 920,
            'total_price' => 920,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $html = view('admin.bookings.pdf', ['booking' => $booking->fresh(['tour', 'session', 'pickupLocation'])])->render();

        $this->assertStringContainsString('Fees', $html);
        $this->assertStringContainsString('AGENT10', $html);
        $this->assertStringContainsString('- THB 200.00', $html);
    }

    public function test_the_footer_works_without_site_settings(): void
    {
        $province = $this->makeProvince('phuket');
        $tour = $this->makeTour($province);
        $booking = Booking::create([
            'public_code' => 'PDFCODE456',
            'customer_name' => 'Anna',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 1,
            'subtotal' => 1000,
            'vat_amount' => 70,
            'fee_amount' => 0,
            'grand_total' => 1070,
            'total_price' => 1070,
            'status' => 'confirmed',
            'payment_status' => 'pending',
        ]);

        $html = view('admin.bookings.pdf', ['booking' => $booking->fresh(['tour', 'session', 'pickupLocation'])])->render();

        $this->assertStringContainsString('Thank you for booking', $html);
        $this->assertStringNotContainsString('support@example.com', $html);
    }
}
