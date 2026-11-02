<?php

namespace Tests\Feature;

use App\Models\Elephant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ElephantReadMoreTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function makeElephant(string $slug, ?int $tourId): void
    {
        Elephant::create([
            'name' => $slug,
            'slug' => $slug,
            'history' => 'Rescued from logging.',
            'images' => ['https://example.com/' . $slug . '.jpg'],
            'is_active' => true,
            'tour_id' => $tourId,
        ]);
    }

    /** The Meet Our Elephants section alone: the program cards above it link to tours too. */
    private function meetSection(): string
    {
        $html = $this->get(route('frontend.home'))->assertOk()->getContent();
        $start = strpos($html, 'class="home-section meet-elephants-section-v2"');
        $this->assertNotFalse($start);

        return substr($html, $start, strpos($html, 'google-reviews-section', $start) - $start);
    }

    public function test_read_more_opens_the_elephants_program(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['slug' => 'short-visit-feeding-shower-kcp']);
        $this->makeElephant('ploy', $tour->id);

        $this->assertStringContainsString(
            'href="' . route('frontend.tours.show.v2', $tour->slug) . '" class="btn-book"',
            $this->meetSection()
        );
    }

    public function test_read_more_without_a_program_stays_on_our_elephants(): void
    {
        $this->makeElephant('ploy', null);

        $this->assertStringContainsString(
            'href="' . route('frontend.elephants') . '#ploy" class="btn-book"',
            $this->meetSection()
        );
    }
}
