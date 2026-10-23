<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\TrackingSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

/**
 * Marketing tags are whatever the admin has typed in: no id saved means no
 * script on the page at all.
 */
class TrackingTagsTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();

        TrackingSetting::forgetCurrent();
    }

    private function tags(array $attrs = []): TrackingSetting
    {
        TrackingSetting::query()->delete();
        TrackingSetting::forgetCurrent();

        return TrackingSetting::create($attrs);
    }

    public function test_a_page_carries_no_tag_until_one_is_saved(): void
    {
        $this->tags();

        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertStringNotContainsString('googletagmanager.com', $html);
        $this->assertStringNotContainsString('connect.facebook.net', $html);
    }

    public function test_the_ga4_id_from_the_admin_page_is_the_one_used(): void
    {
        $this->tags(['ga4_measurement_id' => 'G-TESTID123']);

        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('gtag/js?id=G-TESTID123', false)
            ->assertSee("gtag('consent', 'default'", false);
    }

    public function test_the_meta_pixel_loads_revoked_until_marketing_is_accepted(): void
    {
        $this->tags(['meta_pixel_id' => '1234567890123456']);

        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertStringContainsString('connect.facebook.net', $html);
        $this->assertStringContainsString('"1234567890123456"', $html);
        $this->assertStringContainsString("fbq('consent', 'revoke')", $html);
    }

    public function test_tag_manager_adds_both_halves_of_its_snippet(): void
    {
        $this->tags(['gtm_container_id' => 'GTM-ABC1234']);

        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('gtm.js?id=', false)
            ->assertSee('googletagmanager.com/ns.html?id=GTM-ABC1234', false);
    }

    public function test_a_paid_booking_reports_a_purchase_once_per_booking_code(): void
    {
        $this->tags([
            'ga4_measurement_id' => 'G-TESTID123',
            'meta_pixel_id' => '1234567890123456',
            'track_purchase' => true,
        ]);

        $booking = $this->paidBooking();

        $html = $this->get(route('frontend.booking.confirmed.v2', $booking))->assertOk()->getContent();

        $this->assertStringContainsString("gtag('event', 'purchase'", $html);
        $this->assertStringContainsString("fbq('track', 'Purchase'", $html);
        // The booking code is the transaction id, so a reload is not a second sale.
        $this->assertStringContainsString('"PURCHASE1"', $html);
        $this->assertStringContainsString('"value":2140', $html);
    }

    public function test_the_purchase_event_can_be_switched_off(): void
    {
        $this->tags(['ga4_measurement_id' => 'G-TESTID123', 'track_purchase' => false]);

        $this->get(route('frontend.booking.confirmed.v2', $this->paidBooking()))
            ->assertOk()
            ->assertDontSee("gtag('event', 'purchase'", false);
    }

    public function test_an_unpaid_booking_reports_nothing(): void
    {
        $this->tags(['ga4_measurement_id' => 'G-TESTID123']);

        $booking = $this->paidBooking(['payment_status' => 'pending']);

        $this->get(route('frontend.booking.confirmed.v2', $booking))
            ->assertOk()
            ->assertDontSee("gtag('event', 'purchase'", false);
    }

    public function test_an_admin_can_save_the_ids(): void
    {
        $this->tags();

        $this->actingAsAdmin()
            ->post(route('admin.tracking.update'), [
                'ga4_measurement_id' => 'G-NEWID999',
                'meta_pixel_id' => '9876543210987654',
                'track_purchase' => '1',
            ])
            ->assertSessionHasNoErrors();

        TrackingSetting::forgetCurrent();
        $saved = TrackingSetting::first();

        $this->assertSame('G-NEWID999', $saved->ga4_measurement_id);
        $this->assertSame('9876543210987654', $saved->meta_pixel_id);
        $this->assertTrue($saved->track_purchase);
    }

    public function test_an_id_in_the_wrong_shape_is_refused(): void
    {
        $this->tags(['ga4_measurement_id' => 'G-KEEPME123']);

        $this->actingAsAdmin()
            ->post(route('admin.tracking.update'), ['ga4_measurement_id' => 'UA-12345-1'])
            ->assertSessionHasErrors('ga4_measurement_id');

        $this->assertSame('G-KEEPME123', TrackingSetting::first()->ga4_measurement_id);
    }

    public function test_only_a_super_admin_may_change_the_raw_snippets(): void
    {
        $this->tags(['head_html' => '<!-- original -->']);

        $this->actingAsAdmin()
            ->post(route('admin.tracking.update'), ['head_html' => '<script>alert(1)</script>'])
            ->assertSessionHasNoErrors();

        $this->assertSame('<!-- original -->', TrackingSetting::first()->head_html);

        $this->actingAsSuperAdmin()
            ->post(route('admin.tracking.update'), ['head_html' => '<!-- replaced -->'])
            ->assertSessionHasNoErrors();

        $this->assertSame('<!-- replaced -->', TrackingSetting::first()->head_html);
    }

    public function test_the_admin_page_offers_a_field_for_each_tag(): void
    {
        $this->tags(["ga4_measurement_id" => "G-TESTID123"]);

        $this->actingAsAdmin()
            ->get(route("admin.tracking.edit"))
            ->assertOk()
            ->assertSee("Tracking &amp; Tags", false)
            ->assertSee("name=\"ga4_measurement_id\"", false)
            ->assertSee("name=\"meta_pixel_id\"", false)
            ->assertSee("name=\"gtm_container_id\"", false)
            ->assertSee("name=\"track_purchase\"", false)
            ->assertSee("G-TESTID123")
            // The raw snippet boxes belong to a superAdmin only.
            ->assertDontSee("name=\"head_html\"", false);
    }

    public function test_a_super_admin_gets_the_snippet_boxes(): void
    {
        $this->tags();

        $this->actingAsSuperAdmin()
            ->get(route("admin.tracking.edit"))
            ->assertOk()
            ->assertSee("name=\"head_html\"", false)
            ->assertSee("name=\"body_html\"", false);
    }

    public function test_a_visitor_cannot_open_the_tracking_page(): void
    {
        $this->get(route('admin.tracking.edit'))->assertRedirect();
    }

    private function actingAsSuperAdmin(): static
    {
        $role = Role::firstOrCreate(['name' => 'superAdmin', 'guard_name' => 'web']);
        $user = \App\Models\User::factory()->create();
        $user->assignRole($role);

        return $this->actingAs($user);
    }

    private function paidBooking(array $attrs = []): Booking
    {
        $tour = $this->makeTour($this->chiangMai(), ['name' => 'Full day walking with elephants']);
        $session = $this->makeSession($tour);

        return Booking::create(array_merge([
            'public_code' => 'PURCHASE1',
            'customer_name' => 'Anna Schmidt',
            'customer_phone' => '+66958467417',
            'customer_email' => 'anna@example.com',
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
            'payment_channel' => 'promptpay',
            'paid_at' => now(),
        ], $attrs));
    }
}
