<?php

namespace Tests\Feature;

use App\Models\PickupLocation;
use Database\Seeders\PhuketPickupLocationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class PhuketPickupSeederTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_seeder_loads_the_list_once_and_skips_duplicates(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต', 'name_en' => 'Phuket']);
        // Already added by hand in the admin, in a different letter case.
        $this->makePickup($phuket, ['name' => '2w cafe & hostel']);

        $this->seed(PhuketPickupLocationSeeder::class);

        $afterFirstRun = PickupLocation::where('province_id', $phuket->id)->count();
        $expected = count(file(base_path('database/data/phuket-pickup-locations.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        // Everything from the file, plus the hand-made row, minus the duplicate.
        $this->assertSame($expected, $afterFirstRun);
        $this->assertSame(1, PickupLocation::where('province_id', $phuket->id)
            ->whereRaw('LOWER(name) = ?', ['2w cafe & hostel'])
            ->count());

        $this->seed(PhuketPickupLocationSeeder::class);

        $this->assertSame($afterFirstRun, PickupLocation::where('province_id', $phuket->id)->count());
    }

    public function test_seeder_does_nothing_without_the_province(): void
    {
        $this->seed(PhuketPickupLocationSeeder::class);

        $this->assertSame(0, PickupLocation::count());
    }
}
