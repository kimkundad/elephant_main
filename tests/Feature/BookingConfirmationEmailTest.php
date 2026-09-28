<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Models\Tour;
use App\Models\TourTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingConfirmationEmailTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function bookingFor(Tour $tour): Booking
    {
        return Booking::create([
            'public_code' => 'PUBLICCODE123',
            'customer_name' => 'Anna Schmidt',
            'customer_phone' => '+66958467417',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'total_guests' => 3,
            'subtotal' => 2500,
            'vat_amount' => 175,
            'grand_total' => 2675,
            'total_price' => 2675,
            'discount_amount' => 0,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_channel' => 'promptpay',
            'paid_at' => now(),
        ]);
    }

    private function render(Booking $booking): string
    {
        return (new BookingConfirmedMail($booking, 'fake-png', 'https://example.test/b/PUBLICCODE123'))->render();
    }

    public function test_email_shows_the_english_tour_name(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'เต็มวัน เดินชมช้างตามธรรมชาติ']);
        TourTranslation::create([
            'tour_id' => $tour->id,
            'locale' => 'en',
            'name' => 'Full day walking with elephants',
        ]);
        TourTranslation::create([
            'tour_id' => $tour->id,
            'locale' => 'th',
            'name' => 'เต็มวัน เดินชมช้างตามธรรมชาติ',
        ]);

        $html = $this->render($this->bookingFor($tour));

        $this->assertStringContainsString('Full day walking with elephants', $html);
        $this->assertStringNotContainsString('เต็มวัน เดินชมช้างตามธรรมชาติ', $html);
    }

    public function test_email_falls_back_to_the_stored_name_without_an_english_translation(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'Morning Feeding Tour']);

        $html = $this->render($this->bookingFor($tour));

        $this->assertStringContainsString('Morning Feeding Tour', $html);
    }

    public function test_email_highlights_the_whatsapp_number_from_site_settings(): void
    {
        SiteSetting::query()->delete();
        SiteSetting::create(['contact_whatsapp_line' => '+66 95 846 7417']);

        $html = $this->render($this->bookingFor($this->makeTour($this->chiangMai())));

        $this->assertStringContainsString('https://wa.me/66958467417', $html);
        $this->assertStringContainsString('Chat on WhatsApp', $html);
    }

    public function test_email_hides_the_whatsapp_block_when_nothing_is_configured(): void
    {
        SiteSetting::query()->delete();
        SiteSetting::create(['contact_whatsapp_line' => '#']);

        $html = $this->render($this->bookingFor($this->makeTour($this->chiangMai())));

        $this->assertStringNotContainsString('Chat on WhatsApp', $html);
        $this->assertStringNotContainsString('wa.me', $html);
    }
}
