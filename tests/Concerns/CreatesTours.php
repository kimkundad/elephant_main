<?php

namespace Tests\Concerns;

use App\Models\PickupLocation;
use App\Models\Province;
use App\Models\Tour;
use App\Models\TourSession;
use Illuminate\Support\Str;

trait CreatesTours
{
    /** The province the migration creates in every fresh database. */
    protected function chiangMai(): Province
    {
        return Province::where('slug', 'chiang-mai')->firstOrFail();
    }

    protected function makeProvince(string $slug, array $attrs = []): Province
    {
        return Province::create(array_merge([
            'name_th' => $slug . ' (th)',
            'name_en' => Str::headline($slug),
            'slug' => $slug,
            'is_active' => true,
        ], $attrs));
    }

    protected function makeTour(Province $province, array $attrs = []): Tour
    {
        return Tour::create(array_merge([
            'province_id' => $province->id,
            'name' => 'Tour ' . Str::random(6),
            'slug' => 'tour-' . Str::lower(Str::random(10)),
            'min_price' => 1000,
            'max_price' => 1000,
            'is_active' => true,
        ], $attrs));
    }

    protected function makeSession(Tour $tour): TourSession
    {
        return TourSession::create([
            'tour_id' => $tour->id,
            'title' => 'Morning Program',
            'start_time' => '09:30:00',
            'end_time' => '12:00:00',
            'default_capacity' => 20,
            'is_active' => 1,
        ]);
    }

    protected function makePickup(Province $province, array $attrs = []): PickupLocation
    {
        return PickupLocation::create(array_merge([
            'province_id' => $province->id,
            'name' => 'Pickup ' . Str::random(6),
            'is_active' => true,
            'is_meeting_point' => false,
        ], $attrs));
    }
}
