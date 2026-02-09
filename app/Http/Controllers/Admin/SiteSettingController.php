<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $setting = SiteSetting::first() ?? new SiteSetting([
            'email' => 'infosmallelephants@gmail.com',
        ]);

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'footer_about' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'phone_secondary' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'hours' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'contact_office_hours' => ['nullable', 'string', 'max:255'],
            'contact_whatsapp_line' => ['nullable', 'string', 'max:255'],
            'map_embed_url' => ['nullable', 'string'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['facebook_url', 'instagram_url'] as $key) {
            $value = trim((string) ($data[$key] ?? ''));
            if ($value !== '') {
                if (!preg_match('~^https?://~i', $value)) {
                    $value = 'https://' . $value;
                }
                $data[$key] = $value;
            }
        }

        SiteSetting::updateOrCreate(['id' => SiteSetting::first()?->id], $data);

        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }
}
