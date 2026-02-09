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

    $sessions = $tour->sessions()
        ->where('is_active', 1)
        ->orderBy('start_time')
        ->get();

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
