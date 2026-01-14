<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Str;
use App\Models\Contact;

class HomeController extends Controller
{
    //
   public function index()
    {
        // ดึงทัวร์ที่เปิดใช้งาน (is_active=1) และโชว์บนหน้าแรก 3 รายการ
        // เปลี่ยน take(3) เป็น take(6) ได้ ถ้าอยากโชว์มากขึ้น
        $tours = Tour::query()
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->take(3)
            ->get()
            ->map(function ($tour) {
                // สร้าง excerpt ให้สวย ถ้าไม่มี short_description ก็ใช้ description แทน
                $desc = $tour->short_description ?? $tour->description ?? '';
                $tour->excerpt = Str::limit(strip_tags($desc), 140);

                return $tour;
            });

        return view('frontend.pages.home', compact('tours'));
        // ถ้า home ของคุณอยู่ path อื่น เช่น frontend/home ก็แก้เป็น:
        // return view('frontend.home', compact('tours'));
    }

    public function about()
    {
        return view('frontend.pages.about');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Contact::create([
            ...$data,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string)$request->userAgent(), 0, 255),
            'submitted_at' => now(),
        ]);

        return redirect()->route('contact')->with('success', 'ขอบคุณค่ะ เราได้รับข้อความของคุณแล้ว และจะติดต่อกลับโดยเร็วที่สุด');
    }
}
