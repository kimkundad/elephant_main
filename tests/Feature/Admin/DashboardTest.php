<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Contact;
use App\Models\IntegrationLog;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function booking(array $attrs = []): Booking
    {
        $province = $this->makeProvince('phuket-' . uniqid(), ['name_th' => 'ภูเก็ต']);
        $tour = $this->makeTour($province, ['name' => 'Phuket Walk', 'min_price' => 1000]);

        return Booking::create(array_merge([
            'customer_name' => 'Anna',
            'customer_email' => 'anna@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->toDateString(),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 2,
            'subtotal' => 2000,
            'vat_amount' => 140,
            'fee_amount' => 0,
            'grand_total' => 2140,
            'total_price' => 2140,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_channel' => 'promptpay',
            'paid_at' => now(),
        ], $attrs));
    }

    public function test_dashboard_renders_with_no_data(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('ภาพรวมระบบ')
            ->assertSee('ยังไม่มีการจอง');
    }

    public function test_dashboard_counts_revenue_guests_and_unpaid(): void
    {
        $this->booking();
        $this->booking(['payment_status' => 'pending', 'paid_at' => null, 'grand_total' => 500, 'total_guests' => 3]);
        // Cancelled bookings never count.
        $this->booking(['status' => 'cancelled', 'payment_status' => 'pending', 'paid_at' => null, 'total_guests' => 9]);

        $response = $this->actingAsAdmin()->get(route('admin.dashboard'))->assertOk();

        $kpi = $response->viewData('kpi');
        $this->assertSame(2140.0, $kpi['revenue']);
        $this->assertSame(5, $kpi['guests_today']);
        $this->assertSame(1, $kpi['unpaid']);
        $this->assertSame(500.0, $kpi['unpaid_amount']);
    }

    public function test_dashboard_lists_departures_top_tours_and_attention_items(): void
    {
        $this->booking();
        Review::create(['tour_id' => null, 'author_name' => 'Guest', 'rating' => 5, 'review_text' => 'Great', 'is_active' => false]);
        Contact::create(['name' => 'Bob', 'email' => 'bob@example.com', 'subject' => 'Booking', 'message' => 'Hello']);
        IntegrationLog::create([
            'channel' => 'mail',
            'event' => 'confirmation_failed',
            'level' => 'error',
            'message' => 'SMTP rejected the login',
        ]);

        $response = $this->actingAsAdmin()->get(route('admin.dashboard'))->assertOk();

        $this->assertCount(1, $response->viewData('upcoming'));
        $this->assertSame('Phuket Walk', $response->viewData('topTours')->first()['name']);
        $this->assertSame(1, $response->viewData('attention')['reviews_pending']);
        $this->assertSame(1, $response->viewData('attention')['contacts_new']);

        $response->assertSee('SMTP rejected the login')->assertSee('Phuket Walk');
    }
}
