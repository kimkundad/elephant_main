<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourTagController extends Controller
{
    public function index()
    {
        $tags = TourTag::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.tour_tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tour_tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tour_tags,slug',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        TourTag::create([
            'name_th' => $data['name_th'],
            'name_en' => $data['name_en'] ?? null,
            'slug' => $data['slug'] ?: Str::slug($data['name_en'] ?: $data['name_th']),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.tour-tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(TourTag $tour_tag)
    {
        return view('admin.tour_tags.edit', ['tag' => $tour_tag]);
    }

    public function update(Request $request, TourTag $tour_tag)
    {
        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tour_tags,slug,' . $tour_tag->id,
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $tour_tag->update([
            'name_th' => $data['name_th'],
            'name_en' => $data['name_en'] ?? null,
            'slug' => $data['slug'] ?: Str::slug($data['name_en'] ?: $data['name_th']),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()->route('admin.tour-tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(TourTag $tour_tag)
    {
        $tour_tag->delete();

        return redirect()->route('admin.tour-tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}

