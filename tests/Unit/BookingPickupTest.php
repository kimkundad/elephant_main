<?php

namespace Tests\Unit;

use App\Services\BookingPickup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingPickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_self_drive_needs_no_pickup_and_drops_the_note(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));

        $this->assertSame([
            'pickup_location_id' => null,
            'self_drive' => true,
            'pickup_source' => 'self_drive',
            'pickup_note' => null,
        ], (new BookingPickup())->resolve($tour, true, null, 'ignored'));
    }

    public function test_pickup_in_the_tours_province_is_accepted_with_trimmed_note(): void
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $pickup = $this->makePickup($phuket);

        $this->assertSame([
            'pickup_location_id' => $pickup->id,
            'self_drive' => false,
            'pickup_source' => 'list',
            'pickup_note' => 'Villa 12, Bangtao',
        ], (new BookingPickup())->resolve($tour, false, $pickup->id, "  Villa 12, Bangtao  "));
    }

    public function test_blank_note_is_stored_as_null(): void
    {
        $phuket = $this->makeProvince('phuket');
        $pickup = $this->makePickup($phuket);

        $result = (new BookingPickup())->resolve($this->makeTour($phuket), false, $pickup->id, '   ');

        $this->assertNull($result['pickup_note']);
    }

    public function test_missing_pickup_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        (new BookingPickup())->resolve($this->makeTour($this->makeProvince('phuket')), false, null, null);
    }

    public function test_pickup_from_another_province_is_rejected(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));
        $other = $this->makePickup($this->makeProvince('krabi'));

        try {
            (new BookingPickup())->resolve($tour, false, $other->id, null);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('pickup_location_id', $e->errors());
        }
    }

    public function test_inactive_pickup_is_rejected(): void
    {
        $phuket = $this->makeProvince('phuket');
        $inactive = $this->makePickup($phuket, ['is_active' => false]);

        $this->expectException(ValidationException::class);

        (new BookingPickup())->resolve($this->makeTour($phuket), false, $inactive->id, null);
    }
}
