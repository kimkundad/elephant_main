<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class PickupTimeTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function tourWithLead(?float $hours): Tour
    {
        return $this->makeTour($this->chiangMai(), [
            'name' => 'Full day walking with elephants',
            'pickup_lead_hours' => $hours,
        ]);
    }

    public function test_pickup_time_is_the_start_time_minus_the_lead(): void
    {
        $this->assertSame('08:30', $this->tourWithLead(1)->pickupTimeFor('09:30:00'));
        $this->assertSame('07:00', $this->tourWithLead(2.5)->pickupTimeFor('09:30:00'));
        $this->assertSame('23:30', $this->tourWithLead(1)->pickupTimeFor('00:30:00'));
    }

    public function test_no_pickup_time_without_a_lead(): void
    {
        $this->assertNull($this->tourWithLead(null)->pickupTimeFor('09:30:00'));
        $this->assertNull($this->tourWithLead(0)->pickupTimeFor('09:30:00'));
        $this->assertNull($this->tourWithLead(1)->pickupTimeFor(null));
    }

    public function test_booking_page_shows_the_pickup_time(): void
    {
        $tour = $this->tourWithLead(1);
        $session = $this->makeSession($tour);
        $this->makePickup($tour->province);

        $this->get(route('frontend.booking.create.v2', [
            'tour' => $tour->id,
            'session' => $session->id,
            'date' => now()->addDays(3)->toDateString(),
        ]))
            ->assertOk()
            ->assertSee('08:30')
            ->assertSee('Pickup time');
    }

    public function test_confirmation_email_and_qr_page_show_the_pickup_time(): void
    {
        $tour = $this->tourWithLead(1.5);
        $booking = $this->bookingFor($tour);

        $html = (new BookingConfirmedMail($booking, 'fake-png', 'https://example.test/b/PICKUPCODE'))->render();

        $this->assertStringContainsString('Pickup time: 08:00', $html);

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('08:00');
    }

    public function test_self_drivers_are_told_when_to_reach_the_camp(): void
    {
        $tour = $this->tourWithLead(1);
        $booking = $this->bookingFor($tour, ['self_drive' => true]);

        $this->assertSame('Arrive at the camp by', $booking->pickupTimeLabel('en'));

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('Arrive at the camp by');
    }

    public function test_admin_session_form_shows_the_computed_pickup_time(): void
    {
        $tour = $this->tourWithLead(1);
        $session = $this->makeSession($tour);

        $this->actingAsAdmin()
            ->get(route('admin.tours.sessions.edit', [$tour->id, $session->id]))
            ->assertOk()
            ->assertSee('เวลารับลูกค้า (Pickup time)')
            ->assertSee('ก่อนเริ่มทัวร์ 1 ชม.');

        $this->actingAsAdmin()
            ->get(route('admin.tours.sessions.create', $tour->id))
            ->assertOk()
            ->assertSee('เวลารับลูกค้า (Pickup time)');
    }

    public function test_the_session_tables_list_the_pickup_time(): void
    {
        $tour = $this->tourWithLead(1);
        $this->makeSession($tour);

        $this->actingAsAdmin()
            ->get(route('admin.sessions.all'))
            ->assertOk()
            ->assertSee('เวลารับลูกค้า')
            ->assertSee('08:30')
            ->assertDontSee('Session Time');

        $this->actingAsAdmin()
            ->get(route('admin.tours.sessions.index', $tour->id))
            ->assertOk()
            ->assertSee('เวลารับลูกค้า')
            ->assertSee('08:30')
            ->assertDontSee('Session Time');
    }

    public function test_admin_can_save_the_lead_time_on_a_tour(): void
    {
        $tour = $this->tourWithLead(null);

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'province_id' => $tour->province_id,
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'price_adult' => 1000,
                'price_child' => 500,
                'pickup_lead_hours' => '2.5',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(2.5, $tour->fresh()->pickup_lead_hours);
    }

    private function bookingFor(Tour $tour, array $attrs = []): Booking
    {
        $session = $this->makeSession($tour);

        return Booking::create(array_merge([
            'public_code' => 'PICKUPCODE',
            'customer_name' => 'Anna Schmidt',
            'customer_phone' => '+66958467417',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $session->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 2,
            'subtotal' => 2000,
            'vat_amount' => 140,
            'grand_total' => 2140,
            'total_price' => 2140,
            'discount_amount' => 0,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_channel' => 'promptpay',
            'paid_at' => now(),
        ], $attrs));
    }
}
