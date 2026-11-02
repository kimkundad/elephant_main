<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTextAdminTest extends TestCase
{
    use RefreshDatabase;

    /** Every field the form renders, with whatever it is prefilled with or a filler. */
    private function formValues(string $html): array
    {
        preg_match_all('/name="([a-z0-9_]+)"/', $html, $m);
        $values = [];
        foreach (array_unique($m[1]) as $name) {
            $values[$name] = 'x';
        }
        return $values;
    }

    public function test_home_texts_save(): void
    {
        $admin = $this->actingAsAdmin();
        $html = $admin->get(route('admin.site-texts.home'))->assertOk()->getContent();

        $this->withoutExceptionHandling();
        $admin->post(route('admin.site-texts.home.update'), $this->formValues($html))
            ->assertRedirect(route('admin.site-texts.home'));
    }

    public function test_about_texts_save(): void
    {
        $admin = $this->actingAsAdmin();
        $html = $admin->get(route('admin.site-texts.about'))->assertOk()->getContent();

        $this->withoutExceptionHandling();
        $admin->post(route('admin.site-texts.about.update'), $this->formValues($html))
            ->assertRedirect(route('admin.site-texts.about'));
    }
}
