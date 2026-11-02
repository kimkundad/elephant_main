<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElephantMenuTest extends TestCase
{
    use RefreshDatabase;

    private function linksToElephants(string $html): int
    {
        return substr_count($html, 'href="' . route('frontend.elephants') . '"');
    }

    public function test_header_and_footer_link_to_our_elephants(): void
    {
        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        // The pill nav, the hamburger menu and the footer.
        $this->assertSame(3, $this->linksToElephants($html));
    }

    public function test_the_menu_entry_is_named_in_the_visitors_language(): void
    {
        $link = '#href="' . preg_quote(route('frontend.elephants'), '#') . '"\s*>%s</a>#';

        $this->withSession(['locale' => 'en'])->get(route('frontend.home'))
            ->assertSee(sprintf('>%s</a>', 'Our Elephants'), false);

        $html = $this->withSession(['locale' => 'th'])->get(route('frontend.home'))->getContent();
        $this->assertMatchesRegularExpression(sprintf($link, 'ช้างของเรา'), $html);
    }

    public function test_the_pill_nav_marks_our_elephants_as_the_current_page(): void
    {
        $html = $this->get(route('frontend.elephants'))->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '#href="' . preg_quote(route('frontend.elephants'), '#') . '"\s+aria-current="page"\s*>#',
            $html
        );
    }
}
