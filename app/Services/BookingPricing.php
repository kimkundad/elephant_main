<?php

namespace App\Services;

use App\Models\Tour;

/**
 * The one place that prices a booking, so the public booking form and the
 * admin form always charge the same amounts.
 *
 * Adults pay the tour price, children half of it, infants are free. VAT is
 * 7% on the subtotal and there is no booking fee.
 */
class BookingPricing
{
    public const VAT_RATE = 0.07;

    /**
     * What one guest of each age costs. Infants are free.
     *
     * @return array{adult: int, child: int, infant: int}
     */
    public function unitPrices(Tour $tour): array
    {
        return [
            'adult' => (int) ($tour->price_adult ?? 0),
            'child' => (int) ($tour->price_child ?? 0),
            'infant' => 0,
        ];
    }

    /**
     * @return array{price_adult: int, price_child: int, subtotal: float, vat: float, fee: float, discount: float, grand_total: float}
     */
    public function for(Tour $tour, int $adults, int $children, int $infants, float $discount = 0.0): array
    {
        ['adult' => $priceAdult, 'child' => $priceChild] = $this->unitPrices($tour);

        $subtotal = ($adults * $priceAdult) + ($children * $priceChild);
        $vat = round($subtotal * self::VAT_RATE, 2);
        $fee = 0.0;

        $total = round($subtotal + $vat + $fee, 2);
        $discount = round(min(max($discount, 0), $total), 2);

        return [
            'price_adult' => $priceAdult,
            'price_child' => $priceChild,
            'subtotal' => (float) $subtotal,
            'vat' => $vat,
            'fee' => $fee,
            'discount' => $discount,
            'grand_total' => round($total - $discount, 2),
        ];
    }
}
