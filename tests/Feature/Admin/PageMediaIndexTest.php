<?php

namespace Tests\Feature\Admin;

use App\Models\PageMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageMediaIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_rows_show_a_thumbnail(): void
    {
        PageMedia::create([
            'key' => 'home.hero',
            'locale' => 'th',
            'type' => 'image',
            'disk' => 'public',
            'path' => 'media/hero.jpg',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAsAdmin()
            ->get(route('admin.page-media.index'))
            ->assertOk()
            ->assertSee('<img src="' . asset('storage/media/hero.jpg') . '"', false)
            ->assertSee('Preview');
    }

    public function test_non_image_rows_link_instead_of_showing_a_thumbnail(): void
    {
        PageMedia::create([
            'key' => 'home.clip',
            'locale' => 'th',
            'type' => 'video',
            'disk' => 'public',
            'path' => 'media/clip.mp4',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAsAdmin()
            ->get(route('admin.page-media.index'))
            ->assertOk()
            ->assertSee('home.clip')
            // The admin layout has its own logo <img>, so match the thumbnail style.
            ->assertDontSee('object-fit:cover', false)
            ->assertSee('badge badge-light-primary">Video', false);
    }
}
