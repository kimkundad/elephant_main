<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

/**
 * The filter panel offers four groups and a tour shows if it matches any
 * ticked box, in any group: the guest says what interests them rather than
 * narrowing a list down.
 */
class ProgramFilterPanelTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private TourTag $feeding;

    protected function setUp(): void
    {
        parent::setUp();

        $this->feeding = TourTag::create([
            'slug' => 'hand-feeding',
            'name_en' => 'Elephant Feeding',
            'name_th' => 'ให้อาหารช้าง',
            'is_active' => true,
        ]);

        $chiangMai = $this->chiangMai();
        $phuket = $this->makeProvince('phuket', ['name_en' => 'Phuket']);

        $this->makeTour($chiangMai, [
            'name' => 'Full day trek',
            'duration' => 'full_day',
            'experience_type' => 'observation',
        ]);

        $short = $this->makeTour($phuket, [
            'name' => 'One hour feeding',
            'duration' => 'one_hour',
            'experience_type' => 'interactive',
        ]);
        $short->tags()->attach($this->feeding);

        $this->makeTour($phuket, [
            'name' => 'Half day bath',
            'duration' => 'half_day_morning',
            'experience_type' => 'interactive',
        ]);
    }

    public function test_the_panel_offers_every_group(): void
    {
        $this->get('/programs')
            ->assertOk()
            ->assertSee('name="province[]"', false)
            ->assertSee('name="duration[]"', false)
            ->assertSee('name="experience[]"', false)
            ->assertSee('name="tags[]"', false)
            ->assertSee('Full Day')
            ->assertSee('Observation Only');
    }

    public function test_nothing_ticked_lists_every_tour(): void
    {
        $this->get('/programs')
            ->assertOk()
            ->assertSee('Full day trek')
            ->assertSee('One hour feeding')
            ->assertSee('Half day bath');
    }

    public function test_a_duration_keeps_only_that_duration(): void
    {
        $this->get('/programs?duration[]=one_hour')
            ->assertOk()
            ->assertSee('One hour feeding')
            ->assertDontSee('Full day trek')
            ->assertDontSee('Half day bath');
    }

    public function test_two_boxes_in_one_group_widen_the_list(): void
    {
        $this->get('/programs?duration[]=one_hour&duration[]=full_day')
            ->assertOk()
            ->assertSee('One hour feeding')
            ->assertSee('Full day trek')
            ->assertDontSee('Half day bath');
    }

    public function test_boxes_in_different_groups_narrow_the_list(): void
    {
        // Each group has to be answered: a one hour tour tagged feeding is not
        // a full day tour, so nothing is left.
        $this->get('/programs?duration[]=full_day&tags[]=hand-feeding')
            ->assertOk()
            ->assertDontSee('Full day trek')
            ->assertDontSee('One hour feeding');
    }

    public function test_a_province_and_a_duration_read_as_one_and_the_other(): void
    {
        $this->get('/programs?province[]=phuket&duration[]=one_hour')
            ->assertOk()
            ->assertSee('One hour feeding')
            ->assertDontSee('Half day bath')
            ->assertDontSee('Full day trek');
    }

    public function test_a_box_that_would_leave_nothing_is_offered_but_not_selectable(): void
    {
        $html = $this->get('/programs?province[]=chiang-mai')->assertOk()->getContent();

        // Chiang Mai has no one hour tour, so that box is shown greyed out.
        $this->assertMatchesRegularExpression(
            '/data-value="one_hour"[^>]*>\s*<input[^>]*disabled/s',
            $html
        );
    }

    public function test_each_box_counts_what_it_would_leave(): void
    {
        $facets = $this->getJson('/programs?partial=1&province[]=phuket')->assertOk()->json('facets');

        $this->assertSame(1, $facets['duration']['one_hour']);
        $this->assertSame(1, $facets['duration']['half_day_morning']);
        $this->assertSame(0, $facets['duration']['full_day'], 'No full day tour in Phuket.');
        $this->assertSame(2, $facets['province']['phuket'], 'A group never counts against its own pick.');
    }

    public function test_a_province_filters_by_location(): void
    {
        $this->get('/programs?province[]=phuket')
            ->assertOk()
            ->assertSee('One hour feeding')
            ->assertSee('Half day bath')
            ->assertDontSee('Full day trek');
    }

    public function test_a_single_province_in_the_old_form_still_works(): void
    {
        $this->get('/programs?province=phuket')
            ->assertOk()
            ->assertSee('One hour feeding')
            ->assertDontSee('Full day trek');
    }

    public function test_an_experience_type_filters_the_list(): void
    {
        $this->get('/programs?experience[]=observation')
            ->assertOk()
            ->assertSee('Full day trek')
            ->assertDontSee('One hour feeding');
    }

    public function test_a_made_up_value_is_ignored_rather_than_emptying_the_page(): void
    {
        $this->get('/programs?duration[]=fortnight')
            ->assertOk()
            ->assertSee('Full day trek')
            ->assertSee('One hour feeding');
    }

    public function test_the_panel_can_fetch_just_the_cards(): void
    {
        $response = $this->getJson('/programs?partial=1&duration[]=one_hour')->assertOk();

        $response->assertJsonStructure(['count', 'html']);
        $this->assertSame(1, $response->json('count'));
        $this->assertStringContainsString('One hour feeding', $response->json('html'));
        $this->assertStringNotContainsString('Full day trek', $response->json('html'));
        // Only the cards: no header, no filter panel.
        $this->assertStringNotContainsString('programFilters', $response->json('html'));
    }

    public function test_the_admin_tour_form_offers_both_fields(): void
    {
        $tour = Tour::firstWhere('name', 'Full day trek');

        $this->actingAsAdmin()
            ->get(route('admin.tours.edit', $tour->id))
            ->assertOk()
            ->assertSee('name="duration"', false)
            ->assertSee('name="experience_type"', false)
            ->assertSee('Full Day')
            ->assertSee('Observation Only');
    }

    public function test_admin_can_set_both_fields_on_a_tour(): void
    {
        $tour = Tour::firstWhere('name', 'Full day trek');

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'province_id' => $tour->province_id,
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'price_adult' => 1000,
                'price_child' => 500,
                'duration' => 'half_day_morning',
                'experience_type' => 'interactive',
            ])
            ->assertSessionHasNoErrors();

        $tour->refresh();

        $this->assertSame('half_day_morning', $tour->duration);
        $this->assertSame('interactive', $tour->experience_type);
    }

    public function test_admin_cannot_save_a_duration_that_does_not_exist(): void
    {
        $tour = Tour::firstWhere('name', 'Full day trek');

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'province_id' => $tour->province_id,
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'price_adult' => 1000,
                'price_child' => 500,
                'duration' => 'fortnight',
            ])
            ->assertSessionHasErrors('duration');
    }
}
