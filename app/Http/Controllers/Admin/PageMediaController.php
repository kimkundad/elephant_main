<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PageMediaController extends Controller
{
    private const PRESET_KEYS = [
        'v2.header.menu.active_image',
        'v2.header.menu.hover_image',
        'v2.home.hero.video',
        'v2.home.hero.video_poster',
        'v2.home.welcome.image_1',
        'v2.home.welcome.image_2',
        'v2.home.reviews.background',
        'v2.about.hero.background',
        'v2.about.story.image_1_big',
        'v2.about.story.image_2',
        'v2.about.story.image_3',
        'v2.about.story.image_4_wide',
        'v2.about.experience.background',
        'v2.program.hero.background',
        'v2.contact.hero.background',
        'v2.policy.hero.background',
    ];

    public function index(Request $request)
    {
        $query = PageMedia::query();
        $keyword = trim((string) $request->query('q', ''));
        $locale = (string) $request->query('locale', 'all');
        $type = (string) $request->query('type', '');

        if ($keyword !== '') {
            $query->where(function ($sub) use ($keyword) {
                $sub->where('key', 'like', '%' . $keyword . '%')
                    ->orWhere('title', 'like', '%' . $keyword . '%')
                    ->orWhere('path', 'like', '%' . $keyword . '%');
            });
        }

        if (in_array($locale, ['th', 'en', ''], true)) {
            $query->where('locale', $locale);
        }

        if (in_array($type, ['image', 'video', 'file'], true)) {
            $query->where('type', $type);
        }

        $mediaItems = $query
            ->orderBy('key')
            ->orderBy('locale')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.page_media.index', [
            'mediaItems' => $mediaItems,
            'presetKeys' => self::PRESET_KEYS,
            'filters' => [
                'q' => $keyword,
                'locale' => $locale,
                'type' => $type,
            ],
        ]);
    }

    public function create()
    {
        return view('admin.page_media.create', [
            'media' => new PageMedia(['locale' => '', 'type' => 'image', 'is_active' => true]),
            'presetKeys' => self::PRESET_KEYS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInput($request);
        $data['path'] = $this->storeUploadedFile($request, $data['key']);
        $data['disk'] = 'spaces';
        $data['locale'] = $data['locale'] ?? '';

        PageMedia::create($data);

        return redirect()
            ->route('admin.page-media.index')
            ->with('success', 'Media created successfully.');
    }

    public function edit(PageMedia $pageMedia)
    {
        return view('admin.page_media.edit', [
            'media' => $pageMedia,
            'presetKeys' => self::PRESET_KEYS,
        ]);
    }

    public function update(Request $request, PageMedia $pageMedia)
    {
        $data = $this->validateInput($request, $pageMedia->id);
        $data['locale'] = $data['locale'] ?? '';

        if ($request->hasFile('media_file')) {
            $oldDisk = $pageMedia->disk ?: 'spaces';
            if ($pageMedia->path) {
                Storage::disk($oldDisk)->delete($pageMedia->path);
            }

            $data['path'] = $this->storeUploadedFile($request, $data['key']);
            $data['disk'] = 'spaces';
        }

        $pageMedia->update($data);

        return redirect()
            ->route('admin.page-media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(PageMedia $pageMedia)
    {
        $disk = $pageMedia->disk ?: 'spaces';
        if ($pageMedia->path) {
            Storage::disk($disk)->delete($pageMedia->path);
        }

        $pageMedia->delete();

        return redirect()
            ->route('admin.page-media.index')
            ->with('success', 'Media deleted successfully.');
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        $locale = (string) $request->input('locale', '');
        $type = (string) $request->input('type', 'image');
        $fileRules = ['file', 'max:51200'];

        if ($type === 'image') {
            $fileRules[] = 'mimetypes:image/jpeg,image/png,image/webp,image/gif,image/svg+xml';
        } elseif ($type === 'video') {
            $fileRules[] = 'mimetypes:video/mp4,video/webm,video/quicktime';
        }

        $rules = [
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('page_media', 'key')
                    ->where(fn ($query) => $query->where('locale', $locale))
                    ->ignore($ignoreId),
            ],
            'locale' => ['nullable', Rule::in(['th', 'en'])],
            'type' => ['required', Rule::in(['image', 'video', 'file'])],
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        if ($ignoreId === null) {
            $rules['media_file'] = array_merge(['required'], $fileRules);
        } else {
            $rules['media_file'] = array_merge(['nullable'], $fileRules);
        }

        $data = $request->validate($rules);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function storeUploadedFile(Request $request, string $key): string
    {
        $file = $request->file('media_file');
        if (!$file) {
            return '';
        }

        $safeKey = str_replace(['.', ' '], ['/', '-'], strtolower($key));
        $folder = 'page-media/' . trim($safeKey, '/');
        $path = $file->storePublicly($folder, 'spaces');

        return $path;
    }
}
