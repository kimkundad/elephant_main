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

        foreach ($request->sessions as $session_id => $data) {

            TourAvailability::updateOrCreate(
                [
                    'tour_id' => $tour->id,
                    'session_id' => $session_id,
                    'date' => $date
                ],
                [
                    'is_open' => $data['is_open'] ?? 0,
                    'capacity_override' => $data['capacity_override'] ?? null,
                ]
            );
        }

        return back()->with('success', 'บันทึก Availability สำเร็จ');
    }
}
