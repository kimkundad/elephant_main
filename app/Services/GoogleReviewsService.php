<?php

namespace App\Services;

use App\Models\Review;

class GoogleReviewsService
{
    public function get(): array
    {
        $manualReviews = $this->getManualReviews();

        return $this->buildResponse($manualReviews);
    }

    private function getManualReviews(): array
    {
        return Review::query()
            ->active()
            ->orderBy('sort_order')
            ->orderByDesc('reviewed_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Review $review) => [
                'author_name' => $review->author_name,
                'profile_photo_url' => null,
                'rating' => $review->rating,
                'relative_time_description' => optional($review->reviewed_at)->format('d M Y H:i') ?: $review->created_at?->format('d M Y H:i') ?: '',
                'text' => $review->review_text,
                'author_url' => null,
                'avatar_color' => $review->avatar_color ?: Review::randomAvatarColor(),
                'avatar_variant' => $review->avatar_variant ?: Review::randomAvatarVariant(),
                'source' => 'manual',
            ])
            ->values()
            ->all();
    }

    private function buildResponse(array $manualReviews): array
    {
        $rating = count($manualReviews) > 0
            ? round((float) collect($manualReviews)->avg('rating'), 1)
            : 0;
        $userRatingsTotal = count($manualReviews);

        return [
            'place_name' => 'Customer Reviews',
            'rating' => $rating,
            'user_ratings_total' => $userRatingsTotal,
            'google_url' => null,
            'reviews' => $manualReviews,
        ];
    }
}
