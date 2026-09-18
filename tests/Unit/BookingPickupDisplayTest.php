<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\PickupLocation;
use Tests\TestCase;

class BookingPickupDisplayTest extends TestCase
{
    public function test_self_drive(): void
    {
        app()->setLocale('en');
        $booking = new Booking(['self_drive' => true, 'pickup_note' => 'ignored']);

        $this->assertSame(__('booking.confirmed.self_drive'), $booking->pickupLabel());
        $this->assertNull($booking->pickupDetail());
    }

    public function test_pickup_point_with_note(): void
    {
        $booking = new Booking(['self_drive' => false, 'pickup_note' => 'Villa 12']);
        $booking->setRelation('pickupLocation', new PickupLocation(['name' => 'Bangtao Zone']));

        $this->assertSame('Bangtao Zone', $booking->pickupLabel());
        $this->assertSame('Villa 12', $booking->pickupDetail());
    }

    public function test_legacy_google_booking_falls_back_to_place_fields(): void
    {
        $booking = new Booking([
            'self_drive' => false,
            'pickup_place_name' => 'Old Hotel',
            'pickup_place_address' => '1 Old Road',
        ]);
        $booking->setRelation('pickupLocation', null);

        $this->assertSame('Old Hotel', $booking->pickupLabel());
        $this->assertSame('1 Old Road', $booking->pickupDetail());
    }

    public function test_nothing_set(): void
    {
        $booking = new Booking(['self_drive' => false]);
        $booking->setRelation('pickupLocation', null);

        $this->assertSame('-', $booking->pickupLabel());
        $this->assertNull($booking->pickupDetail());
    }
}
