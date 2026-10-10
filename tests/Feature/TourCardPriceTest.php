<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class TourCardPriceTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeTour($this->chiangMai(), [
            'name' => 'Full day walking with elephants',
            'price_adult' => 2500,
            'price_child' => 1800,
        ]);
    }

    public function test_the_programs_page_cards_show_both_prices(): void
    {
        $this->get(route('frontend.program'))
            ->assertOk()
            ->assertSee('Adult THB 2,500')
            ->assertSee('Child THB 1,800')
            ->assertDontSee('From THB');
    }

    public function test_the_home_page_cards_show_both_prices(): void
    {
        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('Adult THB 2,500')
            ->assertSee('Child THB 1,800')
            ->assertDontSee('From THB');
    }

    public function test_the_tour_page_leaves_the_prices_to_the_booking_page(): void
    {
        $tour = \App\Models\Tour::first();

        $this->get(route('frontend.tours.show', $tour->slug))
            ->assertOk()
            ->assertDontSee('Adult THB 2,500')
            ->assertDontSee('Child THB 1,800');
    }

    public function test_the_labels_follow_the_language(): void
    {
        $this->get(route('frontend.locale.switch', 'th'));

        $this->get(route('frontend.program'))
            ->assertOk()
            ->assertSee('ผู้ใหญ่ THB 2,500')
            ->assertSee('เด็ก THB 1,800');
    }
}
