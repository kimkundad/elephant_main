<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::query()
            ->with('tour')
            ->orderBy('sort_order')
            ->orderByDesc('reviewed_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $review = new Review([
            'rating' => 5,
            'sort_order' => 0,
            'is_active' => true,
            'reviewed_at' => now(),
            'avatar_color' => Review::randomAvatarColor(),
            'avatar_variant' => Review::randomAvatarVariant(),
        ]);

        $tours = Tour::query()->orderBy('name')->get();

        return view('admin.reviews.create', compact('review', 'tours'));
    }

    public function store(Request $request)
    {
        $data = $this->validateReview($request);
        $data['source'] = Review::SOURCE_ADMIN;
        $data['avatar_color'] = $data['avatar_color'] ?: Review::randomAvatarColor();
        $data['avatar_variant'] = $data['avatar_variant'] ?: Review::randomAvatarVariant();

        Review::create($data);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    public function edit(Review $review)
    {
        $tours = Tour::query()->orderBy('name')->get();

        return view('admin.reviews.edit', compact('review', 'tours'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $this->validateReview($request);
        $data['source'] = $review->source ?: Review::SOURCE_ADMIN;
        $data['avatar_color'] = $data['avatar_color'] ?: ($review->avatar_color ?: Review::randomAvatarColor());
        $data['avatar_variant'] = $data['avatar_variant'] ?: ($review->avatar_variant ?: Review::randomAvatarVariant());

        $review->update($data);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function toggleStatus(Review $review)
    {
        $review->update([
            'is_active' => ! $review->is_active,
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', $review->is_active ? 'Review approved and visible now.' : 'Review hidden successfully.');
    }

    private function validateReview(Request $request): array
    {
        $data = $request->validate([
            'tour_id' => ['nullable', 'integer', 'exists:tours,id'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'max:5000'],
            'reviewed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'avatar_color' => ['nullable', 'string', 'max:32'],
            'avatar_variant' => ['nullable', 'in:' . implode(',', Review::AVATAR_VARIANTS)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['tour_id'] = $data['tour_id'] ?? null;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
