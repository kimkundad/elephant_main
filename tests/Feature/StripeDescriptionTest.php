<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

/**
 * Stripe writes the receipt the guest gets after paying, from the line we send
 * it, so that line has to read in English even for a Thai booking.
 */
class StripeDescriptionTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function tourWithBothNames(): Tour
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'เต็มวัน เดินป่าและทำสปาโคลนกับช้าง – DDE']);

        TourTranslation::create([
            'tour_id' => $tour->id,
            'locale' => 'en',
            'name' => 'Full day trekking and mud spa with elephants - DDE',
        ]);

        return $tour;
    }

    private function receiptLine(Tour $tour, int $bookingId): string
    {
        return __('booking.stripe.product_name', [
            'id' => $bookingId,
            'tour' => $tour->nameIn('en'),
        ], 'en');
    }

    public function test_the_receipt_line_is_english_even_while_the_site_is_thai(): void
    {
        app()->setLocale('th');

        $line = $this->receiptLine($this->tourWithBothNames(), 93);

        $this->assertSame('Booking #93 - Full day trekking and mud spa with elephants - DDE', $line);
        $this->assertStringNotContainsString('การจอง', $line);
    }

    public function test_a_tour_without_an_english_name_falls_back_to_the_stored_one(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'Morning Feeding Tour']);

        $this->assertSame('Booking #7 - Morning Feeding Tour', $this->receiptLine($tour, 7));
    }
}
