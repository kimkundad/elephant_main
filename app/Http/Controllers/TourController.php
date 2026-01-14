<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TourAvailability; // ✅ เพิ่มบรรทัดนี้


class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::query()
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->paginate(9);

        return view('frontend.pages.tours.index', compact('tours'));
    }


public function show(string $slug, Request $request)
{
    $tour = Tour::query()
        ->where('slug', $slug)
        ->where('is_active', 1)
        ->firstOrFail();

    $selectedDate = $request->query('date', now()->toDateString());
    $month = $request->query('month', now()->format('Y-m'));

    // ดึง sessions ของทัวร์
    $sessions = $tour->sessions()
        ->where('is_active', 1)
        ->orderBy('start_time')
        ->get();

    // ✅ ดึง availability เฉพาะวันนั้น และเฉพาะที่เปิด (is_open=1)
    $openAvailability = TourAvailability::query()
        ->where('tour_id', $tour->id)
        ->where('date', $selectedDate)
        ->where('is_open', 1)
        ->get()
        ->keyBy('session_id');

    // ✅ ถ้าวันนั้นไม่มี availability เลย -> ต้องไม่มีรอบ
    if ($openAvailability->isEmpty()) {
        $sessionsForSelected = collect();
    } else {
        // ✅ เอาเฉพาะ session ที่มี availability เปิดของวันนั้น
        $sessionsForSelected = $sessions
            ->filter(fn ($s) => $openAvailability->has($s->id))
            ->filter(fn ($s) => (int) $s->remainingCapacity($selectedDate) > 0)
            ->values();
    }

    return view('frontend.pages.tours.show', compact(
        'tour',
        'selectedDate',
        'month',
        'sessionsForSelected'
    ));
}



public function sessionsForDate(string $slug, Request $request)
{
    $tour = Tour::query()
        ->where('slug', $slug)
        ->where('is_active', 1)
        ->firstOrFail();

    $date = $request->query('date', now()->toDateString());

    $openAvailability = TourAvailability::query()
        ->where('tour_id', $tour->id)
        ->where('date', $date)
        ->where('is_open', 1)
        ->get()
        ->keyBy('session_id');

    if ($openAvailability->isEmpty()) {
        return response()->json(['date' => $date, 'sessions' => []]);
    }

    $sessions = $tour->sessions()
        ->where('is_active', 1)
        ->orderBy('start_time')
        ->get()
        ->filter(fn ($s) => $openAvailability->has($s->id))
        ->map(function ($s) use ($date) {
            return [
                'id' => $s->id,
                'title' => $s->title ?? $s->name ?? 'Session',
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'remaining' => (int) $s->remainingCapacity($date),
            ];
        })
        ->filter(fn ($x) => $x['remaining'] > 0)
        ->values();

    return response()->json(['date' => $date, 'sessions' => $sessions]);
}
}
