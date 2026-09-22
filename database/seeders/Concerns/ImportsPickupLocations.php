<?php

namespace Database\Seeders\Concerns;

use App\Models\PickupLocation;
use App\Models\Province;

/**
 * Loads a plain list of accommodation names (one per line) into
 * pickup_locations for one province. Safe to run again: names the province
 * already has are skipped, matched case-insensitively.
 */
trait ImportsPickupLocations
{
    protected function importPickupLocations(string $provinceSlug, string $dataFile): void
    {
        $province = Province::where('slug', $provinceSlug)->first();

        if (!$province) {
            $this->command?->error(sprintf('ไม่พบจังหวัด slug "%s" — สร้างจังหวัดก่อนแล้วค่อยรันใหม่', $provinceSlug));

            return;
        }

        $path = base_path($dataFile);

        if (!is_file($path)) {
            $this->command?->error('ไม่พบไฟล์ข้อมูล ' . $dataFile);

            return;
        }

        $existing = PickupLocation::where('province_id', $province->id)
            ->pluck('name')
            ->map(fn (string $name) => mb_strtolower(trim($name)))
            ->flip();

        $rows = [];
        $skipped = 0;
        $now = now();

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $name = trim($line);
            if ($name === '') {
                continue;
            }

            $key = mb_strtolower($name);
            if ($existing->has($key)) {
                $skipped++;
                continue;
            }

            $existing[$key] = true;
            $rows[] = [
                'province_id' => $province->id,
                'name' => $name,
                'is_active' => true,
                'is_meeting_point' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            PickupLocation::insert($chunk);
        }

        $this->command?->info(sprintf(
            'เพิ่มจุดรับส่ง %s %d รายการ (ข้ามที่ซ้ำ %d รายการ)',
            $province->name_th,
            count($rows),
            $skipped
        ));
    }
}
