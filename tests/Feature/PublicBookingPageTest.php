<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class PublicBookingPageTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function booking(array $attrs = []): Booking
    {
        $province = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต', 'name_en' => 'Phuket']);
        $tour = $this->makeTour($province, ['name' => 'Morning Feeding Tour']);
        $pickup = $this->makePickup($province, ['name' => 'Bangtao Zone']);

        return Booking::create(array_merge([
            'public_code' => 'PUBLICCODE123',
            'customer_name' => 'Anna Schmidt',
            'customer_phone' => '+66958467417',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'total_guests' => 3,
            'subtotal' => 2500,
            'vat_amount' => 175,
            'grand_total' => 2675,
            'total_price' => 2675,
            'discount_amount' => 0,
            'pickup_location_id' => $pickup->id,
            'pickup_note' => 'Villa 12',
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_channel' => 'promptpay',
            'paid_at' => now(),
        ], $attrs));
    }

    public function test_page_shows_the_site_name_not_another_sanctuary(): void
    {
        $booking = $this->booking();

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('Small Elephants')
            ->assertDontSee('Phuket Elephant Sanctuary');
    }

    public function test_page_shows_every_detail_a_guide_needs(): void
    {
        $booking = $this->booking();

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('#' . str_pad($booking->id, 6, '0', STR_PAD_LEFT))
            ->assertSee('Anna Schmidt')
            ->assertSee('+66958467417')
            ->assertSee('anna@example.com')
            ->assertSee('2 adults, 1 children')
            ->assertSee('Morning Feeding Tour')
            ->assertSee('Morning Program')
            ->assertSee('09:30 - 12:00')
            ->assertSee('Phuket')
            ->assertSee('Bangtao Zone')
            ->assertSee('Villa 12')
            ->assertSee('2,675.00')
            ->assertSee('PROMPTPAY')
            ->assertSee('PAID');
    }

    public function test_unpaid_booking_is_flagged(): void
    {
        $booking = $this->booking(['payment_status' => 'pending', 'paid_at' => null]);

        $this->get(route('booking.public', $booking->public_code))
            ->assertOk()
            ->assertSee('NOT PAID');
    }
}
