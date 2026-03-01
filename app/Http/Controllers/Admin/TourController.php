<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourTag;
use App\Models\TourTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::orderBy('id', 'desc')->get();

        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        $tags = $this->activeTags();

        return view('admin.tours.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'short_description_th' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'description_th' => 'nullable|string',
            'description_en' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:tour_tags,id',
            'is_active' => 'nullable|boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'tour_' . time() . '.' . $file->getClientOriginalExtension();

            Storage::disk('spaces')->put(
                'elephant/tours/' . $filename,
                file_get_contents($file),
                'public'
            );

            $thumbnailPath = Storage::disk('spaces')->url('elephant/tours/' . $filename);
        }

        $slugBase = $data['name_en'] ?: $data['name_th'];
        $tour = Tour::create([
            'name' => $data['name_th'],
            'slug' => Str::slug($slugBase),
            'short_description' => $data['short_description_th'] ?? null,
            'description' => $data['description_th'] ?? null,
            'min_price' => $data['min_price'],
            'max_price' => $data['max_price'],
            'is_active' => (bool) ($data['is_active'] ?? true),
            'thumbnail' => $thumbnailPath,
        ]);

        $this->syncTranslations($tour, $data);
        $tour->tags()->sync($data['tag_ids'] ?? []);

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour program created successfully.');
    }

    public function edit($id)
    {
        $tour = Tour::with(['tags', 'translations'])->findOrFail($id);
        $tags = $this->activeTags();

        $translationTh = $tour->translations->firstWhere('locale', 'th');
        $translationEn = $tour->translations->firstWhere('locale', 'en');

        return view('admin.tours.edit', compact('tour', 'tags', 'translationTh', 'translationEn'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);

        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'short_description_th' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'description_th' => 'nullable|string',
            'description_en' => 'nullable|string',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:tour_tags,id',
            'is_active' => 'nullable|boolean',
        ]);

        $disk = Storage::disk('spaces');
        $newThumbnailUrl = $tour->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if (!empty($tour->thumbnail)) {
                $rootUrl = rtrim($disk->url(''), '/');
                $oldPath = str_replace($rootUrl . '/', '', $tour->thumbnail);
                $disk->delete($oldPath);
            }

            $file = $request->file('thumbnail');
            $filename = 'tour_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = 'elephant/tours/' . $filename;
            $disk->put($uploadPath, file_get_contents($file), 'public');
            $newThumbnailUrl = $disk->url($uploadPath);
        }

        $slugBase = $data['name_en'] ?: $data['name_th'];
        $tour->update([
            'name' => $data['name_th'],
            'slug' => Str::slug($slugBase),
            'short_description' => $data['short_description_th'] ?? null,
            'description' => $data['description_th'] ?? null,
            'min_price' => $data['min_price'],
            'max_price' => $data['max_price'],
            'is_active' => (bool) ($data['is_active'] ?? false),
            'thumbnail' => $newThumbnailUrl,
        ]);

        $this->syncTranslations($tour, $data);
        $tour->tags()->sync($data['tag_ids'] ?? []);

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour program updated successfully.');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);

        if ($tour->thumbnail) {
            $disk = Storage::disk('spaces');
            $rootUrl = rtrim($disk->url(''), '/');
            $path = str_replace($rootUrl . '/', '', $tour->thumbnail);
            $disk->delete($path);
        }

        $tour->delete();

        return back()->with('success', 'Tour program deleted successfully.');
    }

    public function toggle($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->is_active = !$tour->is_active;
        $tour->save();

        return back();
    }

    private function activeTags()
    {
        return TourTag::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function syncTranslations(Tour $tour, array $data): void
    {
        TourTranslation::updateOrCreate(
            ['tour_id' => $tour->id, 'locale' => 'th'],
            [
                'name' => $data['name_th'],
                'short_description' => $data['short_description_th'] ?? null,
                'description' => $data['description_th'] ?? null,
            ]
        );

        TourTranslation::updateOrCreate(
            ['tour_id' => $tour->id, 'locale' => 'en'],
            [
                'name' => $data['name_en'],
                'short_description' => $data['short_description_en'] ?? null,
                'description' => $data['description_en'] ?? null,
            ]
        );
    }
}