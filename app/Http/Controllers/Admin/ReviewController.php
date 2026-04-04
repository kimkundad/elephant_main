<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::query()
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

        return view('admin.reviews.create', compact('review'));
    }

    public function store(Request $request)
    {
        $data = $this->validateReview($request);
        $data['avatar_color'] = $data['avatar_color'] ?: Review::randomAvatarColor();
        $data['avatar_variant'] = $data['avatar_variant'] ?: Review::randomAvatarVariant();

        Review::create($data);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $this->validateReview($request);
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

    private function validateReview(Request $request): array
    {
        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'max:5000'],
            'reviewed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'avatar_color' => ['nullable', 'string', 'max:32'],
            'avatar_variant' => ['nullable', 'in:' . implode(',', Review::AVATAR_VARIANTS)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
