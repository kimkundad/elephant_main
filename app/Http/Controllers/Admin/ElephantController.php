<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Elephant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElephantController extends Controller
{
    public function index()
    {
        $elephants = Elephant::orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('admin.elephants.index', compact('elephants'));
    }

    public function create()
    {
        return view('admin.elephants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rescued_at' => ['nullable', 'date'],
            'history' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['required', 'array', 'min:1', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6096'],
        ]);

        $imageUrls = $this->uploadImages($request->file('images', []));

        $slug = $this->makeSlug($data['name']);

        Elephant::create([
            'name' => $data['name'],
            'slug' => $slug,
            'rescued_at' => $data['rescued_at'] ?? null,
            'history' => $data['history'] ?? null,
            'images' => $imageUrls,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.elephants.index')
            ->with('success', 'บันทึกข้อมูลช้างเรียบร้อยแล้ว');
    }

    public function edit(Elephant $elephant)
    {
        return view('admin.elephants.edit', compact('elephant'));
    }

    public function update(Request $request, Elephant $elephant)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rescued_at' => ['nullable', 'date'],
            'history' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'min:1', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6096'],
        ]);

        $imageUrls = $elephant->images ?? [];
        if ($request->hasFile('images')) {
            $this->deleteImages($imageUrls);
            $imageUrls = $this->uploadImages($request->file('images', []));
        }

        $slug = $this->makeSlug($data['name'], $elephant->id);

        $elephant->update([
            'name' => $data['name'],
            'slug' => $slug,
            'rescued_at' => $data['rescued_at'] ?? null,
            'history' => $data['history'] ?? null,
            'images' => $imageUrls,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.elephants.index')
            ->with('success', 'อัปเดตข้อมูลช้างเรียบร้อยแล้ว');
    }

    public function destroy(Elephant $elephant)
    {
        $this->deleteImages($elephant->images ?? []);
        $elephant->delete();

        return back()->with('success', 'ลบข้อมูลช้างเรียบร้อยแล้ว');
    }

    public function toggle(Elephant $elephant)
    {
        $elephant->is_active = !$elephant->is_active;
        $elephant->save();

        return back();
    }

    private function uploadImages(array $files): array
    {
        $urls = [];
        $disk = Storage::disk('spaces');

        foreach ($files as $file) {
            $filename = 'elephant_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'elephant/elephants/' . $filename;
            $disk->put($path, file_get_contents($file), 'public');
            $urls[] = $disk->url($path);
        }

        return $urls;
    }

    private function deleteImages(array $urls): void
    {
        if (empty($urls)) {
            return;
        }

        $disk = Storage::disk('spaces');
        $rootUrl = rtrim($disk->url(''), '/');

        foreach ($urls as $url) {
            if (!$url) {
                continue;
            }
            $path = str_replace($rootUrl . '/', '', $url);
            $disk->delete($path);
        }
    }

    private function makeSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : 'elephant';
        $i = 1;

        while (Elephant::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
