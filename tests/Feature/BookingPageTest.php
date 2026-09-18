<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingPageTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function bookingUrl($tour): string
    {
        return route('frontend.booking.create.v2', [
            'tour' => $tour->id,
            'session' => $this->makeSession($tour)->id,
            'date' => now()->addDays(5)->toDateString(),
        ]);
    }

    public function test_dropdown_lists_only_active_pickups_of_the_tours_province(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_en' => 'Phuket']);
        $tour = $this->makeTour($phuket);
        $this->makePickup($phuket, ['name' => 'Bangtao Zone']);
        $this->makePickup($phuket, ['name' => 'Patong Meeting Point', 'is_meeting_point' => true]);
        $this->makePickup($phuket, ['name' => 'Closed Hotel', 'is_active' => false]);
        $this->makePickup($this->makeProvince('krabi'), ['name' => 'Ao Nang Hotel']);

        $this->get($this->bookingUrl($tour))
            ->assertOk()
            ->assertSee('name="pickup_location_id"', false)
            ->assertSee('name="pickup_note"', false)
            ->assertSee('Bangtao Zone')
            ->assertSee('Patong Meeting Point')
            ->assertDontSee('Closed Hotel')
            ->assertDontSee('Ao Nang Hotel');
    }

    public function test_page_no_longer_loads_google_maps(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));

        $this->get($this->bookingUrl($tour))
            ->assertOk()
            ->assertDontSee('maps.googleapis.com', false)
            ->assertDontSee('initHotelAutocomplete', false);
    }

    public function test_tour_in_inactive_province_returns_404(): void
    {
        $tour = $this->makeTour($this->makeProvince('krabi', ['is_active' => false]));

        $this->get($this->bookingUrl($tour))->assertNotFound();
    }
}
