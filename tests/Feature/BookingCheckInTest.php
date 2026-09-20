<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingCheckInTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private const PIN = '482913';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.checkin.pin' => self::PIN]);
    }

    private function booking(array $attrs = []): Booking
    {
        $province = $this->makeProvince('phuket-' . uniqid());
        $tour = $this->makeTour($province, ['name' => 'Morning Tour']);

        return Booking::create(array_merge([
            'public_code' => 'CODE' . uniqid(),
            'customer_name' => 'Anna',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->toDateString(),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 2,
            'subtotal' => 2000,
            'vat_amount' => 140,
            'grand_total' => 2140,
            'total_price' => 2140,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ], $attrs));
    }

    public function test_staff_with_the_pin_checks_a_guest_in(): void
    {
        $booking = $this->booking();

        $this->post(route('booking.public.check-in', $booking->public_code), [
            'pin' => self::PIN,
            'staff_name' => 'Nok',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertNotNull($booking->checked_in_at);
        $this->assertSame('Nok', $booking->checked_in_by);
    }

    public function test_a_wrong_pin_changes_nothing(): void
    {
        $booking = $this->booking();

        $this->post(route('booking.public.check-in', $booking->public_code), ['pin' => '000000'])
            ->assertSessionHasErrors('pin');

        $this->assertNull($booking->fresh()->checked_in_at);
    }

    public function test_an_unpaid_booking_cannot_be_checked_in(): void
    {
        $booking = $this->booking(['payment_status' => 'pending', 'paid_at' => null]);

        $this->post(route('booking.public.check-in', $booking->public_code), ['pin' => self::PIN])
            ->assertSessionHasErrors('pin');

        $this->assertNull($booking->fresh()->checked_in_at);

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('Check-in unavailable')
            ->assertDontSee('Guest arrived?');
    }

    public function test_checking_in_twice_keeps_the_first_time(): void
    {
        $booking = $this->booking();

        $this->post(route('booking.public.check-in', $booking->public_code), ['pin' => self::PIN]);
        $first = $booking->fresh()->checked_in_at;

        $this->travel(10)->minutes();
        $this->post(route('booking.public.check-in', $booking->public_code), ['pin' => self::PIN]);

        $this->assertEquals($first, $booking->fresh()->checked_in_at);
    }

    public function test_staff_can_undo_a_check_in(): void
    {
        $booking = $this->booking(['checked_in_at' => now(), 'checked_in_by' => 'Nok']);

        $this->post(route('booking.public.check-in.undo', $booking->public_code), ['pin' => self::PIN])
            ->assertRedirect();

        $this->assertNull($booking->fresh()->checked_in_at);
    }

    public function test_the_button_is_hidden_when_no_pin_is_configured(): void
    {
        config(['services.checkin.pin' => null]);
        $booking = $this->booking();

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertDontSee('Guest arrived?');
    }

    public function test_a_booking_for_another_day_warns_but_still_allows_check_in(): void
    {
        $booking = $this->booking(['date' => now()->addDays(4)->toDateString()]);

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('not today');

        $this->post(route('booking.public.check-in', $booking->public_code), ['pin' => self::PIN]);

        $this->assertNotNull($booking->fresh()->checked_in_at);
    }

    public function test_the_admin_shows_the_check_in(): void
    {
        $booking = $this->booking(['checked_in_at' => now(), 'checked_in_by' => 'Nok']);

        $this->actingAsAdmin();

        $this->get(route('admin.bookings.show', $booking->id))
            ->assertOk()
            ->assertSee('เช็คอินหน้างาน')
            ->assertSee('Nok');

        $this->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('เช็คอิน');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('เช็คอินแล้ว 1/1');
    }
}
