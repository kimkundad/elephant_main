<?php

namespace Tests\Feature\Admin;

use App\Models\Elephant;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ElephantCampTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Tour $tour;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('spaces');
        $this->tour = $this->makeTour($this->chiangMai());
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name_th' => 'พลอย',
            'name_en' => 'Ploy',
            'tour_id' => $this->tour->id,
            'is_active' => 1,
            'images' => [UploadedFile::fake()->image('ploy.jpg')],
            'province_id' => $this->chiangMai()->id,
            'camp_name' => 'ปางช้างแม่แตง',
            'camp_info' => "ควาญ: ลุงสม 081-234-5678\nเข้าทางประตูหลัง",
        ], $overrides);
    }

    private function makeElephant(array $attrs = []): Elephant
    {
        return Elephant::create(array_merge([
            'name' => 'พลอย',
            'slug' => 'ploy',
            'history' => 'Rescued from logging.',
            'images' => ['https://example.com/ploy.jpg'],
            'is_active' => true,
            'tour_id' => $this->tour->id,
        ], $attrs));
    }

    public function test_store_saves_where_the_elephant_lives(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload())
            ->assertSessionHasNoErrors();

        $elephant = Elephant::first();
        $this->assertSame($this->chiangMai()->id, $elephant->province_id);
        $this->assertSame('ปางช้างแม่แตง', $elephant->camp_name);
        $this->assertSame("ควาญ: ลุงสม 081-234-5678\nเข้าทางประตูหลัง", $elephant->camp_info);
    }

    public function test_the_camp_fields_are_optional(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload([
                'province_id' => null, 'camp_name' => null, 'camp_info' => null,
            ]))
            ->assertSessionHasNoErrors();

        $this->assertNull(Elephant::first()->province_id);
    }

    public function test_update_saves_where_the_elephant_lives(): void
    {
        $elephant = $this->makeElephant();

        $this->actingAsAdmin()
            ->put(route('admin.elephants.update', $elephant), $this->payload(['images' => null]))
            ->assertSessionHasNoErrors();

        $this->assertSame('ปางช้างแม่แตง', $elephant->fresh()->camp_name);
    }

    public function test_unknown_province_is_rejected(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload(['province_id' => 999999]))
            ->assertSessionHasErrors('province_id');
    }

    public function test_the_edit_form_shows_the_saved_camp(): void
    {
        $elephant = $this->makeElephant([
            'province_id' => $this->chiangMai()->id,
            'camp_name' => 'ปางช้างแม่แตง',
            'camp_info' => 'เข้าทางประตูหลัง',
        ]);

        $this->actingAsAdmin()->get(route('admin.elephants.edit', $elephant))
            ->assertOk()
            ->assertSee('value="' . $this->chiangMai()->id . '" selected', false)
            ->assertSee('ปางช้างแม่แตง')
            ->assertSee('เข้าทางประตูหลัง');
    }

    public function test_deleting_the_province_keeps_the_elephant(): void
    {
        $province = $this->makeProvince('krabi');
        $elephant = $this->makeElephant(['province_id' => $province->id]);

        $province->delete();

        $this->assertNull($elephant->fresh()->province_id);
    }

    public function test_the_public_pages_never_show_the_camp(): void
    {
        $this->makeElephant(['camp_name' => 'ปางช้างแม่แตง', 'camp_info' => 'ควาญ: ลุงสม 081-234-5678']);

        foreach ([route('frontend.home'), route('frontend.elephants')] as $url) {
            $this->get($url)->assertOk()
                ->assertDontSee('ปางช้างแม่แตง')
                ->assertDontSee('081-234-5678');
        }
    }
}
