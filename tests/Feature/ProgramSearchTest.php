<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourTag;
use App\Models\TourTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProgramSearchTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Tour $feeding;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->withSession(['locale' => 'en']);

        $phuket = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต', 'name_en' => 'Phuket']);

        $this->feeding = $this->makeTour($phuket, [
            'name' => 'ให้อาหารช้าง-LLE',
            'short_description' => 'ให้อาหารช้างและเดินชมธรรมชาติ',
        ]);
        TourTranslation::create([
            'tour_id' => $this->feeding->id,
            'locale' => 'en',
            'name' => 'Feed elephants - LLE',
            'short_description' => 'Learn about elephants and help prepare their food.',
        ]);

        $other = $this->makeTour($this->chiangMai(), ['name' => 'เดินป่ากับช้าง CCH']);
        TourTranslation::create([
            'tour_id' => $other->id,
            'locale' => 'en',
            'name' => 'Jungle trekking CCH',
        ]);
    }

    private function results(string $term): string
    {
        return $this->get('/programs?q=' . urlencode($term))->assertOk()->getContent();
    }

    public function test_search_finds_text_in_the_english_translation(): void
    {
        $html = $this->results('prepare their food');

        $this->assertStringContainsString('Feed elephants - LLE', $html);
        $this->assertStringNotContainsString('Jungle trekking CCH', $html);
    }

    public function test_search_finds_the_english_tour_name(): void
    {
        $this->assertStringContainsString('Feed elephants - LLE', $this->results('Feed elephants'));
    }

    public function test_search_still_finds_the_thai_copy(): void
    {
        $this->assertStringContainsString('Feed elephants - LLE', $this->results('ให้อาหารช้าง'));
    }

    public function test_search_finds_a_province_name(): void
    {
        $html = $this->results('Phuket');

        $this->assertStringContainsString('Feed elephants - LLE', $html);
        $this->assertStringNotContainsString('Jungle trekking CCH', $html);
    }

    public function test_search_still_finds_a_tag(): void
    {
        $tag = TourTag::create(['name_th' => 'ครอบครัว', 'name_en' => 'Family friendly', 'slug' => 'family', 'is_active' => true]);
        $this->feeding->tags()->attach($tag->id);

        $this->assertStringContainsString('Feed elephants - LLE', $this->results('Family friendly'));
    }

    public function test_a_term_that_matches_nothing_returns_no_tours(): void
    {
        $html = $this->results('scuba diving lesson');

        $this->assertStringNotContainsString('Feed elephants - LLE', $html);
        $this->assertStringNotContainsString('Jungle trekking CCH', $html);
    }
}
