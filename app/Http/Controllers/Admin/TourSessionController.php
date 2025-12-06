<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSession;

class TourSessionController extends Controller
{
    //

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
            'start_time'        => 'required',
            'end_time'          => 'required',
            'default_capacity'  => 'required|integer|min:1',
            'capacity'          => 'nullable|integer|min:1',
            'is_active'         => 'required|boolean',
        ]);

       // dd($request->title);

        $tour->sessions()->create([
            'name'              => $request->name,
            'title'              => $request->title,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
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
            'name'              => 'required|string',
            'start_time'        => 'required',
            'end_time'          => 'required',
            'default_capacity'  => 'required|integer|min:1',
            'capacity'          => 'nullable|integer|min:1',
            'is_active'         => 'required|boolean',
        ]);

        $session->update([
            'name'              => $request->name,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
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
