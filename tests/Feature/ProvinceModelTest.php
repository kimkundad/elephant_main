<?php

namespace Tests\Feature;

use App\Models\PickupLocation;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceModelTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_migration_creates_chiang_mai(): void
    {
        $this->assertSame('Chiang Mai', $this->chiangMai()->name_en);
        $this->assertTrue($this->chiangMai()->is_active);
    }

    public function test_name_follows_locale(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต', 'name_en' => 'Phuket']);

        app()->setLocale('th');
        $this->assertSame('ภูเก็ต', $phuket->name());

        app()->setLocale('en');
        $this->assertSame('Phuket', $phuket->name());
    }

    public function test_visible_hides_inactive_tours_and_tours_in_inactive_provinces(): void
    {
        $open = $this->makeProvince('phuket');
        $closed = $this->makeProvince('krabi', ['is_active' => false]);

        $shown = $this->makeTour($open);
        $this->makeTour($open, ['is_active' => false]);
        $this->makeTour($closed);

        $this->assertSame([$shown->id], Tour::visible()->pluck('id')->all());
    }

    public function test_available_in_returns_active_pickups_of_that_province_only(): void
    {
        $phuket = $this->makeProvince('phuket');
        $krabi = $this->makeProvince('krabi');

        $ok = $this->makePickup($phuket);
        $this->makePickup($phuket, ['is_active' => false]);
        $this->makePickup($krabi);

        $this->assertSame([$ok->id], PickupLocation::availableIn($phuket->id)->pluck('id')->all());
    }
}
