<?php

namespace Tests\Feature\Admin;

use App\Models\Agent;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class AgentReportTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private Agent $agent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = Agent::create(['name' => 'Somchai', 'is_active' => true]);
    }

    private function booking(array $attrs = []): Booking
    {
        $province = $this->makeProvince('p-' . uniqid());
        $tour = $this->makeTour($province);

        return Booking::create(array_merge([
            'agent_id' => $this->agent->id,
            'customer_name' => 'Guest',
            'customer_email' => 'guest@example.com',
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addMonths(3)->toDateString(),
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'total_guests' => 1,
            'subtotal' => 1000,
            'vat_amount' => 70,
            'grand_total' => 1070,
            'total_price' => 1070,
            'discount_amount' => 100,
            'discount_code' => 'AGENT10',
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ], $attrs));
    }

    private function summaryRow(array $query = []): array
    {
        $response = $this->actingAsAdmin()->get(route('admin.reports.agents', $query))->assertOk();

        return $response->viewData('summary')->firstWhere('agent.id', $this->agent->id);
    }

    public function test_sales_count_only_paid_bookings(): void
    {
        $this->booking();
        $this->booking(['payment_status' => 'pending', 'paid_at' => null]);
        $this->booking(['payment_status' => 'failed', 'paid_at' => null]);

        $row = $this->summaryRow();

        $this->assertSame(1070.0, $row['total_sales']);
        $this->assertSame(100.0, $row['total_discount']);
        $this->assertSame(1, $row['paid_count']);
        $this->assertSame(3, $row['booking_count']);
        $this->assertSame(2, $row['unpaid_count']);
    }

    public function test_sales_are_dated_by_the_payment_not_the_tour_date(): void
    {
        // Paid last month for a tour that runs in three months.
        $this->booking(['paid_at' => now()->subMonth()]);
        // Paid today.
        $this->booking();

        $thisMonth = $this->summaryRow([
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->endOfMonth()->toDateString(),
        ]);

        $this->assertSame(1070.0, $thisMonth['total_sales']);
        $this->assertSame(1, $thisMonth['paid_count']);
    }

    public function test_a_cancelled_but_paid_booking_still_counts_as_sales(): void
    {
        $this->booking(['status' => 'cancelled']);

        $this->assertSame(1070.0, $this->summaryRow()['total_sales']);
    }

    public function test_export_lists_the_discounted_bookings(): void
    {
        $booking = $this->booking();

        $csv = $this->actingAsAdmin()
            ->get(route('admin.reports.agents.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('AGENT10', $csv);
        $this->assertStringContainsString('Somchai', $csv);
        $this->assertStringContainsString((string) $booking->id, $csv);
        $this->assertStringContainsString('Paid At', $csv);
    }
}
