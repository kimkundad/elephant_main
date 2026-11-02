<?php

namespace Tests\Feature;

use App\Models\Elephant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElephantWithoutHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // History is optional in the admin form.
        Elephant::create([
            'name' => 'พลอย',
            'slug' => 'ploy',
            'history' => null,
            'images' => ['https://example.com/ploy.jpg'],
            'is_active' => true,
        ]);
    }

    public function test_home_page_shows_an_elephant_without_a_history(): void
    {
        $this->get(route('frontend.home'))->assertOk()->assertSee('พลอย');
    }

    public function test_our_elephants_page_shows_an_elephant_without_a_history(): void
    {
        $this->get(route('frontend.elephants'))->assertOk()->assertSee('พลอย');
    }
}
