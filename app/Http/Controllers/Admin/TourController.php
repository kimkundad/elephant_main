<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    //
        public function index()
    {
        $tours = Tour::orderBy('id', 'desc')->get();
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
        ]);

        // Upload to DigitalOcean Spaces
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'tour_'.time().'.'.$file->getClientOriginalExtension();

            Storage::disk('spaces')->put(
                'elephant/tours/'.$filename,
                file_get_contents($file),
                'public'
            );

            $thumbnailPath = Storage::disk('spaces')->url('elephant/tours/'.$filename);
        }

        Tour::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'is_active' => $request->is_active ? 1 : 0,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('admin.tours.index')
                        ->with('success', 'สร้างโปรแกรมสำเร็จ');
    }

    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        return view('admin.tours.edit', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $disk = Storage::disk('spaces');
        $newThumbnailUrl = $tour->thumbnail; // ใช้รูปเดิมก่อน

        // ถ้ามีการอัปโหลดรูปใหม่
        if ($request->hasFile('thumbnail')) {

            // 1) ลบรูปเก่าใน DigitalOcean Spaces
            if (!empty($tour->thumbnail)) {
                // root URL เช่น: https://sgp1.digitaloceanspaces.com/bucket
                $rootUrl = rtrim($disk->url(''), '/');

                // แปลง URL → path
                $oldPath = str_replace($rootUrl . '/', '', $tour->thumbnail);

                // ลบไฟล์เก่า
                $disk->delete($oldPath);
            }

            // 2) อัปโหลดรูปใหม่
            $file = $request->file('thumbnail');
            $filename = 'tour_' . time() . '.' . $file->getClientOriginalExtension();

            $uploadPath = 'elephant/tours/' . $filename;

            $disk->put($uploadPath, file_get_contents($file), 'public');

            // URL ใหม่
            $newThumbnailUrl = $disk->url($uploadPath);
        }

        // 3) บันทึกข้อมูลใหม่ลงฐานข้อมูล
        $tour->update([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'is_active' => $request->is_active ?? 1,
            'thumbnail' => $newThumbnailUrl,
        ]);

        return redirect()->route('admin.tours.index')
                        ->with('success', 'อัปเดตโปรแกรมสำเร็จ');
    }



    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);

        if ($tour->thumbnail) {
            // URL ตัวอย่าง:
            // https://sgp1.digitaloceanspaces.com/yourbucket/elephant/tours/xxxx.jpg

            // ตัดส่วน URL ให้เหลือแค่ path ที่อยู่ใน bucket
            $disk = Storage::disk('spaces');
            $rootUrl = rtrim($disk->url(''), '/');
            // ผลแบบ: https://sgp1.digitaloceanspaces.com/yourbucket

            $path = str_replace($rootUrl.'/', '', $tour->thumbnail);

            // ตอนนี้ $path จะเหลือ เช่น:
            // elephant/tours/xxxx.jpg

            // ลบไฟล์
            $disk->delete($path);
        }

        $tour->delete();

        return back()->with('success', 'ลบข้อมูล และลบรูปออกจาก DigitalOcean สำเร็จ');
    }

    public function toggle($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->is_active = !$tour->is_active;
        $tour->save();

        return back();
    }
}
