<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingShowTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function makeBooking(array $attrs = []): Booking
    {
        $province = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต']);
        $tour = $this->makeTour($province, ['name' => 'Phuket Walk']);
        $pickup = $this->makePickup($province, ['name' => 'Bangtao Zone']);

        return Booking::create(array_merge([
            'public_code' => 'TESTCODE123',
            'customer_name' => 'Test Guest',
            'customer_phone' => '0812345678',
            'customer_email' => 'guest@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(4)->toDateString(),
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'total_guests' => 3,
            'subtotal' => 3000,
            'vat_amount' => 210,
            'fee_amount' => 0,
            'grand_total' => 3210,
            'total_price' => 3210,
            'discount_amount' => 0,
            'pickup_location_id' => $pickup->id,
            'pickup_note' => 'Villa 12',
            'self_drive' => false,
            'pickup_source' => 'list',
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_channel' => 'promptpay',
            'amount_due_now' => 3210,
            'amount_pay_later' => 0,
            'paid_at' => now(),
        ], $attrs));
    }

    public function test_page_shows_the_booking_details(): void
    {
        $booking = $this->makeBooking();

        $this->actingAsAdmin()
            ->get(route('admin.bookings.show', $booking->id))
            ->assertOk()
            ->assertSee('การจอง #' . str_pad($booking->id, 6, '0', STR_PAD_LEFT))
            ->assertSee('Phuket Walk')
            ->assertSee('ภูเก็ต')
            ->assertSee('Test Guest')
            ->assertSee('guest@example.com')
            ->assertSee('Bangtao Zone')
            ->assertSee('Villa 12')
            ->assertSee('3,210.00')
            ->assertSee('09:30 - 12:00');
    }

    public function test_cancelled_booking_hides_the_cancel_button(): void
    {
        $booking = $this->makeBooking(['status' => 'cancelled']);

        $response = $this->actingAsAdmin()->get(route('admin.bookings.show', $booking->id))->assertOk();

        $response->assertDontSee(route('admin.bookings.cancel', $booking->id), false);
    }

    public function test_self_drive_booking_renders(): void
    {
        $booking = $this->makeBooking([
            'self_drive' => true,
            'pickup_location_id' => null,
            'pickup_note' => null,
            'pickup_source' => 'self_drive',
            'discount_amount' => 200,
            'discount_code' => 'AGENT10',
        ]);

        $this->actingAsAdmin()
            ->get(route('admin.bookings.show', $booking->id))
            ->assertOk()
            ->assertSee('เดินทางไปเอง (Self Drive)')
            ->assertSee('AGENT10')
            ->assertSee('200.00');
    }
}
