<?php

namespace Tests\Feature\Admin;

use App\Models\Elephant;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ElephantProgramTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Tour $tour;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('spaces');
        $this->tour = $this->makeTour($this->chiangMai(), ['slug' => 'short-visit-feeding-shower-kcp']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name_th' => 'พลอย',
            'name_en' => 'Ploy',
            'tour_id' => $this->tour->id,
            'is_active' => 1,
            'images' => [UploadedFile::fake()->image('ploy.jpg')],
        ], $overrides);
    }

    private function makeElephant(?Tour $tour = null): Elephant
    {
        return Elephant::create([
            'name' => 'พลอย',
            'slug' => 'ploy',
            'images' => ['https://example.com/ploy.jpg'],
            'is_active' => true,
            'tour_id' => $tour?->id,
        ]);
    }

    public function test_store_requires_a_program(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload(['tour_id' => null]))
            ->assertSessionHasErrors('tour_id');

        $this->assertSame(0, Elephant::count());
    }

    public function test_store_saves_the_program(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload())
            ->assertSessionHasNoErrors();

        $this->assertSame($this->tour->id, Elephant::first()->tour_id);
    }

    public function test_unknown_program_is_rejected(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.elephants.store'), $this->payload(['tour_id' => 999999]))
            ->assertSessionHasErrors('tour_id');
    }

    public function test_update_requires_a_program(): void
    {
        $elephant = $this->makeElephant();

        $this->actingAsAdmin()
            ->put(route('admin.elephants.update', $elephant), $this->payload(['tour_id' => null, 'images' => null]))
            ->assertSessionHasErrors('tour_id');
    }

    public function test_update_saves_the_program(): void
    {
        $elephant = $this->makeElephant();

        $this->actingAsAdmin()
            ->put(route('admin.elephants.update', $elephant), $this->payload(['images' => null]))
            ->assertSessionHasNoErrors();

        $this->assertSame($this->tour->id, $elephant->fresh()->tour_id);
    }

    public function test_the_forms_list_the_programs(): void
    {
        $this->actingAsAdmin()->get(route('admin.elephants.create'))
            ->assertOk()->assertSee('name="tour_id"', false)->assertSee($this->tour->name);

        $this->actingAsAdmin()->get(route('admin.elephants.edit', $this->makeElephant($this->tour)))
            ->assertOk()->assertSee('value="' . $this->tour->id . '" selected', false);
    }

    public function test_deleting_the_tour_keeps_the_elephant(): void
    {
        $elephant = $this->makeElephant($this->tour);

        $this->tour->delete();

        $this->assertNull($elephant->fresh()->tour_id);
    }

    public function test_program_url_points_at_a_visible_tour(): void
    {
        $this->assertSame(
            route('frontend.tours.show.v2', 'short-visit-feeding-shower-kcp'),
            $this->makeElephant($this->tour)->programUrl()
        );
    }

    public function test_program_url_falls_back_without_a_tour(): void
    {
        $this->assertSame(route('frontend.elephants') . '#ploy', $this->makeElephant()->programUrl());
    }

    public function test_program_url_falls_back_for_an_inactive_tour(): void
    {
        $this->tour->update(['is_active' => false]);

        $this->assertSame(route('frontend.elephants') . '#ploy', $this->makeElephant($this->tour)->programUrl());
    }
}
