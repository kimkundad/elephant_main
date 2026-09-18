<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingStorePickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function payload(array $overrides = []): array
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $session = $this->makeSession($tour);

        return array_merge([
            'booking_v2' => 1,
            'tour_id' => $tour->id,
            'session_id' => $session->id,
            'date' => now()->addDays(5)->toDateString(),
            'qty_adult' => 1,
            'qty_child' => 0,
            'qty_infant' => 0,
            'full_name' => 'Test Guest',
            'phone' => '0812345678',
            'email' => 'guest@example.com',
            'payment_channel' => 'card',
        ], $overrides);
    }

    public function test_pickup_from_another_province_is_rejected_before_saving(): void
    {
        $otherPickup = $this->makePickup($this->makeProvince('krabi'));

        $this->post(route('frontend.booking.store'), $this->payload([
            'pickup_location_id' => $otherPickup->id,
        ]))->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_missing_pickup_without_self_drive_is_rejected(): void
    {
        $this->post(route('frontend.booking.store'), $this->payload())
            ->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_tour_in_inactive_province_cannot_be_booked(): void
    {
        $closed = $this->makeProvince('krabi', ['is_active' => false]);
        $tour = $this->makeTour($closed);

        $this->post(route('frontend.booking.store'), $this->payload([
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'self_drive' => 1,
        ]))->assertNotFound();
    }

    public function test_note_longer_than_1000_characters_is_rejected(): void
    {
        $this->post(route('frontend.booking.store'), $this->payload([
            'self_drive' => 1,
            'pickup_note' => str_repeat('a', 1001),
        ]))->assertSessionHasErrors('pickup_note');
    }
}
