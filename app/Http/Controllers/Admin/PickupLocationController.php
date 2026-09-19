<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PickupLocation;
use App\Models\Province;

class PickupLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $provinces = Province::orderBy('name_th')->get();

        $locations = PickupLocation::with('province')
            ->when($request->query('province_id'), fn ($query, $provinceId) => $query->where('province_id', $provinceId))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pickup_locations.index', compact('locations', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name_th')->get();

        return view('admin.pickup_locations.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'province_id' => 'required|integer|exists:provinces,id',
        'name' => 'required|string|max:255',
        'is_active' => 'sometimes|boolean',
        'is_meeting_point' => 'sometimes|boolean',
    ]);

    $data['is_active'] = $request->has('is_active');
    // A select always sends the key, so read the value, not its presence.
    $data['is_meeting_point'] = $request->boolean('is_meeting_point');

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
        $provinces = Province::orderBy('name_th')->get();

        return view('admin.pickup_locations.edit', compact('pickup_location', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PickupLocation $pickupLocation)
{
    $data = $request->validate([
        'province_id' => 'required|integer|exists:provinces,id',
        'name' => 'required|string|max:255',
        'is_active' => 'sometimes|boolean',
        'is_meeting_point' => 'sometimes|boolean',
    ]);

    $data['is_active'] = $request->has('is_active');
    // A select always sends the key, so read the value, not its presence.
    $data['is_meeting_point'] = $request->boolean('is_meeting_point');

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
