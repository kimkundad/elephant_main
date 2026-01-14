<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickupLocation;

class PickupLocationLookupController extends Controller
{
    // ใช้สำหรับ autosuggest
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $rows = PickupLocation::query()
            ->where('is_active', 1)
            ->where('is_meeting_point', 0) // เฉพาะโรงแรม/จุดรับปกติ
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($rows);
    }

    // ใช้สำหรับ “เช็คชื่อที่พิมพ์” ว่าตรงกับ DB ไหม (match แบบ exact)
    public function resolve(Request $request)
{
    $name = trim((string)$request->query('name', ''));

    if ($name === '') {
        return response()->json(['found' => false]);
    }

    $row = \App\Models\PickupLocation::query()
        ->where('is_active', 1)
        ->where('is_meeting_point', 0)
        ->where('name', $name) // ✅ exact match
        ->first(['id', 'name']);

    if (!$row) {
        return response()->json(['found' => false]);
    }

    return response()->json(['found' => true, 'id' => $row->id, 'name' => $row->name]);
}

}
