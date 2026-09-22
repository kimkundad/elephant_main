<?php

namespace Tests\Feature;

use App\Models\PickupLocation;
use Database\Seeders\ChiangMaiPickupLocationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ChiangMaiPickupSeederTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_seeder_loads_the_list_once_and_keeps_the_existing_points(): void
    {
        $chiangMai = $this->chiangMai();
        // The seven points the team added by hand must survive.
        $byHand = $this->makePickup($chiangMai, ['name' => 'ลานดิน', 'is_meeting_point' => true]);

        $this->seed(ChiangMaiPickupLocationSeeder::class);

        $fromFile = count(file(
            base_path('database/data/chiang-mai-pickup-locations.txt'),
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        ));

        $this->assertSame($fromFile + 1, PickupLocation::where('province_id', $chiangMai->id)->count());
        $this->assertTrue($byHand->fresh()->is_meeting_point);

        // Running it again adds nothing.
        $this->seed(ChiangMaiPickupLocationSeeder::class);
        $this->assertSame($fromFile + 1, PickupLocation::where('province_id', $chiangMai->id)->count());
    }

    public function test_the_points_land_in_chiang_mai_only(): void
    {
        $phuket = $this->makeProvince('phuket');

        $this->seed(ChiangMaiPickupLocationSeeder::class);

        $this->assertSame(0, PickupLocation::where('province_id', $phuket->id)->count());
        $this->assertGreaterThan(900, PickupLocation::where('province_id', $this->chiangMai()->id)->count());
    }
}
