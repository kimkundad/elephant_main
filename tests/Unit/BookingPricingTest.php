<?php

namespace Tests\Unit;

use App\Models\Tour;
use App\Services\BookingPricing;
use Tests\TestCase;

class BookingPricingTest extends TestCase
{
    public function test_children_pay_half_and_infants_are_free(): void
    {
        $tour = new Tour(['min_price' => 1000]);

        $result = (new BookingPricing())->for($tour, 2, 1, 3);

        $this->assertSame(2500.0, $result['subtotal']);
        $this->assertSame(175.0, $result['vat']);
        $this->assertSame(0.0, $result['fee']);
        $this->assertSame(2675.0, $result['grand_total']);
    }

    public function test_discount_is_subtracted_and_never_goes_below_zero(): void
    {
        $tour = new Tour(['min_price' => 1000]);

        $this->assertSame(570.0, (new BookingPricing())->for($tour, 1, 0, 0, 500)['grand_total']);
        $this->assertSame(0.0, (new BookingPricing())->for($tour, 1, 0, 0, 99999)['grand_total']);
        $this->assertSame(1070.0, (new BookingPricing())->for($tour, 1, 0, 0, -50)['grand_total']);
    }
}
