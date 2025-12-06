<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourSession;
use App\Models\TourAvailability;
use App\Models\PickupLocation;

class DemoTourSeeder extends Seeder
{
    public function run()
    {
        // ---------- TOUR LIST ----------
        $toursData = [
            [
                'name' => 'Canopy Walkway Tour (Shared Transfers Included)',
                'slug' => 'canopy-walkway-shared',
                'short_description' => 'All ages • Shared transfers included',
                'description' => 'Experience a canopy walkway and elephants.',
                'min_price' => 1200,
                'max_price' => 2400,
                'thumbnail' => 'https://cdn.filestackcontent.com/mfxJdOnHQSCcxnjcLsD8/convert?cache=true&compress=true&quality=90&h=250&fit=max',
                'is_active' => 1,
            ],
            [
                'name' => 'Canopy Walkway Tour (No Transfers)',
                'slug' => 'canopy-walkway-no-transfer',
                'short_description' => 'All ages • Private Transfers available',
                'description' => 'Explore the canopy walkway without shared transfer.',
                'min_price' => 950,
                'max_price' => 1900,
                'thumbnail' => 'https://cdn.filestackcontent.com/mfxJdOnHQSCcxnjcLsD8/convert?cache=true&compress=true&quality=90&h=250&fit=max',
                'is_active' => 1,
            ],
            [
                'name' => 'Half Day Program (Shared Round Trip Transfers Included)',
                'slug' => 'halfday-shared',
                'short_description' => 'All ages • Most Popular!',
                'description' => 'Half day experience with elephants.',
                'min_price' => 1750,
                'max_price' => 3500,
                'thumbnail' => 'https://cdn.filestackcontent.com/mfxJdOnHQSCcxnjcLsD8/convert?cache=true&compress=true&quality=90&h=250&fit=max',
                'is_active' => 1,
            ],
            [
                'name' => 'Half Day Program (No Transfers)',
                'slug' => 'halfday-no-transfer',
                'short_description' => 'All ages • Private Transfers available',
                'description' => 'A half-day experience without shared transfer.',
                'min_price' => 1500,
                'max_price' => 3000,
                'thumbnail' => 'https://cdn.filestackcontent.com/mfxJdOnHQSCcxnjcLsD8/convert?cache=true&compress=true&quality=90&h=250&fit=max',
                'is_active' => 1,
            ],
        ];

        foreach ($toursData as $tourData) {
            $tour = Tour::updateOrCreate(
                ['slug' => $tourData['slug']], // unique key
                $tourData                      // update fields
            );

            // ---------- SESSIONS ----------
            $sessions = [
                ['name' => '9:30am', 'title' => 'Morning Program', 'start' => '09:30:00', 'end' => '12:00:00'],
                ['name' => '10am', 'title' => 'Morning Program', 'start' => '10:00:00', 'end' => '12:30:00'],
                ['name' => '1:30pm', 'title' => 'Afternoon Program', 'start' => '13:30:00', 'end' => '16:00:00'],
                ['name' => '2pm', 'title' => 'Afternoon Program', 'start' => '14:00:00', 'end' => '16:30:00'],
                ['name' => '2:30pm', 'title' => 'Afternoon Program', 'start' => '14:30:00', 'end' => '17:00:00'],
            ];

            foreach ($sessions as $s) {
                $session = TourSession::create([
                    'tour_id' => $tour->id,
                    'name' => $s['name'],
                    'title' => $s['title'],
                    'start_time' => $s['start'],
                    'end_time' => $s['end'],
                    'default_capacity' => 20,
                    'capacity' => null,
                    'is_active' => 1,
                ]);

                // ---------- AVAILABILITY (30 DAYS) ----------
                for ($i = 0; $i < 30; $i++) {
                    $date = now()->addDays($i)->format('Y-m-d');

                    TourAvailability::create([
                        'tour_id' => $tour->id,
                        'session_id' => $session->id,
                        'date' => $date,
                        'is_open' => 1,
                        'capacity_override' => null,
                    ]);
                }
            }
        }

        // ---------- PICKUP LOCATIONS ----------
        $pickupList = [
            ['name' => 'Hotel A', 'pickup_time' => '08:00', 'latitude' => 18.7883, 'longitude' => 98.9853],
            ['name' => 'Hotel B', 'pickup_time' => '08:15', 'latitude' => 18.7821, 'longitude' => 98.9975],
            ['name' => 'Hotel C', 'pickup_time' => '08:30', 'latitude' => 18.7899, 'longitude' => 98.9933],
        ];

        foreach ($pickupList as $p) {
            PickupLocation::create([
                'name' => $p['name'],
                'latitude' => $p['latitude'],
                'longitude' => $p['longitude'],
                'is_active' => 1,
            ]);
        }
    }
}
