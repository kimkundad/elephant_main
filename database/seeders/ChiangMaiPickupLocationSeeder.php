<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ImportsPickupLocations;
use Illuminate\Database\Seeder;

/**
 * Chiang Mai accommodation within 8 km of the Old City, from OpenStreetMap
 * (© OpenStreetMap contributors, ODbL). Names only; no coordinates are kept.
 */
class ChiangMaiPickupLocationSeeder extends Seeder
{
    use ImportsPickupLocations;

    public function run(): void
    {
        $this->importPickupLocations('chiang-mai', 'database/data/chiang-mai-pickup-locations.txt');
    }
}
