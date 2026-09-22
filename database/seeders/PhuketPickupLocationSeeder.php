<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ImportsPickupLocations;
use Illuminate\Database\Seeder;

/** Phuket hotel list, taken from the supplier's booking-form export. */
class PhuketPickupLocationSeeder extends Seeder
{
    use ImportsPickupLocations;

    public function run(): void
    {
        $this->importPickupLocations('phuket', 'database/data/phuket-pickup-locations.txt');
    }
}
