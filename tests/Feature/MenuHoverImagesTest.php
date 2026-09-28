<?php

namespace Tests\Feature;

use App\Models\PageMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuHoverImagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        PageMedia::forgetResolved();
    }

    private function media(string $key, string $path): void
    {
        PageMedia::create([
            'key' => $key,
            'locale' => '', // shown in every language
            'disk' => 'public',
            'path' => $path,
            'alt_text' => 'Small Elephants',
            'is_active' => true,
        ]);

        PageMedia::forgetResolved();
    }

    public function test_each_menu_item_hovers_its_own_picture(): void
    {
        $this->media('v2.header.menu.about_image', 'page-media/about.jpg');
        $this->media('v2.header.menu.programs_image', 'page-media/programs.jpg');
        $this->media('v2.header.menu.contact_image', 'page-media/contact.jpg');

        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('page-media/about.jpg', false)
            ->assertSee('page-media/programs.jpg', false)
            ->assertSee('page-media/contact.jpg', false);
    }

    public function test_menu_items_without_a_picture_fall_back_to_the_shared_one(): void
    {
        $this->media('v2.header.menu.hover_image', 'page-media/shared-hover.jpg');

        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertSame(
            4,
            substr_count($html, 'page-media/shared-hover.jpg'),
            'The shared image stands in for all three menu items plus the hover slot itself.'
        );
    }

    public function test_the_menu_no_longer_points_at_the_missing_template_images(): void
    {
        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertDontSee('/assets/images/cover-menu/', false);
    }
}
