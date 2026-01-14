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
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'is_active' => 'sometimes|boolean',
        'is_meeting_point' => 'sometimes|boolean',
    ]);

    $data['is_active'] = $request->has('is_active');
    $data['is_meeting_point'] = $request->has('is_meeting_point'); // ✅

    PickupLocation::create($data);

    return redirect()->route('admin.pickup-locations.index')
        ->with('success', 'Pickup location created successfully.');
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
    public function update(Request $request, PickupLocation $pickupLocation)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'is_active' => 'sometimes|boolean',
        'is_meeting_point' => 'sometimes|boolean',
    ]);

    $data['is_active'] = $request->has('is_active');
    $data['is_meeting_point'] = $request->has('is_meeting_point'); // ✅

    $pickupLocation->update($data);

    return redirect()->route('admin.pickup-locations.index')
        ->with('success', 'Pickup location updated successfully.');
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
