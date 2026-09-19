<?php

namespace Database\Seeders;

use App\Models\PickupLocation;
use App\Models\Province;
use Illuminate\Database\Seeder;

/**
 * Loads the Phuket hotel list (database/data/phuket-pickup-locations.txt) into
 * pickup_locations. Safe to run again: names that already exist for the
 * province are skipped, matched case-insensitively.
 */
class PhuketPickupLocationSeeder extends Seeder
{
    private const DATA_FILE = 'database/data/phuket-pickup-locations.txt';

    public function run(): void
    {
        $province = Province::where('slug', 'phuket')->first();

        if (!$province) {
            $this->command?->error('ไม่พบจังหวัด slug "phuket" — สร้างจังหวัดก่อนแล้วค่อยรันใหม่');

            return;
        }

        $path = base_path(self::DATA_FILE);

        if (!is_file($path)) {
            $this->command?->error('ไม่พบไฟล์ข้อมูล ' . self::DATA_FILE);

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
            'เพิ่มจุดรับส่งภูเก็ต %d รายการ (ข้ามที่ซ้ำ %d รายการ)',
            count($rows),
            $skipped
        ));
    }
}
