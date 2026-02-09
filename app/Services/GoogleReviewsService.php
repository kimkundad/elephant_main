<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleReviewsService
{
    public function get(): array
    {
        $placeId = config('services.google_places.place_id');
        $key     = config('services.google_places.key');
        $ttl     = now()->addMinutes(config('services.google_places.cache_minutes', 60));

        return Cache::remember("google_reviews:{$placeId}", $ttl, function () use ($placeId, $key) {

            // Places API - Place Details (Legacy Web Service)
            // docs: Place Details (Legacy) + Place Data Fields :contentReference[oaicite:4]{index=4}
            $url = "https://maps.googleapis.com/maps/api/place/details/json";

            $res = Http::get($url, [
                'place_id' => $placeId,
                'fields'   => 'name,rating,user_ratings_total,reviews,url',
                'language' => 'en', // เปลี่ยนเป็น th ได้ แต่รีวิวจะตามภาษาที่มี
                'key'      => $key,
            ])->throw()->json();

            $result = $res['result'] ?? [];

            $reviews = collect($result['reviews'] ?? [])
                ->map(fn ($r) => [
                    'author_name' => $r['author_name'] ?? '',
                    'profile_photo_url' => $r['profile_photo_url'] ?? null,
                    'rating' => $r['rating'] ?? null,
                    'relative_time_description' => $r['relative_time_description'] ?? '',
                    'text' => $r['text'] ?? '',
                    'author_url' => $r['author_url'] ?? null,
                ])
                ->values()
                ->all();

            return [
                'place_name' => $result['name'] ?? '',
                'rating' => $result['rating'] ?? null,
                'user_ratings_total' => $result['user_ratings_total'] ?? null,
                'google_url' => $result['url'] ?? null, // ลิงก์ไปหน้าสถานที่บน Google
                'reviews' => $reviews,
            ];
        });
    }
}
