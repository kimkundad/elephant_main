<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackingSetting;
use Illuminate\Http\Request;

class TrackingSettingController extends Controller
{
    public function edit()
    {
        return view('admin.tracking.edit', [
            'tracking' => TrackingSetting::first() ?? new TrackingSetting(),
            'canEditSnippets' => $this->canEditSnippets(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'ga4_measurement_id' => ['nullable', 'string', 'max:40', 'regex:/^G-[A-Z0-9]+$/i'],
            'gtm_container_id' => ['nullable', 'string', 'max:40', 'regex:/^GTM-[A-Z0-9]+$/i'],
            'meta_pixel_id' => ['nullable', 'string', 'max:40', 'regex:/^\d+$/'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:40', 'regex:/^[A-Z0-9]+$/i'],
            'google_ads_conversion_id' => ['nullable', 'string', 'max:40', 'regex:/^AW-[A-Z0-9]+$/i'],
            'google_ads_purchase_label' => ['nullable', 'string', 'max:60'],
            'facebook_domain_verification' => ['nullable', 'string', 'max:120'],
            'head_html' => ['nullable', 'string', 'max:20000'],
            'body_html' => ['nullable', 'string', 'max:20000'],
        ], [
            'ga4_measurement_id.regex' => 'GA4 Measurement ID ต้องขึ้นต้นด้วย G- เช่น G-94EZMK5EPY',
            'gtm_container_id.regex' => 'GTM Container ID ต้องขึ้นต้นด้วย GTM- เช่น GTM-ABC1234',
            'meta_pixel_id.regex' => 'Meta Pixel ID เป็นตัวเลขล้วน เช่น 1234567890123456',
            'google_ads_conversion_id.regex' => 'Google Ads ID ต้องขึ้นต้นด้วย AW- เช่น AW-123456789',
        ]);

        $data['track_purchase'] = $request->boolean('track_purchase');

        // Raw snippets run on every page, so only a superAdmin may change them.
        if (!$this->canEditSnippets()) {
            unset($data['head_html'], $data['body_html']);
        }

        $setting = TrackingSetting::first();
        TrackingSetting::updateOrCreate(['id' => $setting?->id], $data);
        TrackingSetting::forgetCurrent();

        return back()->with('success', 'บันทึก Tracking & Tags เรียบร้อยแล้ว');
    }

    private function canEditSnippets(): bool
    {
        return (bool) auth()->user()?->hasRole('superAdmin');
    }
}
