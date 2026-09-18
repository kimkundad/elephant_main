<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProgramProvinceFilterTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        // SetLocale middleware reads the locale from the session on every request.
        $this->withSession(['locale' => 'en']);
    }

    public function test_province_param_filters_tours(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_en' => 'Phuket']);
        $this->makeTour($phuket, ['name' => 'Phuket Sanctuary Walk']);
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        $this->get('/programs?province=phuket')
            ->assertOk()
            ->assertSee('Phuket Sanctuary Walk')
            ->assertDontSee('Chiang Mai Feeding');
    }

    public function test_unknown_province_shows_everything(): void
    {
        $this->makeTour($this->makeProvince('phuket'), ['name' => 'Phuket Sanctuary Walk']);
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        $this->get('/programs?province=atlantis')
            ->assertOk()
            ->assertSee('Phuket Sanctuary Walk')
            ->assertSee('Chiang Mai Feeding');
    }

    public function test_chips_hidden_with_one_active_province(): void
    {
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        // Match the element, not the CSS rule of the same name.
        $this->get('/programs')->assertOk()->assertDontSee('class="program-provinces"', false);
    }

    public function test_chips_shown_with_two_active_provinces(): void
    {
        $this->makeProvince('phuket', ['name_en' => 'Phuket']);

        $this->get('/programs')->assertOk()->assertSee('class="program-provinces"', false)->assertSee('Phuket');
    }

    public function test_tours_in_inactive_province_are_hidden_everywhere(): void
    {
        $closed = $this->makeProvince('krabi', ['is_active' => false]);
        $tour = $this->makeTour($closed, ['name' => 'Krabi Hidden Tour']);

        $this->get('/programs')->assertDontSee('Krabi Hidden Tour');
        $this->get('/')->assertDontSee('Krabi Hidden Tour');
        $this->get(route('frontend.tours.show.v2', $tour->slug))->assertNotFound();
    }

    public function test_tour_page_shows_province(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket', ['name_en' => 'Phuket']));
        $this->makeSession($tour);

        $this->get(route('frontend.tours.show.v2', $tour->slug))
            ->assertOk()
            ->assertSee('class="tour-province"', false)
            ->assertSee('Phuket');
    }
}
