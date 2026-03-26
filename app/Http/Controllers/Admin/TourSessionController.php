<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSession;

class TourSessionController extends Controller
{
    //

    private function normalizeTimeInput(string $value): string
    {
        $normalized = trim($value);

        if (preg_match('/^\d{4}$/', $normalized)) {
            $normalized = substr($normalized, 0, 2) . ':' . substr($normalized, 2, 2);
        }

        $normalized = str_replace('.', ':', $normalized);

        if (!preg_match('/^\d{1,2}:\d{2}$/', $normalized)) {
            abort(422, 'รูปแบบเวลาต้องเป็น HH:mm เช่น 13.30 หรือ 18:25');
        }

        [$hour, $minute] = array_map('intval', explode(':', $normalized));

        if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
            abort(422, 'รูปแบบเวลาไม่ถูกต้อง');
        }

        return sprintf('%02d:%02d:00', $hour, $minute);
    }

    public function index(Tour $tour)
    {
        // ใช้ start_time เป็นตัว sort หลัก
        $sessions = $tour->sessions()->orderBy('start_time', 'asc')->get();

        return view('admin.tours.sessions.index', compact('tour', 'sessions'));
    }

    /**
     * หน้า form create
     */
    public function create(Tour $tour)
    {
        return view('admin.tours.sessions.create', compact('tour'));
    }

    /**
     * บันทึกข้อมูล session ใหม่
     */
    public function store(Request $request, Tour $tour)
    {
        $request->validate([
            'title'              => 'required|string',
            'name'              => 'required|string',
            'start_time'        => ['required', 'regex:/^(\d{4}|\d{1,2}[:.]\d{2})$/'],
            'end_time'          => ['required', 'regex:/^(\d{4}|\d{1,2}[:.]\d{2})$/'],
            'default_capacity'  => 'required|integer|min:1',
            'capacity'          => 'nullable|integer|min:1',
            'is_active'         => 'required|boolean',
        ]);

       // dd($request->title);

        $tour->sessions()->create([
            'name'              => $request->name,
            'title'              => $request->title,
            'start_time'        => $this->normalizeTimeInput($request->start_time),
            'end_time'          => $this->normalizeTimeInput($request->end_time),
            'default_capacity'  => $request->default_capacity,
            'capacity'          => $request->capacity,
            'is_active'         => $request->is_active,
        ]);

        return redirect()
            ->route('admin.tours.sessions.index', $tour->id)
            ->with('success', 'เพิ่ม Session สำเร็จ');
    }


    /**
     * แก้ไข Session
     */
    public function edit(Tour $tour, TourSession $session)
    {
        return view('admin.tours.sessions.edit', compact('tour', 'session'));
    }


    /**
     * บันทึกการแก้ไข
     */
    public function update(Request $request, Tour $tour, TourSession $session)
    {
        $request->validate([
            'title'              => 'required|string',
            'name'              => 'required|string',
            'start_time'        => ['required', 'regex:/^(\d{4}|\d{1,2}[:.]\d{2})$/'],
            'end_time'          => ['required', 'regex:/^(\d{4}|\d{1,2}[:.]\d{2})$/'],
            'default_capacity'  => 'required|integer|min:1',
            'capacity'          => 'nullable|integer|min:1',
            'is_active'         => 'required|boolean',
        ]);

        $session->update([
            'title'             => $request->title,
            'name'              => $request->name,
            'start_time'        => $this->normalizeTimeInput($request->start_time),
            'end_time'          => $this->normalizeTimeInput($request->end_time),
            'default_capacity'  => $request->default_capacity,
            'capacity'          => $request->capacity,
            'is_active'         => $request->is_active,
        ]);

        return redirect()
            ->route('admin.tours.sessions.index', $tour->id)
            ->with('success', 'อัปเดต Session สำเร็จ');
    }


    /**
     * ลบ Session
     */
    public function destroy(Tour $tour, TourSession $session)
    {
        $session->delete();

        return back()->with('success', 'ลบ Session สำเร็จ');
    }

    /**
     * หน้า Sessions ทั้งหมด (ถ้าคุณยังต้องใช้)
     */
    public function all()
    {
        $sessions = TourSession::with('tour')
            ->orderBy('tour_id')
            ->orderBy('start_time')
            ->get();

        return view('admin.tours.sessions.all', compact('sessions'));
    }
}
