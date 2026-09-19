<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_shows_every_group_and_link(): void
    {
        $response = $this->actingAsAdmin()->get(route('admin.dashboard'))->assertOk();

        foreach (['ภาพรวม', 'การขาย', 'ทัวร์', 'เนื้อหาเว็บ', 'ระบบ'] as $group) {
            $response->assertSee($group);
        }

        foreach ([
            route('admin.bookings.index'),
            route('admin.agents.index'),
            route('admin.discount-codes.index'),
            route('admin.reports.agents'),
            route('admin.tours.index'),
            route('admin.sessions.all'),
            route('admin.tour-tags.index'),
            route('admin.provinces.index'),
            route('admin.pickup-locations.index'),
            route('admin.elephants.index'),
            route('admin.reviews.index'),
            route('admin.site-texts.home'),
            route('admin.site-texts.about'),
            route('admin.page-media.index'),
            route('admin.settings.edit'),
            url('admin/customers'),
            url('admin/users'),
            url('admin/logout'),
        ] as $link) {
            $response->assertSee($link, false);
        }
    }

    public function test_groups_keep_their_order(): void
    {
        $html = $this->actingAsAdmin()->get(route('admin.dashboard'))->assertOk()->getContent();

        $this->assertLessThan(mb_strpos($html, 'การขาย'), mb_strpos($html, 'ภาพรวม'));
        $this->assertLessThan(mb_strpos($html, 'เนื้อหาเว็บ'), mb_strpos($html, 'ทัวร์'));
        $this->assertLessThan(mb_strpos($html, 'ระบบ'), mb_strpos($html, 'เนื้อหาเว็บ'));
    }

    public function test_current_page_link_is_marked_active(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.provinces.index'))
            ->assertOk()
            ->assertSee('menu-link active" href="' . route('admin.provinces.index'), false);
    }
}
