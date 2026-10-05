<?php

namespace Tests\Feature;

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class SelfDriveOptionTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function bookingPage(Tour $tour): \Illuminate\Testing\TestResponse
    {
        return $this->get(route('frontend.booking.create.v2', [
            'tour' => $tour->id,
            'session' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
        ]));
    }

    public function test_a_tour_that_allows_it_offers_the_checkbox(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['allows_self_drive' => true, 'pickup_lead_hours' => 1]);
        $this->makePickup($tour->province);

        $this->bookingPage($tour)
            ->assertOk()
            ->assertSee('name="self_drive"', false);
    }

    public function test_a_tour_that_forbids_it_never_shows_the_checkbox(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['allows_self_drive' => false, 'pickup_lead_hours' => 1]);
        $this->makePickup($tour->province);

        $this->bookingPage($tour)
            ->assertOk()
            ->assertDontSee('name="self_drive"', false);
    }

    public function test_the_note_tells_self_drivers_when_to_be_at_the_venue(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['allows_self_drive' => true, 'pickup_lead_hours' => 1]);
        $this->makePickup($tour->province);

        $this->bookingPage($tour)
            ->assertOk()
            // 09:30 start, 20 minutes before.
            ->assertSee('data-arrive-clock="09:10"', false)
            ->assertSee('arrive at the venue at least 20 minutes before the activity starts', false);
    }

    public function test_a_posted_self_drive_is_refused_when_the_tour_forbids_it(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['allows_self_drive' => false]);
        $session = $this->makeSession($tour);
        $this->makePickup($tour->province);

        $this->post(route('frontend.booking.store'), [
            'booking_v2' => 1,
            'tour_id' => $tour->id,
            'session_id' => $session->id,
            'date' => now()->addDays(3)->toDateString(),
            'qty_adult' => 1,
            'qty_child' => 0,
            'qty_infant' => 0,
            'self_drive' => 1,
            'full_name' => 'Anna Schmidt',
            'phone' => '0958467417',
            'phone_country' => 'th',
            'email' => 'anna@example.com',
            'payment_channel' => 'promptpay',
        ])->assertSessionHasErrors('self_drive');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_admin_can_switch_it_off_for_a_tour(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['allows_self_drive' => true]);

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'province_id' => $tour->province_id,
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'price_adult' => 1000,
                'price_child' => 500,
            ])
            ->assertSessionHasNoErrors();

        $this->assertFalse($tour->fresh()->allows_self_drive, 'An unticked switch posts nothing.');
    }
}
