<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceFieldsAdminTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_pickup_location_requires_province(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.pickup-locations.store'), ['name' => 'Bangtao Zone', 'is_active' => 1])
            ->assertSessionHasErrors('province_id');
    }

    public function test_pickup_location_saves_province(): void
    {
        $phuket = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->post(route('admin.pickup-locations.store'), [
                'name' => 'Bangtao Zone',
                'province_id' => $phuket->id,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.pickup-locations.index'));

        $this->assertDatabaseHas('pickup_locations', ['name' => 'Bangtao Zone', 'province_id' => $phuket->id]);
    }

    public function test_pickup_location_update_changes_province(): void
    {
        $pickup = $this->makePickup($this->chiangMai(), ['name' => 'Old Town']);
        $phuket = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->put(route('admin.pickup-locations.update', $pickup), [
                'name' => 'Old Town',
                'province_id' => $phuket->id,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.pickup-locations.index'));

        $this->assertSame($phuket->id, $pickup->fresh()->province_id);
    }

    public function test_lists_filter_by_province_and_forms_render(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต']);
        $this->makePickup($phuket, ['name' => 'Bangtao Zone']);
        $this->makePickup($this->chiangMai(), ['name' => 'Nimman Hotel']);
        $tour = $this->makeTour($phuket, ['name' => 'Phuket Walk']);
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        $this->actingAsAdmin();

        $this->get(route('admin.pickup-locations.index', ['province_id' => $phuket->id]))
            ->assertOk()->assertSee('Bangtao Zone')->assertDontSee('Nimman Hotel');
        $this->get(route('admin.tours.index', ['province_id' => $phuket->id]))
            ->assertOk()->assertSee('Phuket Walk')->assertDontSee('Chiang Mai Feeding');

        $this->get(route('admin.pickup-locations.create'))->assertOk()->assertSee('name="province_id"', false);
        $this->get(route('admin.tours.create'))->assertOk()->assertSee('name="province_id"', false);
        $this->get(route('admin.tours.edit', $tour->id))->assertOk()->assertSee('name="province_id"', false);
    }

    public function test_tour_update_requires_province(): void
    {
        $tour = $this->makeTour($this->chiangMai());

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'min_price' => 1000,
                'max_price' => 1000,
            ])
            ->assertSessionHasErrors('province_id');
    }
}
