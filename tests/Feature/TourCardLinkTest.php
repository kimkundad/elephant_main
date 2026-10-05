<?php

namespace Tests\Feature;

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class TourCardLinkTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Tour $tour;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tour = $this->makeTour($this->chiangMai(), [
            'name' => 'Full day walking with elephants',
            'thumbnail' => 'uploads/tour.jpg',
        ]);
    }

    public function test_the_programs_card_opens_the_tour_from_the_picture_and_the_title(): void
    {
        $url = route('frontend.tours.show.v2', $this->tour->slug);

        $html = $this->get(route('frontend.program'))->assertOk()->getContent();

        $this->assertStringContainsString('<a class="program-media" href="' . $url . '"', $html);
        $this->assertStringContainsString('<div class="program-title"><a href="' . $url . '">', $html);
    }

    public function test_the_home_card_opens_the_tour_from_the_picture_and_the_title(): void
    {
        $url = route('frontend.tours.show.v2', $this->tour->slug);

        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertStringContainsString('<a class="elephant-card__media" href="' . $url . '"', $html);
        $this->assertStringContainsString('<div class="elephant-name"><a href="' . $url . '">', $html);
    }

    public function test_the_picture_link_is_skipped_by_screen_readers_and_the_keyboard(): void
    {
        // The title right below goes to the same place, so offering the picture
        // again would only add a stop that announces nothing.
        $html = $this->get(route('frontend.program'))->assertOk()->getContent();

        $this->assertStringContainsString('class="program-media" href', $html);
        $this->assertMatchesRegularExpression('/<a class="program-media"[^>]*tabindex="-1"[^>]*aria-hidden="true"/', $html);
    }
}
