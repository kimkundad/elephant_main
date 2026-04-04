<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleReviewsService
{
    public function get(): array
    {
        $placeId = config('services.google_places.place_id');
        $key = config('services.google_places.key');
        $ttl = now()->addMinutes(config('services.google_places.cache_minutes', 60));
        $manualReviews = $this->getManualReviews();

        if (!$placeId || !$key) {
            return $this->buildResponse([], null, null, null, $manualReviews);
        }

        return Cache::remember("google_reviews:{$placeId}", $ttl, function () use ($placeId, $key, $manualReviews) {
            $url = 'https://maps.googleapis.com/maps/api/place/details/json';

            try {
                $res = Http::get($url, [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,user_ratings_total,reviews,url',
                    'language' => 'en',
                    'key' => $key,
                ])->throw()->json();
            } catch (\Throwable $e) {
                return $this->buildResponse([], null, null, null, $manualReviews);
            }

            $result = $res['result'] ?? [];

            $reviews = collect($result['reviews'] ?? [])
                ->map(fn ($review) => [
                    'author_name' => $review['author_name'] ?? '',
                    'profile_photo_url' => $review['profile_photo_url'] ?? null,
                    'rating' => $review['rating'] ?? null,
                    'relative_time_description' => $review['relative_time_description'] ?? '',
                    'text' => $review['text'] ?? '',
                    'author_url' => $review['author_url'] ?? null,
                    'source' => 'google',
                ])
                ->values()
                ->all();

            return $this->buildResponse(
                $reviews,
                $result['name'] ?? '',
                $result['rating'] ?? null,
                $result['user_ratings_total'] ?? null,
                $manualReviews,
                $result['url'] ?? null
            );
        });
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

    private function buildResponse(
        array $googleReviews,
        ?string $placeName,
        ?float $rating,
        ?int $userRatingsTotal,
        array $manualReviews,
        ?string $googleUrl = null
    ): array {
        if ($rating === null && count($manualReviews) > 0) {
            $rating = round((float) collect($manualReviews)->avg('rating'), 1);
        }

        if ($userRatingsTotal === null && count($manualReviews) > 0) {
            $userRatingsTotal = count($manualReviews);
        }

        return [
            'place_name' => $placeName ?? '',
            'rating' => $rating,
            'user_ratings_total' => $userRatingsTotal,
            'google_url' => $googleUrl,
            'reviews' => array_merge($manualReviews, $googleReviews),
        ];
    }
}
