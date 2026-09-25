<?php

namespace Tests\Feature\Admin;

use App\Models\TourTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class TourListTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_the_list_shows_both_names(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'ให้อาหารช้าง -KCP']);
        TourTranslation::create(['tour_id' => $tour->id, 'locale' => 'th', 'name' => 'ให้อาหารช้าง -KCP']);
        TourTranslation::create(['tour_id' => $tour->id, 'locale' => 'en', 'name' => 'Short Visit Feeding & Shower - KCP']);

        $this->actingAsAdmin()
            ->get(route('admin.tours.index'))
            ->assertOk()
            ->assertSee('ให้อาหารช้าง -KCP')
            ->assertSee('Short Visit Feeding &amp; Shower - KCP', false);
    }

    public function test_a_tour_without_an_english_name_shows_one_line(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'ทัวร์ไม่มีชื่ออังกฤษ']);
        TourTranslation::create(['tour_id' => $tour->id, 'locale' => 'th', 'name' => 'ทัวร์ไม่มีชื่ออังกฤษ']);

        $html = $this->actingAsAdmin()->get(route('admin.tours.index'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'ทัวร์ไม่มีชื่ออังกฤษ'));
    }
}
