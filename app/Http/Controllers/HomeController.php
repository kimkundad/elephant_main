<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\Elephant;
use App\Models\TourTag;

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
            ->take(5)
            ->get()
            ->map(function ($tour) {
                // สร้าง excerpt ให้สวย ถ้าไม่มี short_description ก็ใช้ description แทน
                $desc = $tour->short_description ?? $tour->description ?? '';
                $tour->excerpt = Str::limit(strip_tags($desc), 140);

                return $tour;
            });

        $elephants = Elephant::query()
            ->where('is_active', 1)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('frontend.pages.home', compact('tours', 'elephants'));
        // ถ้า home ของคุณอยู่ path อื่น เช่น frontend/home ก็แก้เป็น:
        // return view('frontend.home', compact('tours'));
    }

    public function about()
    {
        return view('frontend.pages.about');
    }

    public function aboutV2()
    {
        return view('frontend_v2.pages.about');
    }

    public function programV2(Request $request)
    {
        $searchTerm = trim((string) $request->query('q', ''));
        $selectedTags = collect($request->query('tags', []))
            ->filter(fn ($tag) => is_string($tag) && $tag !== '')
            ->values()
            ->all();

        $availableTags = TourTag::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $filterByTags = count($selectedTags) > 0;

        $tours = Tour::query()
            ->where('is_active', 1)
            ->with('tags')
            ->when(!$filterByTags && $searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($inner) use ($searchTerm) {
                    $inner->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
                            $tagQuery->where('name_th', 'like', '%' . $searchTerm . '%')
                                ->orWhere('name_en', 'like', '%' . $searchTerm . '%')
                                ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                        });
                });
            })
            ->when($filterByTags, function ($query) use ($selectedTags) {
                $query->whereHas('tags', function ($tagQuery) use ($selectedTags) {
                    $tagQuery->whereIn('slug', $selectedTags);
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('frontend_v2.pages.program', compact('tours', 'availableTags', 'selectedTags', 'searchTerm'));
    }

    public function home()
    {
        return view('welcome');
    }

    public function homeV2()
    {
        $tours = Tour::query()
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->get()
            ->map(function ($tour) {
                $desc = $tour->short_description ?? $tour->description ?? '';
                $tour->excerpt = Str::limit(strip_tags($desc), 140);

                return $tour;
            });

        $elephants = Elephant::query()
            ->where('is_active', 1)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $heroTags = TourTag::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('frontend_v2.pages.home', compact('tours', 'elephants', 'heroTags'));
    }


    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function contactV2()
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $question = "{$a} + {$b}";

        session([
            'contact_captcha_answer' => $a + $b,
            'contact_captcha_question' => $question,
        ]);

        return view('frontend_v2.pages.contact', [
            'captchaQuestion' => $question,
        ]);
    }

    public function contactStoreV2(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha_answer' => 'required|numeric',
        ]);

        $expected = (int) session('contact_captcha_answer', -1);
        if ((int) $data['captcha_answer'] !== $expected) {
            return back()
                ->withErrors(['captcha_answer' => 'คำตอบไม่ถูกต้อง'])
                ->withInput();
        }

        Contact::create([
            ...$data,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('frontend.contact.v2')
            ->with('success', 'ขอบคุณค่ะ เราได้รับข้อความของคุณแล้ว และจะติดต่อกลับโดยเร็วที่สุด');
    }

    public function elephants()
    {
        $elephants = Elephant::query()
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('frontend.pages.elephants', compact('elephants'));
    }

    public function elephantsV2()
    {
        $elephants = Elephant::query()
            ->where('is_active', 1)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('frontend_v2.pages.elephants', compact('elephants'));
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
