<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\SiteSetting;
use App\Models\TourTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class InvoicePdfTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function invoiceHtml(Booking $booking): string
    {
        return view('admin.bookings.pdf', ['booking' => $booking])->render();
    }

    private function booking(): Booking
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'ครึ่งวัน เดินชมช้างตามธรรมชาติ']);
        TourTranslation::create([
            'tour_id' => $tour->id,
            'locale' => 'en',
            'name' => 'Half-Day No-Touch Elephant Trekking -CCV',
        ]);

        return Booking::create([
            'public_code' => 'INVOICECODE1',
            'customer_name' => 'Adam Cooper',
            'customer_email' => 'adam@example.com',
            'customer_phone' => '+447963265926',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 2,
            'subtotal' => 3000,
            'vat_amount' => 210,
            'grand_total' => 3210,
            'total_price' => 3210,
            'discount_amount' => 0,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function test_the_from_block_comes_from_site_settings(): void
    {
        SiteSetting::query()->delete();
        SiteSetting::create([
            'site_name' => 'SmallElephants.com',
            'company_name' => 'GWealthcome Co,.Ltd',
            'email' => 'info@elephant.com',
            'phone' => '0893261564',
        ]);

        $html = $this->invoiceHtml($this->booking());

        $this->assertStringContainsString('SmallElephants.com', $html);
        $this->assertStringContainsString('GWealthcome Co,.Ltd', $html);
        $this->assertStringContainsString('info@elephant.com', $html);
        $this->assertStringContainsString('Phone: 0893261564', $html);
        $this->assertStringNotContainsString('Elephant Sanctuary Co., Ltd.', $html);
        $this->assertStringNotContainsString('090-000-0000', $html);
    }

    public function test_the_invoice_prints_the_english_tour_name(): void
    {
        $html = $this->invoiceHtml($this->booking());

        $this->assertStringContainsString('Half-Day No-Touch Elephant Trekking -CCV', $html);
        $this->assertStringNotContainsString('ครึ่งวัน เดินชมช้างตามธรรมชาติ', $html);
    }

    public function test_unset_company_details_leave_the_brand_name_only(): void
    {
        SiteSetting::query()->delete();
        SiteSetting::create([]);

        $html = $this->invoiceHtml($this->booking());

        $this->assertStringContainsString('SmallElephants.com', $html);
        $this->assertStringNotContainsString('Phone: <', $html);
    }
}
