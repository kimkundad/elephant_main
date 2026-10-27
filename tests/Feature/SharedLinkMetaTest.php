<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Support\CanonicalUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * What a guest sees when they forward a link: a phone's share sheet sends the
 * page title and nothing else, and a chat app reads the og: tags.
 */
class SharedLinkMetaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        \App\Models\SiteText::forgetCached();
        SiteSetting::create(['site_name' => 'SmallElephants.com']);
    }

    public function test_the_brand_is_in_the_page_title(): void
    {
        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '/<title>[^<]*\| SmallElephants\.com<\/title>/',
            $html
        );
    }

    public function test_a_title_that_already_names_us_is_left_alone(): void
    {
        \App\Models\SiteText::create([
            'key' => 'home.seo.title',
            'locale' => 'en',
            'value' => 'Elephant tours by SmallElephants.com',
        ]);

        $html = $this->get(route('frontend.home'))->assertOk()->getContent();

        $this->assertStringContainsString('<title>Elephant tours by SmallElephants.com</title>', $html);
    }

    public function test_a_chat_app_is_told_which_site_the_link_belongs_to(): void
    {
        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('<meta property="og:site_name" content="SmallElephants.com">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
    }

    public function test_every_page_names_the_one_address_it_lives_at(): void
    {
        $html = $this->get(route('frontend.refund_policy'))->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="http://localhost/refund-policy">', $html);
        $this->assertStringContainsString('<meta property="og:url" content="http://localhost/refund-policy">', $html);
    }

    public function test_a_filtered_page_is_shared_as_the_page_itself(): void
    {
        $html = $this->get('/programs?duration[]=one_hour&utm_source=line')->assertOk()->getContent();

        // The query string is what one guest picked, not part of the address.
        $this->assertStringContainsString('<link rel="canonical" href="http://localhost/programs">', $html);
        $this->assertStringNotContainsString('utm_source', explode('</head>', $html)[0]);
    }

    public function test_a_description_written_over_two_lines_stays_on_one(): void
    {
        \App\Models\SiteText::create([
            'key' => 'home.seo.description',
            'locale' => 'en',
            'value' => "First line.\nSecond line.",
        ]);

        $head = explode('</head>', $this->get(route('frontend.home'))->assertOk()->getContent())[0];

        $this->assertStringContainsString('content="First line. Second line."', $head);
    }

    public function test_the_public_address_is_https_on_the_bare_domain(): void
    {
        config(['app.canonical_host' => null]);

        $request = Request::create('http://smallelephants.com/programs?x=1');

        $this->assertSame('https://www.smallelephants.com/programs', CanonicalUrl::current($request));
    }

    public function test_a_configured_host_wins(): void
    {
        config(['app.canonical_host' => 'www.smallelephants.com']);

        $request = Request::create('http://1.2.3.4/tours/half-day');

        $this->assertSame('https://www.smallelephants.com/tours/half-day', CanonicalUrl::current($request));
    }

    public function test_the_home_page_canonical_keeps_its_slash(): void
    {
        config(['app.canonical_host' => 'www.smallelephants.com']);

        $this->assertSame(
            'https://www.smallelephants.com/',
            CanonicalUrl::current(Request::create('https://www.smallelephants.com/'))
        );
    }

    public function test_a_local_address_is_left_on_http(): void
    {
        config(['app.canonical_host' => null]);

        $this->assertSame(
            'http://localhost/programs',
            CanonicalUrl::current(Request::create('http://localhost/programs'))
        );
    }
}
