<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class TourMapTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private const EMBED = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780';

    public function test_an_iframe_snippet_is_reduced_to_its_src(): void
    {
        $tour = new Tour(['map_embed_url' => '<iframe src="' . self::EMBED . '" width="600" height="450"></iframe>']);

        $this->assertSame(self::EMBED, $tour->mapEmbedSrc());
        $this->assertSame(self::EMBED, $tour->mapLink());
    }

    public function test_a_share_link_only_gives_a_link(): void
    {
        $tour = new Tour(['map_embed_url' => 'https://maps.app.goo.gl/abc123']);

        $this->assertNull($tour->mapEmbedSrc());
        $this->assertSame('https://maps.app.goo.gl/abc123', $tour->mapLink());
    }

    public function test_coordinates_become_both_an_embed_and_a_link(): void
    {
        $tour = new Tour(['map_embed_url' => '18.626111,98.627497']);

        $this->assertSame('https://www.google.com/maps?q=18.626111%2C98.627497&output=embed', $tour->mapEmbedSrc());
        $this->assertSame('https://www.google.com/maps?q=18.626111%2C98.627497', $tour->mapLink());
    }

    public function test_an_empty_field_shows_nothing(): void
    {
        $tour = new Tour(['map_embed_url' => '   ']);

        $this->assertNull($tour->mapEmbedSrc());
        $this->assertNull($tour->mapLink());
    }

    public function test_tour_page_shows_the_map_card(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['map_embed_url' => self::EMBED]);

        $this->get(route('frontend.tours.show', $tour->slug))
            ->assertOk()
            ->assertSee('<iframe class="tour-map-frame"', false)
            ->assertSee(self::EMBED, false);
    }

    public function test_tour_page_without_a_map_has_no_card(): void
    {
        $tour = $this->makeTour($this->chiangMai());

        $this->get(route('frontend.tours.show', $tour->slug))
            ->assertOk()
            ->assertDontSee('<iframe class="tour-map-frame"', false);
    }

    public function test_email_links_to_google_maps(): void
    {
        $tour = $this->makeTour($this->chiangMai(), ['map_embed_url' => 'https://maps.app.goo.gl/abc123']);
        $session = $this->makeSession($tour);

        $booking = Booking::create([
            'public_code' => 'MAPCODE1234',
            'customer_name' => 'Anna Schmidt',
            'customer_email' => 'anna@example.com',
            'customer_phone' => '+66958467417',
            'tour_id' => $tour->id,
            'session_id' => $session->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 2,
            'subtotal' => 2000,
            'vat_amount' => 140,
            'grand_total' => 2140,
            'total_price' => 2140,
            'discount_amount' => 0,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $html = (new BookingConfirmedMail($booking, 'fake-png', 'https://example.test/b/MAPCODE1234'))->render();

        $this->assertStringContainsString('https://maps.app.goo.gl/abc123', $html);
        $this->assertStringContainsString('Open in Google Maps', $html);
        $this->assertStringNotContainsString('<iframe', $html);
    }

    public function test_admin_can_save_a_map_on_a_tour(): void
    {
        $tour = $this->makeTour($this->chiangMai());

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'province_id' => $tour->province_id,
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'price_adult' => 1000,
                'price_child' => 500,
                'map_embed_url' => self::EMBED,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(self::EMBED, $tour->fresh()->map_embed_url);
    }
}
