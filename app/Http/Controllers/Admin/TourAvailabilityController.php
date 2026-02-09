<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSession;
use App\Models\TourAvailability;

class TourAvailabilityController extends Controller
{
    /**
     * หน้าเลือกวัน + ดู availability ของวันนั้น
     */
    public function index(Tour $tour, Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        // โหลด sessions ของโปรแกรมนี้
        $sessions = $tour->sessions()->orderBy('start_time')->get();

        // โหลด availability รายวัน
        $availabilities = TourAvailability::where('tour_id', $tour->id)
            ->where('date', $date)
            ->get()
            ->keyBy('session_id');

        return view('admin.tours.availability.index', compact('tour', 'sessions', 'availabilities', 'date'));
    }

    /**
     * บันทึกการตั้งค่า Availability รายวัน
     */
    public function store(Tour $tour, Request $request)
    {
        $date = $request->date;

        foreach (($request->sessions ?? []) as $session_id => $data) {

            // ค่าเริ่มต้น: เปิด
            $isOpen = isset($data['is_open']) ? (int)$data['is_open'] : 1;

            // capacity_override: ถ้าเป็น '' ให้เป็น null
            $cap = $data['capacity_override'] ?? null;
            $cap = ($cap === '' || $cap === null) ? null : (int)$cap;

            // ✅ ถ้า "เปิด" และ "ไม่ override" => ถือว่า default -> ไม่ต้องมี record
            if ($isOpen === 1 && $cap === null) {
                TourAvailability::where('tour_id', $tour->id)
                    ->where('session_id', $session_id)
                    ->where('date', $date)
                    ->delete();
                continue;
            }

            // ✅ กรณีที่เป็น exception: ปิด หรือ override capacity
            TourAvailability::updateOrCreate(
                [
                    'tour_id' => $tour->id,
                    'session_id' => $session_id,
                    'date' => $date
                ],
                [
                    'is_open' => $isOpen,
                    'capacity_override' => $cap,
                ]
            );
        }

        return back()->with('success', 'บันทึก Availability สำเร็จ');
    }

}
