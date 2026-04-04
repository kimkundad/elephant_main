<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TourAvailability; // ✅ เพิ่มบรรทัดนี้

class TourController extends Controller
{
    private function createReviewCaptcha(string $tourSlug): array
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);

        session([
            "tour_review_captcha_answer.{$tourSlug}" => $a + $b,
            "tour_review_captcha_question.{$tourSlug}" => "{$a} + {$b}",
        ]);

        return ['question' => "{$a} + {$b}"];
    }

    private function approvedReviewsForTour(Tour $tour)
    {
        return Review::query()
            ->active()
            ->where('tour_id', $tour->id)
            ->orderBy('sort_order')
            ->orderByDesc('reviewed_at')
            ->orderByDesc('id')
            ->get();
    }

    private function getBookableSessionsForDate($sessions, string $selectedDate, Carbon $now)
    {
        $isToday = ($selectedDate === $now->toDateString());

        return $sessions
            ->filter(function ($s) use ($selectedDate, $now, $isToday) {
                if ($isToday) {
                    $sessionStart = Carbon::parse($selectedDate . ' ' . $s->start_time);

                    if ($sessionStart->lte($now)) {
                        return false;
                    }
                }

                return (int) $s->remainingCapacity($selectedDate) > 0;
            })
            ->values();
    }

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

    $sessions = $tour->sessions()
        ->where('is_active', 1)
        ->orderBy('start_time')
        ->get();

    $fallbackNow = now();
    $fallbackSessionsForSelected = $this->getBookableSessionsForDate($sessions, $selectedDate, $fallbackNow);

    if ($selectedDate === $fallbackNow->toDateString() && $fallbackSessionsForSelected->isEmpty()) {
        $selectedDate = $fallbackNow->copy()->addDay()->toDateString();
        $month = Carbon::parse($selectedDate)->format('Y-m');
        $sessionsForSelected = $this->getBookableSessionsForDate($sessions, $selectedDate, $fallbackNow);

        return view('frontend.pages.tours.show', compact(
            'tour', 'selectedDate', 'month', 'sessionsForSelected'
        ));
    }

    // ✅ ใช้ timezone ของระบบ (แนะนำตั้งค่า config/app.php => timezone = 'Asia/Bangkok')
    $now = now();
    $isToday = ($selectedDate === $now->toDateString());

    $sessionsForSelected = $sessions
        ->filter(function ($s) use ($selectedDate, $now, $isToday) {

            // 1) กรองตามเวลา (เฉพาะวัน "วันนี้")
            if ($isToday) {
                // เอาวัน+เวลา session มารวมเป็น datetime แล้วเทียบกับตอนนี้
                $sessionStart = Carbon::parse($selectedDate.' '.$s->start_time);

                // ถ้าเลยเวลาเริ่มไปแล้ว => ไม่ให้จอง
                if ($sessionStart->lte($now)) {
                    return false;
                }
            }

            // 2) กรองตาม capacity / availability ที่คุณมีอยู่แล้ว
            return (int) $s->remainingCapacity($selectedDate) > 0;
        })
        ->values();

    return view('frontend.pages.tours.show', compact(
        'tour', 'selectedDate', 'month', 'sessionsForSelected'
    ));
}

    public function showV2(string $slug, Request $request)
    {
        $tour = Tour::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $tourReviews = $this->approvedReviewsForTour($tour);
        $reviewCaptcha = $this->createReviewCaptcha($tour->slug);

        $selectedDate = $request->query('date', now()->toDateString());
        $month = $request->query('month', now()->format('Y-m'));

        $sessions = $tour->sessions()
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        $fallbackNow = now();
        $fallbackSessionsForSelected = $this->getBookableSessionsForDate($sessions, $selectedDate, $fallbackNow);

        if ($selectedDate === $fallbackNow->toDateString() && $fallbackSessionsForSelected->isEmpty()) {
            $selectedDate = $fallbackNow->copy()->addDay()->toDateString();
            $month = Carbon::parse($selectedDate)->format('Y-m');
            $sessionsForSelected = $this->getBookableSessionsForDate($sessions, $selectedDate, $fallbackNow);

            return view('frontend_v2.pages.tours.show', compact(
                'tour', 'selectedDate', 'month', 'sessionsForSelected', 'tourReviews', 'reviewCaptcha'
            ));
        }

        $now = now();
        $isToday = ($selectedDate === $now->toDateString());

        $sessionsForSelected = $sessions
            ->filter(function ($s) use ($selectedDate, $now, $isToday) {

                if ($isToday) {
                    $sessionStart = Carbon::parse($selectedDate.' '.$s->start_time);
                    if ($sessionStart->lte($now)) {
                        return false;
                    }
                }

                return (int) $s->remainingCapacity($selectedDate) > 0;
            })
            ->values();

        return view('frontend_v2.pages.tours.show', compact(
            'tour', 'selectedDate', 'month', 'sessionsForSelected', 'tourReviews', 'reviewCaptcha'
        ));
    }

    public function storeReviewV2(string $slug, Request $request)
    {
        $tour = Tour::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $rateKey = 'tour-review:' . $request->ip() . ':' . $tour->id;

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            return back()
                ->withErrors(['review_form' => 'Too many review attempts. Please try again later.'])
                ->withInput();
        }

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'min:20', 'max:5000'],
            'captcha_answer' => ['required', 'integer'],
            'website' => ['nullable', 'max:0'],
        ]);

        $expectedAnswer = (int) session("tour_review_captcha_answer.{$tour->slug}", -1);

        if ((int) $data['captcha_answer'] !== $expectedAnswer) {
            RateLimiter::hit($rateKey, 3600);

            return back()
                ->withErrors(['captcha_answer' => 'Incorrect captcha answer. Please try again.'])
                ->withInput();
        }

        Review::create([
            'tour_id' => $tour->id,
            'author_name' => $data['author_name'],
            'author_email' => $data['author_email'] ?? null,
            'source' => Review::SOURCE_CUSTOMER,
            'rating' => (int) $data['rating'],
            'review_text' => $data['review_text'],
            'avatar_color' => Review::randomAvatarColor(),
            'avatar_variant' => Review::randomAvatarVariant(),
            'reviewed_at' => now(),
            'sort_order' => 0,
            'is_active' => false,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
        ]);

        RateLimiter::clear($rateKey);

        return redirect()
            ->route('frontend.tours.show.v2', [
                'slug' => $tour->slug,
                'month' => $request->query('month', now()->format('Y-m')),
                'date' => $request->query('date', now()->toDateString()),
            ])
            ->with('review_success', 'Thank you. Your review has been submitted and is waiting for approval.');
    }



    public function sessionsForDate($slug, Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $tour = Tour::where('slug', $slug)->firstOrFail();

        // 1) sessions หลักจาก tour_sessions
        $sessions = $tour->sessions()
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get(['id','tour_id','title','name','start_time','end_time','default_capacity','capacity']);

        // 2) exception ของวันนั้น (ปิด/override) จาก tour_session_availability
        $availMap = TourAvailability::where('tour_id', $tour->id)
            ->where('date', $date)
            ->get()
            ->keyBy('session_id');

        // 3) booked ต่อ session (query เดียว)
        $bookedMap = Booking::where('tour_id', $tour->id)
            ->where('date', $date)
            // TODO: ใส่เงื่อนไขสถานะที่ “นับจริง” เช่น ->where('payment_status','paid')
            ->selectRaw('session_id, COALESCE(SUM(total_guests),0) as booked')
            ->groupBy('session_id')
            ->pluck('booked', 'session_id');

        // 4) ผสมข้อมูล + คำนวณ remaining
        $result = $sessions->map(function($s) use ($availMap, $bookedMap) {

            $a = $availMap->get($s->id);

            // default capacity
            $cap = $s->capacity ?? $s->default_capacity;

            // override capacity เฉพาะวัน
            if ($a && $a->capacity_override !== null) {
                $cap = (int)$a->capacity_override;
            }

            // ถ้าถูกปิดวันนั้น
            if ($a && (int)$a->is_open === 0) {
                $remaining = 0;
            } else {
                $booked = (int)($bookedMap[$s->id] ?? 0);
                $remaining = max($cap - $booked, 0);
            }

            return [
                'id' => $s->id,
                'title' => $s->title ?? $s->name,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'capacity' => $cap,
                'booked' => (int)($bookedMap[$s->id] ?? 0),
                'remaining' => $remaining,
                'is_open' => $a ? (int)$a->is_open : 1, // ไม่มี record = เปิด
            ];
        })
        // เอาเฉพาะรอบที่จองได้
        ->filter(fn($x) => $x['is_open'] === 1 && $x['remaining'] > 0)
        ->values();

        return response()->json([
            'date' => $date,
            'sessions' => $result,
        ]);
    }
}
