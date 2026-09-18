<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class AdminBookingPickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_admin_cannot_use_a_pickup_from_another_province(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));
        $session = $this->makeSession($tour);
        $otherPickup = $this->makePickup($this->makeProvince('krabi'));
        $customer = Customer::create(['full_name' => 'Guest', 'email' => 'g@example.com', 'phone' => '0800000000']);

        $this->actingAsAdmin()
            ->post(route('admin.bookings.store'), [
                'customer_id' => $customer->id,
                'tour_id' => $tour->id,
                'session_id' => $session->id,
                'date' => now()->addDays(3)->toDateString(),
                'adults' => 1,
                'pickup_location_id' => $otherPickup->id,
            ])
            ->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_create_form_tags_tours_and_pickups_with_their_province(): void
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $pickup = $this->makePickup($phuket);

        $this->actingAsAdmin()
            ->get(route('admin.bookings.create'))
            ->assertOk()
            ->assertSee('value="' . $tour->id . '" data-province="' . $phuket->id . '"', false)
            ->assertSee('value="' . $pickup->id . '" data-province="' . $phuket->id . '"', false)
            ->assertSee('name="pickup_note"', false);
    }

    public function test_admin_booking_saves_pickup_and_note(): void
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $session = $this->makeSession($tour);
        $pickup = $this->makePickup($phuket);
        $customer = Customer::create(['full_name' => 'Guest', 'email' => 'g@example.com', 'phone' => '0800000000']);

        $this->actingAsAdmin()
            ->post(route('admin.bookings.store'), [
                'customer_id' => $customer->id,
                'tour_id' => $tour->id,
                'session_id' => $session->id,
                'date' => now()->addDays(3)->toDateString(),
                'adults' => 1,
                'pickup_location_id' => $pickup->id,
                'pickup_note' => 'Villa 12',
            ])
            ->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'tour_id' => $tour->id,
            'pickup_location_id' => $pickup->id,
            'pickup_note' => 'Villa 12',
        ]);
    }
}
