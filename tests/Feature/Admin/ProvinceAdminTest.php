<?php

namespace Tests\Feature\Admin;

use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceAdminTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_admin_creates_a_province_with_slug_from_english_name(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.provinces.store'), [
                'name_th' => 'ภูเก็ต',
                'name_en' => 'Phuket',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertDatabaseHas('provinces', ['slug' => 'phuket', 'name_th' => 'ภูเก็ต', 'is_active' => true]);
    }

    public function test_slug_must_be_unique(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.provinces.store'), [
                'name_th' => 'เชียงใหม่ 2',
                'name_en' => 'Chiang Mai',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_unticked_status_saves_inactive(): void
    {
        $province = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->put(route('admin.provinces.update', $province), [
                'name_th' => 'ภูเก็ต',
                'name_en' => 'Phuket',
                'slug' => 'phuket',
            ])
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertFalse($province->fresh()->is_active);
    }

    public function test_province_with_tours_cannot_be_deleted(): void
    {
        $province = $this->makeProvince('phuket');
        $this->makeTour($province);

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertSessionHasErrors('province');

        $this->assertModelExists($province);
    }

    public function test_province_with_pickups_cannot_be_deleted(): void
    {
        $province = $this->makeProvince('phuket');
        $this->makePickup($province);

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertSessionHasErrors('province');

        $this->assertModelExists($province);
    }

    public function test_empty_province_is_deleted(): void
    {
        $province = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertModelMissing($province);
    }

    public function test_index_and_forms_render(): void
    {
        $province = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต']);

        $this->actingAsAdmin();
        $this->get(route('admin.provinces.index'))->assertOk()->assertSee('ภูเก็ต')->assertSee('เชียงใหม่');
        $this->get(route('admin.provinces.create'))->assertOk();
        $this->get(route('admin.provinces.edit', $province))->assertOk()->assertSee('value="phuket"', false);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.provinces.index'))->assertRedirect();
        $this->assertSame(1, Province::count());
    }
}
