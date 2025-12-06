<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PickupLocation;

class PickupLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $locations = PickupLocation::orderBy('name')->paginate(20);
        return view('admin.pickup_locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pickup_locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name'        => 'required|string|max:255',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        PickupLocation::create($request->all());

        return redirect()->route('admin.pickup-locations.index')
            ->with('success', 'เพิ่มจุดรับส่งสำเร็จ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PickupLocation $pickup_location)
    {
        //
      //  dd($pickup_location);
        return view('admin.pickup_locations.edit', compact('pickup_location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PickupLocation $pickup_location)
    {
        //
        $request->validate([
            'name'        => 'required|string|max:255',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $pickup_location->update($request->all());

        return redirect()->route('admin.pickup-locations.index')
            ->with('success', 'อัปเดตจุดรับส่งสำเร็จ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PickupLocation $pickup_location)
    {
        //
        $pickup_location->delete();
        return back()->with('success', 'ลบสำเร็จ');
    }
}
