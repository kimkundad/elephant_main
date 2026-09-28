<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ReviewRecaptchaTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function withKeys(): void
    {
        config([
            'services.recaptcha.site_key' => 'site-key',
            'services.recaptcha.secret_key' => 'secret-key',
        ]);
    }

    private function reviewPayload(array $attrs = []): array
    {
        return array_merge([
            'author_name' => 'Anna Schmidt',
            'author_email' => 'anna@example.com',
            'rating' => 5,
            'review_text' => 'A calm morning with the elephants and a very knowledgeable guide.',
        ], $attrs);
    }

    private function tour(): Tour
    {
        return $this->makeTour($this->chiangMai());
    }

    public function test_the_widget_replaces_the_arithmetic_question_when_keys_are_set(): void
    {
        $this->withKeys();
        $tour = $this->tour();

        $this->get(route('frontend.tours.show', $tour->slug))
            ->assertOk()
            ->assertSee('g-recaptcha', false)
            ->assertSee('data-sitekey="site-key"', false)
            ->assertDontSee('Anti-spam:');
    }

    public function test_the_arithmetic_question_stays_without_keys(): void
    {
        config(['services.recaptcha.site_key' => null, 'services.recaptcha.secret_key' => null]);
        $tour = $this->tour();

        $this->get(route('frontend.tours.show', $tour->slug))
            ->assertOk()
            ->assertSee('Anti-spam:')
            ->assertDontSee('g-recaptcha', false);
    }

    public function test_a_verified_token_saves_the_review(): void
    {
        $this->withKeys();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);

        $tour = $this->tour();

        $this->post(
            route('frontend.tours.reviews.store.v2', $tour->slug),
            $this->reviewPayload(['g-recaptcha-response' => 'token-from-google'])
        )->assertSessionHasNoErrors();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertFalse(Review::first()->is_active, 'Reviews still wait for admin approval.');
    }

    public function test_a_rejected_token_is_turned_away(): void
    {
        $this->withKeys();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);

        $tour = $this->tour();

        $this->post(
            route('frontend.tours.reviews.store.v2', $tour->slug),
            $this->reviewPayload(['g-recaptcha-response' => 'token-from-google'])
        )->assertSessionHasErrors('recaptcha');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_a_missing_token_is_turned_away(): void
    {
        $this->withKeys();
        Http::fake();

        $tour = $this->tour();

        $this->post(route('frontend.tours.reviews.store.v2', $tour->slug), $this->reviewPayload())
            ->assertSessionHasErrors('recaptcha');

        $this->assertDatabaseCount('reviews', 0);
        Http::assertNothingSent();
    }
}
