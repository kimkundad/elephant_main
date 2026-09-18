<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::query()
            ->withCount(['tours', 'pickupLocations'])
            ->orderBy('name_th')
            ->paginate(20);

        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(Request $request)
    {
        Province::create($this->validated($request));

        return redirect()->route('admin.provinces.index')
            ->with('success', 'เพิ่มจังหวัดเรียบร้อย');
    }

    public function edit(Province $province)
    {
        return view('admin.provinces.edit', compact('province'));
    }

    public function update(Request $request, Province $province)
    {
        $province->update($this->validated($request, $province));

        return redirect()->route('admin.provinces.index')
            ->with('success', 'บันทึกจังหวัดเรียบร้อย');
    }

    public function destroy(Province $province)
    {
        if ($province->tours()->exists() || $province->pickupLocations()->exists()) {
            return back()->withErrors([
                'province' => 'ลบไม่ได้: ยังมีทัวร์หรือจุดรับส่งอยู่ในจังหวัดนี้ ย้ายออกก่อน หรือปิดการใช้งานแทน',
            ]);
        }

        $province->delete();

        return redirect()->route('admin.provinces.index')
            ->with('success', 'ลบจังหวัดเรียบร้อย');
    }

    /** Validate the form; the slug defaults to the English name and must stay unique. */
    private function validated(Request $request, ?Province $province = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name_en')),
        ]);

        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('provinces', 'slug')->ignore($province?->id),
            ],
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
