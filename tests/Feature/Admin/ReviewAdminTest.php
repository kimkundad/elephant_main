<?php

namespace Tests\Feature\Admin;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ReviewAdminTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_staff_can_add_a_review_without_an_email(): void
    {
        $tour = $this->makeTour($this->chiangMai());

        $this->actingAsAdmin()
            ->post(route('admin.reviews.store'), [
                'tour_id' => $tour->id,
                'author_name' => 'Walk-in guest',
                'rating' => 5,
                'review_text' => 'Left this review on paper at the camp.',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.reviews.index'));

        $review = Review::first();

        $this->assertNotNull($review);
        $this->assertNull($review->author_email);
    }

    public function test_the_admin_form_does_not_mark_email_as_required(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.reviews.create'))
            ->assertOk()
            ->assertSee('(ไม่บังคับ)');
    }
}
