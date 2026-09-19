<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class PhoneCountryTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function bookingPayload(array $overrides = []): array
    {
        $province = $this->makeProvince('phuket');
        $tour = $this->makeTour($province);

        return array_merge([
            'booking_v2' => 1,
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'date' => now()->addDays(5)->toDateString(),
            'qty_adult' => 1,
            'qty_child' => 0,
            'qty_infant' => 0,
            'full_name' => 'Anna Schmidt',
            'phone' => '+66 95-846 7417',
            'phone_country' => 'th',
            'email' => 'anna@example.com',
            'payment_channel' => 'card',
            'self_drive' => 1,
        ], $overrides);
    }

    public function test_booking_stores_the_number_in_e164_with_its_country(): void
    {
        // Stripe is not reachable in tests, so the booking row is checked
        // after the controller saves it and the redirect fails.
        try {
            $this->post(route('frontend.booking.store'), $this->bookingPayload());
        } catch (\Throwable $e) {
            // ignore the Stripe call
        }

        $booking = Booking::firstOrFail();
        $this->assertSame('+66958467417', $booking->customer_phone);
        $this->assertSame('TH', $booking->customer_phone_country);

        $customer = Customer::firstOrFail();
        $this->assertSame('+66958467417', $customer->phone);
        $this->assertSame('TH', $customer->phone_country);
    }

    public function test_a_returning_guest_with_a_local_number_is_not_duplicated(): void
    {
        $existing = Customer::create([
            'full_name' => 'Anna Schmidt',
            'email' => 'old@example.com',
            'phone' => '0958467417',
        ]);

        try {
            $this->post(route('frontend.booking.store'), $this->bookingPayload(['email' => 'new@example.com']));
        } catch (\Throwable $e) {
            // ignore the Stripe call
        }

        $this->assertSame(1, Customer::count());
        $this->assertSame($existing->id, Booking::firstOrFail()->customer_id);
    }

    public function test_admin_customer_form_stores_e164_and_country(): void
    {
        $this->actingAsAdmin()->post(route('admin.customers.store'), [
            'full_name' => 'Bob',
            'email' => 'bob@example.com',
            'phone' => '+66 81 234 5678',
            'phone_country' => 'th',
        ])->assertRedirect(route('admin.customers.index'));

        $customer = Customer::firstOrFail();
        $this->assertSame('+66812345678', $customer->phone);
        $this->assertSame('TH', $customer->phone_country);
    }

    public function test_contact_form_stores_the_country(): void
    {
        Contact::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'phone' => '+66812345678',
            'phone_country' => 'TH',
            'subject' => 'Booking',
            'message' => 'Hello',
        ]);

        $this->assertSame('TH', Contact::firstOrFail()->phone_country);
    }
}
