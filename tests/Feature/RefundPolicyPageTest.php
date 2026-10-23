<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefundPolicyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_page_states_the_48_hour_promise_and_every_rate(): void
    {
        $this->get(route('frontend.refund_policy'))
            ->assertOk()
            ->assertSee('Free cancellation up to 48 hours before')
            ->assertSee('48 hours or more before the start time')
            ->assertSee('100%')
            ->assertSee('24 to 48 hours before the start time')
            ->assertSee('50%')
            ->assertSee('No refund');
    }

    public function test_it_covers_rescheduling_refund_timing_and_how_to_ask(): void
    {
        $html = $this->get(route('frontend.refund_policy'))->assertOk()->getContent();

        foreach ([
            'Moving your booking instead of cancelling',
            'When we cancel or change a program',
            'Late arrival and no-shows',
            'Requesting a cancellation or refund',
            'When the money reaches you',
        ] as $heading) {
            $this->assertStringContainsString($heading, $html);
        }
    }

    public function test_the_page_is_in_thai_when_the_site_is(): void
    {
        $this->get(route('frontend.locale.switch', 'th'));

        $this->get(route('frontend.refund_policy'))
            ->assertOk()
            ->assertSee('นโยบายการยกเลิกและคืนเงิน')
            ->assertSee('ยกเลิกฟรี ภายใน 48 ชั่วโมงก่อนเริ่มโปรแกรม');
    }

    public function test_the_footer_links_to_it(): void
    {
        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee(route('frontend.refund_policy.v2'), false)
            ->assertSee('Cancellation &amp; Refund', false);
    }

    public function test_the_terms_page_points_at_it(): void
    {
        $this->get(route('frontend.terms'))
            ->assertOk()
            ->assertSee(route('frontend.refund_policy'), false);
    }

    public function test_the_old_v2_address_redirects(): void
    {
        $this->get('/v2/refund-policy')->assertRedirect(route('frontend.refund_policy'));
    }
}
