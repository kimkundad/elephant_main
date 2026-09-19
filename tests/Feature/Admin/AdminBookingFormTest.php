<?php

namespace Tests\Feature\Admin;

use App\Models\Agent;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Tour;
use App\Models\TourSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class AdminBookingFormTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Province $province;
    private Tour $tour;
    private TourSession $session;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->province = $this->makeProvince('phuket');
        $this->tour = $this->makeTour($this->province, ['min_price' => 1000]);
        $this->session = $this->makeSession($this->tour);
        $this->customer = Customer::create([
            'full_name' => 'Anna Schmidt',
            'email' => 'anna@example.com',
            'phone' => '0812345678',
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'customer_id' => $this->customer->id,
            'tour_id' => $this->tour->id,
            'session_id' => $this->session->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 1,
            'infants' => 2,
            'status' => 'confirmed',
            'payment_status' => 'pending',
        ], $overrides);
    }

    public function test_price_matches_the_public_booking_formula(): void
    {
        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload());

        // 2 adults (2000) + 1 child at half price (500) + 2 free infants, VAT 7%, no fee.
        $this->assertDatabaseHas('bookings', [
            'subtotal' => 2500,
            'vat_amount' => 175,
            'fee_amount' => 0,
            'grand_total' => 2675,
            'total_price' => 2675,
        ]);
    }

    public function test_booking_gets_a_public_code_and_guest_snapshot(): void
    {
        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload());

        $booking = Booking::firstOrFail();

        $this->assertSame(32, strlen($booking->public_code));
        $this->assertSame('Anna Schmidt', $booking->customer_name);
        $this->assertSame('anna@example.com', $booking->customer_email);
        $this->assertSame('0812345678', $booking->customer_phone);
    }

    public function test_self_drive_clears_the_pickup_point(): void
    {
        $pickup = $this->makePickup($this->province);

        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload([
            'self_drive' => 1,
            'pickup_location_id' => $pickup->id,
            'pickup_note' => 'ignored',
        ]));

        $booking = Booking::firstOrFail();

        $this->assertTrue($booking->self_drive);
        $this->assertNull($booking->pickup_location_id);
        $this->assertNull($booking->pickup_note);
        $this->assertSame('self_drive', $booking->pickup_source);
    }

    public function test_paid_booking_records_the_channel_and_paid_at(): void
    {
        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload([
            'payment_status' => 'paid',
            'payment_channel' => 'cash',
        ]));

        $booking = Booking::firstOrFail();

        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame('cash', $booking->payment_channel);
        $this->assertNotNull($booking->paid_at);
        $this->assertEquals($booking->grand_total, $booking->amount_due_now);
    }

    public function test_agent_and_discount_are_saved_and_reduce_the_total(): void
    {
        $agent = Agent::create(['name' => 'Somchai', 'is_active' => true]);

        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload([
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'agent_id' => $agent->id,
            'discount_code' => 'AGENT10',
            'discount_amount' => 200,
        ]));

        $booking = Booking::firstOrFail();

        $this->assertSame($agent->id, $booking->agent_id);
        $this->assertSame('AGENT10', $booking->discount_code);
        $this->assertEquals(200, $booking->discount_amount);
        // 1000 + 70 VAT - 200 discount
        $this->assertEquals(870, $booking->grand_total);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.bookings.store'), $this->payload(['status' => 'whatever']))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_moving_to_another_session_does_not_credit_the_old_seats(): void
    {
        $small = TourSession::create([
            'tour_id' => $this->tour->id,
            'title' => 'Small Program',
            'start_time' => '13:30:00',
            'end_time' => '16:00:00',
            'default_capacity' => 2,
            'is_active' => 1,
        ]);

        $date = now()->addDays(3)->toDateString();

        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload([
            'adults' => 3,
            'children' => 0,
            'infants' => 0,
            'date' => $date,
        ]));

        $booking = Booking::firstOrFail();

        $this->actingAsAdmin()
            ->post(route('admin.bookings.update', $booking->id), $this->payload([
                'adults' => 3,
                'children' => 0,
                'infants' => 0,
                'date' => $date,
                'session_id' => $small->id,
            ]))
            ->assertSessionHasErrors('date');

        $this->assertSame($this->session->id, $booking->fresh()->session_id);
    }

    public function test_update_keeps_the_original_paid_at(): void
    {
        $this->actingAsAdmin()->post(route('admin.bookings.store'), $this->payload([
            'payment_status' => 'paid',
            'payment_channel' => 'transfer',
        ]));

        $booking = Booking::firstOrFail();
        $paidAt = $booking->paid_at;

        $this->travel(2)->days();

        $this->actingAsAdmin()->post(route('admin.bookings.update', $booking->id), $this->payload([
            'payment_status' => 'paid',
            'payment_channel' => 'transfer',
        ]));

        $this->assertEquals($paidAt, $booking->fresh()->paid_at);
    }
}
