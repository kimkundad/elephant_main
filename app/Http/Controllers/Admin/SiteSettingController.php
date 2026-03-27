<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SpacesImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'address_th' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
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
            'logo_header' => ['nullable', 'image', 'max:25600'],
            'logo_footer' => ['nullable', 'image', 'max:25600'],
            'og_image' => ['nullable', 'image', 'max:25600'],
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

        $addressTh = trim((string) ($data['address_th'] ?? ''));
        $addressEn = trim((string) ($data['address_en'] ?? ''));
        if ($addressTh !== '' || $addressEn !== '') {
            $data['address'] = $addressTh !== '' ? $addressTh : $addressEn;
        }

        $setting = SiteSetting::first();

        if ($request->hasFile('logo_header')) {
            if ($setting?->logo_header_path) {
                Storage::disk('spaces')->delete($setting->logo_header_path);
            } elseif ($setting?->logo_path) {
                Storage::disk('spaces')->delete($setting->logo_path);
            }

            $data['logo_header_path'] = SpacesImageUploader::store(
                $request->file('logo_header'),
                'site',
                'logo_header_' . time()
            );
        }

        if ($request->hasFile('logo_footer')) {
            if ($setting?->logo_footer_path) {
                Storage::disk('spaces')->delete($setting->logo_footer_path);
            }

            $data['logo_footer_path'] = SpacesImageUploader::store(
                $request->file('logo_footer'),
                'site',
                'logo_footer_' . time()
            );
        }

        if ($request->hasFile('og_image')) {
            if ($setting?->og_image_path) {
                Storage::disk('spaces')->delete($setting->og_image_path);
            }

            $data['og_image_path'] = SpacesImageUploader::store(
                $request->file('og_image'),
                'site',
                'og_image_' . time()
            );
        }

        SiteSetting::updateOrCreate(['id' => $setting?->id], $data);

        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }
}
