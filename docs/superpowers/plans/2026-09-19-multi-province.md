# Multi-Province Support Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let the admin create provinces, put tours and pickup points in a province, and make the booking page offer only the pickup points of the tour's province (dropdown plus free-text note), replacing the Google Maps hotel search.

**Architecture:** A new `provinces` table, with `province_id` added to `tours` and `pickup_locations` and `pickup_note` added to `bookings`. `Tour::visible()` is the single rule for "shown and bookable on the frontend". `BookingPickup` is the single rule for "valid pickup for this tour". `Booking::pickupLabel()` and `pickupDetail()` are the single way to display a booking's pickup. Admin gets a Provinces CRUD, and the tour, pickup-location and booking forms get province-aware fields.

**Tech Stack:** Laravel 11 (PHP 8.2), Blade, MySQL in dev/prod, sqlite in-memory for tests, spatie/laravel-permission for admin roles.

**Spec:** `docs/superpowers/specs/2026-09-19-multi-province-design.md`

## Global Constraints

- PHP binary on this Windows dev machine: `/d/xampp/php/php.exe`. Run everything from `/d/xampp/htdocs/elephant-sanctuary`.
- Tests MUST run on sqlite in-memory. Never run a test using `RefreshDatabase` while `DB_CONNECTION` points at MySQL: it would wipe the dev DB. Task 1 makes sqlite the phpunit default.
- The local `.env` MySQL (DigitalOcean) is a **dev** DB. Only Task 11 runs `migrate` against it.
- Frontend = `resources/views/frontend_v2/**` only. The legacy `resources/views/frontend/**` has no routes and is out of scope. Do not edit it.
- Google Maps is removed from the **booking page only**. The admin pickup-location form keeps its map.
- Province names are stored as `name_th` / `name_en`. Display with `$province->name()`, which follows the current locale.
- Pickup note: `nullable|string|max:1000`.
- Every commit message ends with a blank line, then `Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>`.
- Commit on `main`. Do not push; the user pushes.

## File Map

| File | Responsibility |
|---|---|
| `phpunit.xml`, `database/migrations/2025_11_18_024338_update_tour_session_availability_table.php`, `tests/Feature/ExampleTest.php` | Make the test suite run on sqlite (T1) |
| `database/migrations/2026_09_19_000001_create_provinces_table.php` | Schema + Chiang Mai backfill (T2) |
| `app/Models/Province.php` (new), `app/Models/Tour.php`, `app/Models/PickupLocation.php` | Relations, `Tour::visible()`, `PickupLocation::availableIn()` (T2) |
| `tests/Concerns/CreatesTours.php` (new), `tests/TestCase.php` | Test fixtures + `actingAsAdmin()` (T2) |
| `app/Services/BookingPickup.php` (new), `app/Http/Controllers/BookingController.php` | Pickup rule + booking store (T3) |
| `resources/views/frontend_v2/pages/booking/create.blade.php`, `resources/lang/{th,en}/booking.php` | New pickup card, remove Google (T4) |
| `app/Models/Booking.php` + booking display views | `pickupLabel()` / `pickupDetail()` everywhere (T5) |
| `app/Http/Controllers/Admin/ProvinceController.php` (new), `resources/views/admin/provinces/**` (new), `routes/web.php`, sidebar | Provinces CRUD (T6) |
| Admin tour + pickup-location controllers/views | Province field, column, filter (T7) |
| Admin booking controller/views | Province-filtered pickup + note (T8) |
| `HomeController`, `TourController`, program/home/show views, `resources/lang/{th,en}/common.php` | Province label + `/programs` filter + hide inactive (T9) |
| `resources/views/admin/customers/index.blade.php`, `app/Models/Customer.php` | Remove nationality (T10) |

---

### Task 1: Run the test suite on sqlite

**Files:**
- Modify: `phpunit.xml` (the two commented `DB_*` lines)
- Modify: `database/migrations/2025_11_18_024338_update_tour_session_availability_table.php` (the `SHOW INDEX` block)
- Modify: `tests/Feature/ExampleTest.php`

**Interfaces:**
- Produces: `php artisan test` runs on sqlite `:memory:` with all migrations. Every later task relies on this.

This migration already ran on dev/prod, so editing it does not re-run it there. It only makes fresh sqlite databases work. `SHOW INDEX` is MySQL-only, and `Schema::hasIndex()` works on both MySQL and sqlite.

- [ ] **Step 1: Enable sqlite in phpunit.xml**

Replace:
```xml
        <!-- <env name="DB_CONNECTION" value="sqlite"/> -->
        <!-- <env name="DB_DATABASE" value=":memory:"/> -->
```
with:
```xml
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
```

- [ ] **Step 2: Make ExampleTest use a fresh DB and no Vite**

Replace the whole file `tests/Feature/ExampleTest.php` with:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

- [ ] **Step 3: Run it and confirm it fails on the MySQL-only migration**

Run: `/d/xampp/php/php.exe artisan test`
Expected: FAIL with `near "SHOW": syntax error` from `2025_11_18_024338_update_tour_session_availability_table`.

- [ ] **Step 4: Replace the SHOW INDEX check**

In `database/migrations/2025_11_18_024338_update_tour_session_availability_table.php`, replace:
```php
            // 4) index ป้องกันซ้ำ (เฉพาะถ้าไม่มีอยู่แล้ว)
            $exists = DB::select("
                SHOW INDEX FROM tour_session_availability
                WHERE Key_name = 'tour_session_unique'
            ");

            if (empty($exists)) {
```
with:
```php
            // 4) index ป้องกันซ้ำ (เฉพาะถ้าไม่มีอยู่แล้ว)
            // Schema::hasIndex works on MySQL and on the sqlite test DB.
            if (!Schema::hasIndex('tour_session_availability', 'tour_session_unique')) {
```

- [ ] **Step 5: Run the suite**

Run: `/d/xampp/php/php.exe artisan test`
Expected: PASS (ExampleTest and the Unit example).

- [ ] **Step 6: Commit**

```bash
git add phpunit.xml database/migrations/2025_11_18_024338_update_tour_session_availability_table.php tests/Feature/ExampleTest.php
git commit -m "Run the test suite on sqlite in-memory

The only MySQL-only migration used SHOW INDEX; Schema::hasIndex does the
same check on both drivers. Tests no longer touch the dev database.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 2: Provinces schema, models, and test fixtures

**Files:**
- Create: `database/migrations/2026_09_19_000001_create_provinces_table.php`
- Create: `app/Models/Province.php`
- Modify: `app/Models/Tour.php`, `app/Models/PickupLocation.php`, `app/Models/Booking.php` (`$fillable` only)
- Create: `tests/Concerns/CreatesTours.php`
- Modify: `tests/TestCase.php`
- Test: `tests/Feature/ProvinceModelTest.php`

**Interfaces:**
- Produces:
  - `App\Models\Province`: fillable `name_th, name_en, slug, is_active`; `tours()`, `pickupLocations()`, `scopeActive()`, `name(?string $locale = null): string`.
  - `Tour::province(): BelongsTo`, `Tour::scopeVisible(Builder): Builder`. Visible means `is_active = 1` and the province is active.
  - `PickupLocation::province(): BelongsTo`, `PickupLocation::scopeAvailableIn(Builder, int $provinceId): Builder`. Available means `is_active` and in that province.
  - `Booking` fillable gains `pickup_note`.
  - Test trait `Tests\Concerns\CreatesTours`: `makeProvince(string $slug, array $attrs = []): Province`, `makeTour(Province $p, array $attrs = []): Tour`, `makeSession(Tour $t): TourSession`, `makePickup(Province $p, array $attrs = []): PickupLocation`, `chiangMai(): Province`.
  - `Tests\TestCase::actingAsAdmin(): static`.
  - Every fresh test DB already contains the province `chiang-mai` (active), created by the migration.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ProvinceModelTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\PickupLocation;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceModelTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_migration_creates_chiang_mai(): void
    {
        $this->assertSame('Chiang Mai', $this->chiangMai()->name_en);
        $this->assertTrue($this->chiangMai()->is_active);
    }

    public function test_name_follows_locale(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_th' => 'ภูเก็ต', 'name_en' => 'Phuket']);

        app()->setLocale('th');
        $this->assertSame('ภูเก็ต', $phuket->name());

        app()->setLocale('en');
        $this->assertSame('Phuket', $phuket->name());
    }

    public function test_visible_hides_inactive_tours_and_tours_in_inactive_provinces(): void
    {
        $open = $this->makeProvince('phuket');
        $closed = $this->makeProvince('krabi', ['is_active' => false]);

        $shown = $this->makeTour($open);
        $this->makeTour($open, ['is_active' => false]);
        $this->makeTour($closed);

        $this->assertSame([$shown->id], Tour::visible()->pluck('id')->all());
    }

    public function test_available_in_returns_active_pickups_of_that_province_only(): void
    {
        $phuket = $this->makeProvince('phuket');
        $krabi = $this->makeProvince('krabi');

        $ok = $this->makePickup($phuket);
        $this->makePickup($phuket, ['is_active' => false]);
        $this->makePickup($krabi);

        $this->assertSame([$ok->id], PickupLocation::availableIn($phuket->id)->pluck('id')->all());
    }
}
```

- [ ] **Step 2: Create the test fixtures trait**

Create `tests/Concerns/CreatesTours.php`:
```php
<?php

namespace Tests\Concerns;

use App\Models\PickupLocation;
use App\Models\Province;
use App\Models\Tour;
use App\Models\TourSession;
use Illuminate\Support\Str;

trait CreatesTours
{
    /** The province the migration creates in every fresh database. */
    protected function chiangMai(): Province
    {
        return Province::where('slug', 'chiang-mai')->firstOrFail();
    }

    protected function makeProvince(string $slug, array $attrs = []): Province
    {
        return Province::create(array_merge([
            'name_th' => $slug . ' (th)',
            'name_en' => Str::headline($slug),
            'slug' => $slug,
            'is_active' => true,
        ], $attrs));
    }

    protected function makeTour(Province $province, array $attrs = []): Tour
    {
        return Tour::create(array_merge([
            'province_id' => $province->id,
            'name' => 'Tour ' . Str::random(6),
            'slug' => 'tour-' . Str::lower(Str::random(10)),
            'min_price' => 1000,
            'max_price' => 1000,
            'is_active' => true,
        ], $attrs));
    }

    protected function makeSession(Tour $tour): TourSession
    {
        return TourSession::create([
            'tour_id' => $tour->id,
            'title' => 'Morning Program',
            'start_time' => '09:30:00',
            'end_time' => '12:00:00',
            'default_capacity' => 20,
            'is_active' => 1,
        ]);
    }

    protected function makePickup(Province $province, array $attrs = []): PickupLocation
    {
        return PickupLocation::create(array_merge([
            'province_id' => $province->id,
            'name' => 'Pickup ' . Str::random(6),
            'is_active' => true,
            'is_meeting_point' => false,
        ], $attrs));
    }
}
```

- [ ] **Step 3: Add `actingAsAdmin()` to the base TestCase**

Replace `tests/TestCase.php` with:
```php
<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    /** Log in as a user with the `admin` role, which the /admin routes require. */
    protected function actingAsAdmin(): static
    {
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        return $this->actingAs($user);
    }
}
```
(If the existing `tests/TestCase.php` has more than the empty abstract class, keep its contents and only add the method and the two `use` lines.)

- [ ] **Step 4: Run the test to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=ProvinceModelTest`
Expected: FAIL with `Class "App\Models\Province" not found`.

- [ ] **Step 5: Write the migration**

Create `database/migrations/2026_09_19_000001_create_provinces_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name_th');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Nullable so existing rows can be backfilled below; the admin forms require it.
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('id')
                ->constrained('provinces')->restrictOnDelete();
        });

        Schema::table('pickup_locations', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('id')
                ->constrained('provinces')->restrictOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->text('pickup_note')->nullable()->after('pickup_place_address');
        });

        // Everything that exists today is in Chiang Mai.
        $chiangMaiId = DB::table('provinces')->insertGetId([
            'name_th' => 'เชียงใหม่',
            'name_en' => 'Chiang Mai',
            'slug' => 'chiang-mai',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tours')->whereNull('province_id')->update(['province_id' => $chiangMaiId]);
        DB::table('pickup_locations')->whereNull('province_id')->update(['province_id' => $chiangMaiId]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('pickup_note');
        });

        Schema::table('pickup_locations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('province_id');
        });

        Schema::table('tours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('province_id');
        });

        Schema::dropIfExists('provinces');
    }
};
```

- [ ] **Step 6: Create the Province model**

Create `app/Models/Province.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name_th',
        'name_en',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function pickupLocations()
    {
        return $this->hasMany(PickupLocation::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Province name in the given (or current) locale, falling back to Thai. */
    public function name(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'en' && $this->name_en) {
            return $this->name_en;
        }

        return $this->name_th ?: (string) $this->name_en;
    }
}
```

- [ ] **Step 7: Wire Tour to Province**

In `app/Models/Tour.php`:
- Add `use Illuminate\Database\Eloquent\Builder;` under `use Illuminate\Database\Eloquent\Model;`.
- Add `'province_id',` as the first entry of `$fillable`.
- Add these methods after `availabilities()`:
```php
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /** Tours the public site may list and book: active, in an active province. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', 1)
            ->whereHas('province', fn (Builder $province) => $province->where('is_active', true));
    }
```

- [ ] **Step 8: Wire PickupLocation to Province**

Replace `app/Models/PickupLocation.php` with:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
    protected $fillable = [
        'province_id',
        'name',
        'latitude',
        'longitude',
        'is_active',
        'is_meeting_point',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_meeting_point' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /** Pickup points a guest may choose for a tour in this province. */
    public function scopeAvailableIn(Builder $query, int $provinceId): Builder
    {
        return $query->where('is_active', true)->where('province_id', $provinceId);
    }
}
```

- [ ] **Step 9: Allow `pickup_note` on Booking**

In `app/Models/Booking.php` `$fillable`, change `'pickup_place_address','created_by',` to `'pickup_place_address','pickup_note','created_by',`.

- [ ] **Step 10: Run the tests**

Run: `/d/xampp/php/php.exe artisan test`
Expected: PASS (all 4 ProvinceModelTest tests plus the existing tests).

- [ ] **Step 11: Commit**

```bash
git add database/migrations/2026_09_19_000001_create_provinces_table.php app/Models/Province.php app/Models/Tour.php app/Models/PickupLocation.php app/Models/Booking.php tests/Concerns/CreatesTours.php tests/TestCase.php tests/Feature/ProvinceModelTest.php
git commit -m "Add provinces and link tours and pickup points to them

Existing tours and pickup points are backfilled to Chiang Mai. Adds
bookings.pickup_note for the guest's free-text pickup details.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 3: Pickup rule and booking store

**Files:**
- Create: `app/Services/BookingPickup.php`
- Modify: `app/Http/Controllers/BookingController.php` (`store()`: validation, pickup block, transaction `use` list, `Booking::create` pickup keys; delete `isWithinChiangMaiBounds()`)
- Modify: `resources/lang/en/booking.php`, `resources/lang/th/booking.php` (`errors.pickup_required`, new `errors.pickup_invalid`)
- Test: `tests/Unit/BookingPickupTest.php`, `tests/Feature/BookingStorePickupTest.php`

**Interfaces:**
- Consumes: `Tour::visible()`, `PickupLocation::availableIn()`, fixtures from Task 2.
- Produces: `App\Services\BookingPickup::resolve(Tour $tour, bool $selfDrive, ?int $pickupLocationId, ?string $note): array`. It returns `['pickup_location_id' => ?int, 'self_drive' => bool, 'pickup_source' => 'self_drive'|'list', 'pickup_note' => ?string]`, or throws `Illuminate\Validation\ValidationException` keyed on `pickup_location_id`. The store request fields are now `self_drive`, `pickup_location_id`, `pickup_note`.

The unit test uses the Laravel `Tests\TestCase` because it needs the DB. Successful stores are not feature-tested, because `store()` calls Stripe after saving. `BookingPickup` covers that logic without Stripe.

- [ ] **Step 1: Write the failing unit test**

Create `tests/Unit/BookingPickupTest.php`:
```php
<?php

namespace Tests\Unit;

use App\Services\BookingPickup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingPickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_self_drive_needs_no_pickup_and_drops_the_note(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));

        $this->assertSame([
            'pickup_location_id' => null,
            'self_drive' => true,
            'pickup_source' => 'self_drive',
            'pickup_note' => null,
        ], (new BookingPickup())->resolve($tour, true, null, 'ignored'));
    }

    public function test_pickup_in_the_tours_province_is_accepted_with_trimmed_note(): void
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $pickup = $this->makePickup($phuket);

        $this->assertSame([
            'pickup_location_id' => $pickup->id,
            'self_drive' => false,
            'pickup_source' => 'list',
            'pickup_note' => 'Villa 12, Bangtao',
        ], (new BookingPickup())->resolve($tour, false, $pickup->id, "  Villa 12, Bangtao  "));
    }

    public function test_blank_note_is_stored_as_null(): void
    {
        $phuket = $this->makeProvince('phuket');
        $pickup = $this->makePickup($phuket);

        $result = (new BookingPickup())->resolve($this->makeTour($phuket), false, $pickup->id, '   ');

        $this->assertNull($result['pickup_note']);
    }

    public function test_missing_pickup_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        (new BookingPickup())->resolve($this->makeTour($this->makeProvince('phuket')), false, null, null);
    }

    public function test_pickup_from_another_province_is_rejected(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));
        $other = $this->makePickup($this->makeProvince('krabi'));

        try {
            (new BookingPickup())->resolve($tour, false, $other->id, null);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('pickup_location_id', $e->errors());
        }
    }

    public function test_inactive_pickup_is_rejected(): void
    {
        $phuket = $this->makeProvince('phuket');
        $inactive = $this->makePickup($phuket, ['is_active' => false]);

        $this->expectException(ValidationException::class);

        (new BookingPickup())->resolve($this->makeTour($phuket), false, $inactive->id, null);
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingPickupTest`
Expected: FAIL with `Class "App\Services\BookingPickup" not found`.

- [ ] **Step 3: Implement BookingPickup**

Create `app/Services/BookingPickup.php`:
```php
<?php

namespace App\Services;

use App\Models\PickupLocation;
use App\Models\Tour;
use Illuminate\Validation\ValidationException;

class BookingPickup
{
    /**
     * Work out the pickup fields for a new booking. A guest either travels by
     * themselves or picks an active pickup point in the tour's province; the
     * free-text note is kept only alongside a pickup point.
     *
     * @return array{pickup_location_id: ?int, self_drive: bool, pickup_source: string, pickup_note: ?string}
     *
     * @throws ValidationException
     */
    public function resolve(Tour $tour, bool $selfDrive, ?int $pickupLocationId, ?string $note): array
    {
        if ($selfDrive) {
            return [
                'pickup_location_id' => null,
                'self_drive' => true,
                'pickup_source' => 'self_drive',
                'pickup_note' => null,
            ];
        }

        if (!$pickupLocationId) {
            throw ValidationException::withMessages([
                'pickup_location_id' => __('booking.errors.pickup_required'),
            ]);
        }

        $available = PickupLocation::availableIn((int) $tour->province_id)
            ->whereKey($pickupLocationId)
            ->exists();

        if (!$available) {
            throw ValidationException::withMessages([
                'pickup_location_id' => __('booking.errors.pickup_invalid'),
            ]);
        }

        $note = trim((string) $note);

        return [
            'pickup_location_id' => $pickupLocationId,
            'self_drive' => false,
            'pickup_source' => 'list',
            'pickup_note' => $note !== '' ? $note : null,
        ];
    }
}
```

- [ ] **Step 4: Update the error messages**

In `resources/lang/en/booking.php` (the `'errors' => [` block), replace the `'pickup_required' => ...` line with:
```php
        'pickup_required' => 'Please select a pickup point.',
        'pickup_invalid' => 'The selected pickup point is not available for this tour. Please choose again.',
```
In `resources/lang/th/booking.php` (the `'errors' => [` block), replace the `'pickup_required' => ...` line with:
```php
        'pickup_required' => 'กรุณาเลือกจุดรับส่ง',
        'pickup_invalid' => 'จุดรับส่งที่เลือกไม่มีในจังหวัดของทัวร์นี้ กรุณาเลือกใหม่',
```

- [ ] **Step 5: Run the unit test**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingPickupTest`
Expected: PASS (6 tests).

- [ ] **Step 6: Write the failing feature test for store**

Create `tests/Feature/BookingStorePickupTest.php`:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingStorePickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    private function payload(array $overrides = []): array
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $session = $this->makeSession($tour);

        return array_merge([
            'booking_v2' => 1,
            'tour_id' => $tour->id,
            'session_id' => $session->id,
            'date' => now()->addDays(5)->toDateString(),
            'qty_adult' => 1,
            'qty_child' => 0,
            'qty_infant' => 0,
            'full_name' => 'Test Guest',
            'phone' => '0812345678',
            'email' => 'guest@example.com',
            'payment_channel' => 'card',
        ], $overrides);
    }

    public function test_pickup_from_another_province_is_rejected_before_saving(): void
    {
        $otherPickup = $this->makePickup($this->makeProvince('krabi'));

        $this->post(route('frontend.booking.store'), $this->payload([
            'pickup_location_id' => $otherPickup->id,
        ]))->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_missing_pickup_without_self_drive_is_rejected(): void
    {
        $this->post(route('frontend.booking.store'), $this->payload())
            ->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_tour_in_inactive_province_cannot_be_booked(): void
    {
        $closed = $this->makeProvince('krabi', ['is_active' => false]);
        $tour = $this->makeTour($closed);

        $this->post(route('frontend.booking.store'), $this->payload([
            'tour_id' => $tour->id,
            'session_id' => $this->makeSession($tour)->id,
            'self_drive' => 1,
        ]))->assertNotFound();
    }

    public function test_note_longer_than_1000_characters_is_rejected(): void
    {
        $this->post(route('frontend.booking.store'), $this->payload([
            'self_drive' => 1,
            'pickup_note' => str_repeat('a', 1001),
        ]))->assertSessionHasErrors('pickup_note');
    }
}
```

- [ ] **Step 7: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingStorePickupTest`
Expected: FAIL. The current store has no `pickup_location_id` field, and the inactive-province tour is still found.

- [ ] **Step 8: Rewire `BookingController::store()`**

In `app/Http/Controllers/BookingController.php`:

1. Add `use App\Services\BookingPickup;` next to the other `App\Services` imports.
2. Delete the whole `private function isWithinChiangMaiBounds(...)` method.
3. In `store()`'s `$request->validate([...])`, replace the block from `'self_drive' => 'nullable|boolean',` through `'meeting_point_id' => 'nullable|integer|exists:pickup_locations,id',` with:
```php
            'self_drive' => 'nullable|boolean',
            'pickup_location_id' => 'nullable|integer',
            'pickup_note' => 'nullable|string|max:1000',
```
4. Replace this block:
```php
        $isV2 = $request->boolean('booking_v2');
        $tour = Tour::with('translations')->findOrFail($data['tour_id']);
```
with:
```php
        $isV2 = $request->boolean('booking_v2');
        $tour = Tour::visible()->with('translations')->findOrFail($data['tour_id']);
```
5. Replace everything from `$selfDrive = $request->boolean('self_drive');` through the closing `}` of the `if ($selfDrive) { ... } elseif ... else { ... }` pickup block (the line just before `$priceAdult = ...`) with:
```php
        $pickup = (new BookingPickup())->resolve(
            $tour,
            $request->boolean('self_drive'),
            isset($data['pickup_location_id']) ? (int) $data['pickup_location_id'] : null,
            $data['pickup_note'] ?? null
        );
```
6. In the `DB::transaction(function () use (...)` list, replace
```php
                $pickupLocationId,
                $selfDrive,
                $pickupSource,
                $pickupPlaceName,
                $pickupPlaceAddress
```
with:
```php
                $pickup
```
7. In `Booking::create([...])`, replace
```php
                    'pickup_location_id' => $pickupLocationId,
                    'self_drive' => $selfDrive,
                    'pickup_source' => $pickupSource,
                    'pickup_place_name' => $pickupPlaceName,
                    'pickup_place_address' => $pickupPlaceAddress,
```
with:
```php
                    'pickup_location_id' => $pickup['pickup_location_id'],
                    'self_drive' => $pickup['self_drive'],
                    'pickup_source' => $pickup['pickup_source'],
                    'pickup_note' => $pickup['pickup_note'],
```
8. Run `grep -n 'selfDrive\|pickupPlace\|pickupSource\|pickupLocationId\|meeting_point\|google_' app/Http/Controllers/BookingController.php`. Expected: no matches inside `store()`. If one remains, it is a leftover from the removed block: replace it with the matching `$pickup[...]` key, or delete it.

- [ ] **Step 9: Run the tests**

Run: `/d/xampp/php/php.exe artisan test`
Expected: PASS. All BookingStorePickupTest and BookingPickupTest tests pass, and the earlier tests still pass.

- [ ] **Step 10: Commit**

```bash
git add app/Services/BookingPickup.php app/Http/Controllers/BookingController.php resources/lang/en/booking.php resources/lang/th/booking.php tests/Unit/BookingPickupTest.php tests/Feature/BookingStorePickupTest.php
git commit -m "Book with a pickup point from the tour's province

Replace the Google Places / Chiang Mai bounding-box pickup rules with
BookingPickup: a guest travels by themselves or picks an active pickup
point in the tour's province, plus an optional note. Tours in an inactive
province can no longer be booked.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 4: Booking page pickup card, remove Google Maps

**Files:**
- Modify: `app/Http/Controllers/BookingController.php` (`createV2()` only)
- Modify: `resources/views/frontend_v2/pages/booking/create.blade.php`
- Modify: `resources/lang/en/booking.php`, `resources/lang/th/booking.php` (`create` block)
- Test: `tests/Feature/BookingPageTest.php`

**Interfaces:**
- Consumes: `Tour::visible()`, `PickupLocation::availableIn()`, `Province::name()`, and the store field names from Task 3 (`self_drive`, `pickup_location_id`, `pickup_note`).
- Produces: the view variable `$pickupLocations` (replaces `$meetingPoints`), and `BOOKING_I18N.errors.selectPickup`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/BookingPageTest.php`:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class BookingPageTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function bookingUrl($tour): string
    {
        return route('frontend.booking.create.v2', [
            'tour' => $tour->id,
            'session' => $this->makeSession($tour)->id,
            'date' => now()->addDays(5)->toDateString(),
        ]);
    }

    public function test_dropdown_lists_only_active_pickups_of_the_tours_province(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_en' => 'Phuket']);
        $tour = $this->makeTour($phuket);
        $this->makePickup($phuket, ['name' => 'Bangtao Zone']);
        $this->makePickup($phuket, ['name' => 'Patong Meeting Point', 'is_meeting_point' => true]);
        $this->makePickup($phuket, ['name' => 'Closed Hotel', 'is_active' => false]);
        $this->makePickup($this->makeProvince('krabi'), ['name' => 'Ao Nang Hotel']);

        $this->get($this->bookingUrl($tour))
            ->assertOk()
            ->assertSee('name="pickup_location_id"', false)
            ->assertSee('name="pickup_note"', false)
            ->assertSee('Bangtao Zone')
            ->assertSee('Patong Meeting Point')
            ->assertDontSee('Closed Hotel')
            ->assertDontSee('Ao Nang Hotel');
    }

    public function test_page_no_longer_loads_google_maps(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));

        $this->get($this->bookingUrl($tour))
            ->assertOk()
            ->assertDontSee('maps.googleapis.com', false)
            ->assertDontSee('initHotelAutocomplete', false);
    }

    public function test_tour_in_inactive_province_returns_404(): void
    {
        $tour = $this->makeTour($this->makeProvince('krabi', ['is_active' => false]));

        $this->get($this->bookingUrl($tour))->assertNotFound();
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingPageTest`
Expected: FAIL. The page still renders `searchInput`/Google, and `pickup_location_id` is not in the page.

- [ ] **Step 3: Update `createV2()`**

In `app/Http/Controllers/BookingController.php`, inside `createV2()` only:
- Replace `$tour = Tour::with('translations')->findOrFail($tourId);` with:
```php
        $tour = Tour::visible()->with(['translations', 'province'])->findOrFail($tourId);
```
- Replace the `$meetingPoints = PickupLocation::query() ... ->get();` statement with:
```php
        $pickupLocations = PickupLocation::availableIn((int) $tour->province_id)
            ->orderBy('name')
            ->get();
```
- In the `compact(...)` call of `createV2()`, replace `'meetingPoints',` with `'pickupLocations',`.

Leave the legacy `create()` method untouched. No route uses it.

- [ ] **Step 4: Update the `create` language keys**

In `resources/lang/en/booking.php`, inside `'create' => [`:
- Replace the `'hotel_pickup' => ...` line with:
```php
        'hotel_pickup' => 'Hotel Pick up & Drop Off (If your hotel is not on the list, select your zone, e.g. "Bangtao" and enter your hotel name manually)',
        'pickup_select_placeholder' => 'Please select where you are staying',
        'pickup_group_hotels' => 'Hotels / Zones',
        'pickup_group_meeting' => 'Meeting points',
        'pickup_note_label' => 'If your hotel is not listed, please provide address below:',
```
- Delete these keys: `search_hotel_label`, `search_hotel_placeholder`, `search_hotel_help`, `pickup_found`, `manual_address_label`, `manual_address_placeholder`, `manual_address_help`, `manual_latlng`, `meeting_label`, `meeting_map_help`, `meeting_placeholder`.
- In `'errors' => [`, delete: `meeting_point_required`, `select_hotel`, `select_manual_address`, `pin_location`, `select_meeting_point`, `out_of_bounds`.

In `resources/lang/th/booking.php`, make the same changes. The replacement for the `'hotel_pickup' => ...` line is:
```php
        'hotel_pickup' => 'จุดรับส่งที่พัก (ถ้าไม่มีโรงแรมของคุณในรายการ ให้เลือกโซน เช่น "บางเทา" แล้วกรอกชื่อโรงแรมในช่องด้านล่าง)',
        'pickup_select_placeholder' => 'กรุณาเลือกที่พักของคุณ',
        'pickup_group_hotels' => 'โรงแรม / โซน',
        'pickup_group_meeting' => 'จุดนัดพบ',
        'pickup_note_label' => 'หากไม่มีโรงแรมของคุณในรายการ กรุณากรอกที่อยู่ด้านล่าง:',
```
(Delete a key only if it exists in that file.)

- [ ] **Step 5: Replace the "Additional information" card**

In `resources/views/frontend_v2/pages/booking/create.blade.php`, replace the whole card that starts with
```blade
        <div class="card">
          <div class="card-title">{{ __('booking.create.additional_info') }}</div>
```
and ends with the `</div>` just before `<div class="card">` + `{{ __('booking.create.contact_details') }}`, with:
```blade
        <div class="card">
          <div class="card-title">{{ __('booking.create.additional_info') }}</div>
          <label class="checkbox self-drive-check">
            <input type="checkbox" name="self_drive" id="self_drive" value="1" @checked(old('self_drive'))>
            <span>{{ __('booking.create.self_drive') }}</span>
          </label>

          <div id="pickupFields">
            <label class="f-label" for="pickup_location_id">
              {{ __('booking.create.hotel_pickup') }} <span class="req">*</span>
            </label>
            @php
              $pickupGroups = [
                __('booking.create.pickup_group_hotels') => $pickupLocations->where('is_meeting_point', false),
                __('booking.create.pickup_group_meeting') => $pickupLocations->where('is_meeting_point', true),
              ];
            @endphp
            <select name="pickup_location_id" id="pickup_location_id" class="f-input @error('pickup_location_id') is-invalid @enderror" required>
              <option value="">{{ __('booking.create.pickup_select_placeholder') }}</option>
              @foreach($pickupGroups as $groupLabel => $groupItems)
                @if($groupItems->isNotEmpty())
                  <optgroup label="{{ $groupLabel }}">
                    @foreach($groupItems as $pickup)
                      <option value="{{ $pickup->id }}" @selected((string) old('pickup_location_id') === (string) $pickup->id)>{{ $pickup->name }}</option>
                    @endforeach
                  </optgroup>
                @endif
              @endforeach
            </select>
            @error('pickup_location_id')<span class="field-error">{{ $message }}</span>@enderror

            <label class="f-label" for="pickup_note">{{ __('booking.create.pickup_note_label') }}</label>
            <textarea name="pickup_note" id="pickup_note" class="f-input pickup-note" rows="3" maxlength="1000">{{ old('pickup_note') }}</textarea>
            @error('pickup_note')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>
```

- [ ] **Step 6: Add styles for the asterisk and textarea**

In the same file's `@push('styles')` `<style>` block, directly after the `.f-input.is-invalid{ ... }` rule, add:
```css
.req{ color:#e2572b; font-weight:700; }
textarea.f-input.pickup-note{ resize:vertical; min-height:84px; font-family:inherit; }
```

- [ ] **Step 7: Update the i18n block and submit validation**

In the `@php` block at the top of the same file, inside `'errors' => [`, delete the lines for `'selectHotel'`, `'selectManualAddress'`, `'pinLocation'`, `'selectMeetingPoint'` and `'outOfBounds'`, and add:
```php
          'selectPickup' => __('booking.errors.pickup_required'),
```

In the form `submit` handler, replace everything from `const searchInput = document.getElementById('searchInput');` through the closing `}` of the `if (!selfDrive?.checked && ((meetingWrap ...` block with:
```js
    const selfDrive = document.getElementById('self_drive');
    const pickupSelect = document.getElementById('pickup_location_id');
    const fullName = form.querySelector('input[name="full_name"]');
    const phone = form.querySelector('input[name="phone"]');
    const email = form.querySelector('input[name="email"]');
    const discountCode = document.getElementById('discount_code');

    if (!selfDrive?.checked && (!pickupSelect || !pickupSelect.value)) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.selectPickup);
      pickupSelect?.focus();
      return;
    }
```
(The `fullName` / `phone` / `email` / `discountCode` declarations were inside the replaced range. They are re-declared here, and the checks below them stay unchanged.)

- [ ] **Step 8: Replace the Google script with a self-drive toggle**

Delete the whole `<script>` block that starts with `window.initHotelAutocomplete = function () {`, and delete the following
```blade
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initHotelAutocomplete"
  async defer></script>
```
In their place (just before `@endsection`), add:
```blade
<script>
(function () {
  const selfDrive = document.getElementById('self_drive');
  const pickupFields = document.getElementById('pickupFields');
  const pickupSelect = document.getElementById('pickup_location_id');
  if (!selfDrive || !pickupFields || !pickupSelect) return;

  // Travelling by themselves means no pickup point is needed.
  const syncPickupMode = () => {
    pickupFields.style.display = selfDrive.checked ? 'none' : '';
    pickupSelect.required = !selfDrive.checked;
  };

  selfDrive.addEventListener('change', syncPickupMode);
  syncPickupMode();
})();
</script>
```

- [ ] **Step 9: Show the province in the booking hero**

In the same file, replace:
```blade
          @ {{ $session->time_range }}
```
with:
```blade
          @ {{ $session->time_range }}
          @if($tour->province) &middot; {{ $tour->province->name() }} @endif
```

- [ ] **Step 10: Run the tests and grep for leftovers**

Run: `/d/xampp/php/php.exe artisan test`
Expected: PASS. BookingPageTest has 3 tests.

Run: `grep -n "searchInput\|meeting_point_id\|google_\|meetingPoints\|initHotelAutocomplete\|pickup_out_of_bounds" resources/views/frontend_v2/pages/booking/create.blade.php`
Expected: no output.

- [ ] **Step 11: Check the page in a browser**

Open `http://localhost:8083/v2/booking?tour=<id>&date=<future date>&session=<id>` (a local tour and session). Check:
- the dropdown groups options;
- ticking "I will travel by myself" hides the dropdown and the textarea, and unticking shows them again;
- submitting with nothing selected shows the pickup alert;
- there are no console errors.

Record what you saw in the task report.

- [ ] **Step 12: Commit**

```bash
git add app/Http/Controllers/BookingController.php resources/views/frontend_v2/pages/booking/create.blade.php resources/lang/en/booking.php resources/lang/th/booking.php tests/Feature/BookingPageTest.php
git commit -m "Replace Google hotel search with a province pickup dropdown

The booking page now lists the active pickup points of the tour's
province (hotels/zones, then meeting points) plus a textarea for guests
whose hotel is not listed. Google Maps is no longer loaded on this page.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 5: One way to display a booking's pickup

**Files:**
- Modify: `app/Models/Booking.php`
- Modify: `resources/lang/en/booking.php`, `resources/lang/th/booking.php` (`confirmed` block)
- Modify: `resources/views/frontend_v2/pages/booking/confirmed.blade.php`, `resources/views/public/booking.blade.php`, `resources/views/emails/booking-confirmed.blade.php`
- Modify: `resources/views/admin/bookings/show.blade.php`, `resources/views/admin/bookings/pdf.blade.php`, `app/Http/Controllers/Admin/BookingController.php` (`export()` only)
- Test: `tests/Unit/BookingPickupDisplayTest.php`

**Interfaces:**
- Consumes: `bookings.pickup_note` (Task 2), `Province::name()`.
- Produces: `Booking::pickupLabel(): string` and `Booking::pickupDetail(): ?string`. Label is the self-drive text, the pickup point name, the legacy `pickup_place_name`, or `-`. Detail is `pickup_note`, the legacy `pickup_place_address`, or `null`; it is always `null` for self drive.

- [ ] **Step 1: Write the failing test**

Create `tests/Unit/BookingPickupDisplayTest.php`:
```php
<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\PickupLocation;
use Tests\TestCase;

class BookingPickupDisplayTest extends TestCase
{
    public function test_self_drive(): void
    {
        app()->setLocale('en');
        $booking = new Booking(['self_drive' => true, 'pickup_note' => 'ignored']);

        $this->assertSame(__('booking.confirmed.self_drive'), $booking->pickupLabel());
        $this->assertNull($booking->pickupDetail());
    }

    public function test_pickup_point_with_note(): void
    {
        $booking = new Booking(['self_drive' => false, 'pickup_note' => 'Villa 12']);
        $booking->setRelation('pickupLocation', new PickupLocation(['name' => 'Bangtao Zone']));

        $this->assertSame('Bangtao Zone', $booking->pickupLabel());
        $this->assertSame('Villa 12', $booking->pickupDetail());
    }

    public function test_legacy_google_booking_falls_back_to_place_fields(): void
    {
        $booking = new Booking([
            'self_drive' => false,
            'pickup_place_name' => 'Old Hotel',
            'pickup_place_address' => '1 Old Road',
        ]);
        $booking->setRelation('pickupLocation', null);

        $this->assertSame('Old Hotel', $booking->pickupLabel());
        $this->assertSame('1 Old Road', $booking->pickupDetail());
    }

    public function test_nothing_set(): void
    {
        $booking = new Booking(['self_drive' => false]);
        $booking->setRelation('pickupLocation', null);

        $this->assertSame('-', $booking->pickupLabel());
        $this->assertNull($booking->pickupDetail());
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingPickupDisplayTest`
Expected: FAIL with `Call to undefined method App\Models\Booking::pickupLabel()`.

- [ ] **Step 3: Implement the helpers**

In `app/Models/Booking.php`, add after `pickupLocation()`:
```php
    /** How the guest gets to the tour: self drive, the chosen pickup point, or an old free-text hotel. */
    public function pickupLabel(): string
    {
        if ($this->self_drive) {
            return __('booking.confirmed.self_drive');
        }

        return $this->pickupLocation?->name ?: ($this->pickup_place_name ?: '-');
    }

    /** Extra pickup details: the guest's note, or the address saved by the old Google flow. */
    public function pickupDetail(): ?string
    {
        if ($this->self_drive) {
            return null;
        }

        return $this->pickup_note ?: ($this->pickup_place_address ?: null);
    }
```

- [ ] **Step 4: Language keys**

In `resources/lang/en/booking.php` `'confirmed' => [`, replace `'pickup_address' => 'Pickup address',` with `'pickup_address' => 'Pickup details',` and add `'province' => 'Province',`. Keep the existing `'self_drive' => 'Traveling by myself',`.

In `resources/lang/th/booking.php` `'confirmed' => [`, after `'pickup_location' => 'จุดรับ',` add:
```php
        'pickup_address' => 'รายละเอียดจุดรับส่ง',
        'self_drive' => 'เดินทางไปเอง',
        'province' => 'จังหวัด',
```

- [ ] **Step 5: Run the unit test**

Run: `/d/xampp/php/php.exe artisan test --filter=BookingPickupDisplayTest`
Expected: PASS (4 tests).

- [ ] **Step 6: Use the helpers in the confirmed page**

In `resources/views/frontend_v2/pages/booking/confirmed.blade.php`:
- In the top `@php` block, delete everything from `$pickupLabel = '-';` through `$pickupAddressLabel = app()->getLocale() === 'th' ? 'ที่อยู่รับส่ง' : 'Pickup address';`.
- Replace:
```blade
            <div class="label">{{ __('booking.confirmed.pickup_location') }}</div>
            <div class="value">{{ $pickupLabel }}</div>

            @if(!$booking->self_drive && $booking->pickup_place_address)
              <div class="label">{{ $pickupAddressLabel }}</div>
              <div class="value">{{ $booking->pickup_place_address }}</div>
            @endif
```
with:
```blade
            <div class="label">{{ __('booking.confirmed.pickup_location') }}</div>
            <div class="value">{{ $booking->pickupLabel() }}</div>

            @if($booking->pickupDetail())
              <div class="label">{{ __('booking.confirmed.pickup_address') }}</div>
              <div class="value" style="white-space:pre-line;">{{ $booking->pickupDetail() }}</div>
            @endif
```
- After the `{{ __('booking.confirmed.tour') }}` label/value pair, add:
```blade
            @if($booking->tour?->province)
              <div class="label">{{ __('booking.confirmed.province') }}</div>
              <div class="value">{{ $booking->tour->province->name() }}</div>
            @endif
```

- [ ] **Step 7: Public QR page and email**

In `resources/views/public/booking.blade.php`, after the `Package` row (`<div class="label">Package</div>` and its value), add:
```blade
        <div class="row">
          <div class="label">Province</div>
          <div class="value">{{ $booking->tour?->province?->name('en') ?? '-' }}</div>
        </div>
        <div class="row">
          <div class="label">Pickup</div>
          <div class="value" style="white-space:pre-line;">{{ $booking->pickupLabel() }}@if($booking->pickupDetail())
{{ $booking->pickupDetail() }}@endif</div>
        </div>
```

In `resources/views/emails/booking-confirmed.blade.php`, after the `DATE & TIME` column `<div>...</div>` (inside the same flex container), add:
```blade
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">PICKUP</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">{{ $booking->pickupLabel() }}</div>
            @if($booking->pickupDetail())
              <div style="font-size:13px;color:#425466;white-space:pre-line;">{{ $booking->pickupDetail() }}</div>
            @endif
          </div>
```
and change the `PROGRAM` value line to:
```blade
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">{{ $booking->tour?->name ?? '-' }}@if($booking->tour?->province) ({{ $booking->tour->province->name('en') }})@endif</div>
```

- [ ] **Step 8: Admin show, PDF, export**

In `resources/views/admin/bookings/show.blade.php`, replace the block from `<strong>Pickup:</strong>` through the `@endif` that closes `@if($booking->pickup_place_address)` with:
```blade
                <strong>Pickup:</strong> {{ $booking->pickupLabel() }} <br>

                @if($booking->pickupDetail())
                    <strong>Pickup details:</strong> <span style="white-space:pre-line;">{{ $booking->pickupDetail() }}</span> <br>
                @endif
```

In `resources/views/admin/bookings/pdf.blade.php`, replace the whole `@php ... @endphp` under `{{-- PICKUP --}}` with:
```blade
    @php
        $pickupLabel = $booking->pickupLabel();
        if ($booking->pickupDetail()) {
            $pickupLabel .= "\n" . $booking->pickupDetail();
        }
    @endphp
```

In `app/Http/Controllers/Admin/BookingController.php` `export()`:
- In `$columns`, replace `'จุดรับส่ง', 'ประเภทการรับส่ง',` with `'จังหวัด', 'จุดรับส่ง', 'รายละเอียดรับส่ง', 'ประเภทการรับส่ง',`.
- Change the eager load to `Booking::with(['customer', 'tour.province', 'session', 'agent', 'discountCode', 'pickupLocation'])`.
- Replace the whole `if ($b->self_drive) { ... } else { ... }` block that sets `$pickupLabel` / `$pickupType` with:
```php
                if ($b->self_drive) {
                    $pickupType = 'Self Drive';
                } elseif ($b->pickupLocation) {
                    $pickupType = $b->pickupLocation->is_meeting_point ? 'Meeting Point' : 'Hotel / Zone';
                } elseif ($b->pickup_place_name) {
                    $pickupType = 'Hotel / Address';
                } else {
                    $pickupType = '-';
                }
```
- In `fputcsv($file, [...])`, replace `$pickupLabel,` with:
```php
                    $b->tour?->province?->name_th ?? '-',
                    $b->pickupLabel(),
                    $b->pickupDetail() ?? '-',
```
  Keep `$pickupType,` right after it.

- [ ] **Step 9: Compile all views and run the suite**

Run: `/d/xampp/php/php.exe artisan view:cache && /d/xampp/php/php.exe artisan view:clear && /d/xampp/php/php.exe artisan test`
Expected: `Blade templates cached successfully`, then all tests PASS.

- [ ] **Step 10: Commit**

```bash
git add app/Models/Booking.php resources/lang/en/booking.php resources/lang/th/booking.php resources/views/frontend_v2/pages/booking/confirmed.blade.php resources/views/public/booking.blade.php resources/views/emails/booking-confirmed.blade.php resources/views/admin/bookings/show.blade.php resources/views/admin/bookings/pdf.blade.php app/Http/Controllers/Admin/BookingController.php tests/Unit/BookingPickupDisplayTest.php
git commit -m "Show pickup point, note and province wherever a booking is shown

Booking::pickupLabel()/pickupDetail() replace five copies of the pickup
display logic and fall back to the old Google fields for past bookings.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 6: Admin Provinces CRUD

**Files:**
- Create: `app/Http/Controllers/Admin/ProvinceController.php`
- Create: `resources/views/admin/provinces/index.blade.php`, `create.blade.php`, `edit.blade.php`, `partials/form.blade.php`
- Modify: `routes/web.php` (admin group), `resources/views/partials/admin/inc-slidebar.blade.php`
- Test: `tests/Feature/Admin/ProvinceAdminTest.php`

**Interfaces:**
- Consumes: `Province` (Task 2), `actingAsAdmin()`.
- Produces: routes `admin.provinces.index|create|store|edit|update|destroy`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Admin/ProvinceAdminTest.php`:
```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceAdminTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_admin_creates_a_province_with_slug_from_english_name(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.provinces.store'), [
                'name_th' => 'ภูเก็ต',
                'name_en' => 'Phuket',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertDatabaseHas('provinces', ['slug' => 'phuket', 'name_th' => 'ภูเก็ต', 'is_active' => true]);
    }

    public function test_slug_must_be_unique(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.provinces.store'), [
                'name_th' => 'เชียงใหม่ 2',
                'name_en' => 'Chiang Mai',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_unticked_status_saves_inactive(): void
    {
        $province = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->put(route('admin.provinces.update', $province), [
                'name_th' => 'ภูเก็ต',
                'name_en' => 'Phuket',
                'slug' => 'phuket',
            ])
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertFalse($province->fresh()->is_active);
    }

    public function test_province_with_tours_cannot_be_deleted(): void
    {
        $province = $this->makeProvince('phuket');
        $this->makeTour($province);

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertSessionHasErrors('province');

        $this->assertModelExists($province);
    }

    public function test_province_with_pickups_cannot_be_deleted(): void
    {
        $province = $this->makeProvince('phuket');
        $this->makePickup($province);

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertSessionHasErrors('province');

        $this->assertModelExists($province);
    }

    public function test_empty_province_is_deleted(): void
    {
        $province = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->delete(route('admin.provinces.destroy', $province))
            ->assertRedirect(route('admin.provinces.index'));

        $this->assertModelMissing($province);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.provinces.index'))->assertRedirect();
        $this->assertSame(1, Province::count());
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=ProvinceAdminTest`
Expected: FAIL with `Route [admin.provinces.store] not defined`.

- [ ] **Step 3: Controller**

Create `app/Http/Controllers/Admin/ProvinceController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::query()
            ->withCount(['tours', 'pickupLocations'])
            ->orderBy('name_th')
            ->paginate(20);

        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(Request $request)
    {
        Province::create($this->validated($request));

        return redirect()->route('admin.provinces.index')
            ->with('success', 'เพิ่มจังหวัดเรียบร้อย');
    }

    public function edit(Province $province)
    {
        return view('admin.provinces.edit', compact('province'));
    }

    public function update(Request $request, Province $province)
    {
        $province->update($this->validated($request, $province));

        return redirect()->route('admin.provinces.index')
            ->with('success', 'บันทึกจังหวัดเรียบร้อย');
    }

    public function destroy(Province $province)
    {
        if ($province->tours()->exists() || $province->pickupLocations()->exists()) {
            return back()->withErrors([
                'province' => 'ลบไม่ได้: ยังมีทัวร์หรือจุดรับส่งอยู่ในจังหวัดนี้ ย้ายออกก่อน หรือปิดการใช้งานแทน',
            ]);
        }

        $province->delete();

        return redirect()->route('admin.provinces.index')
            ->with('success', 'ลบจังหวัดเรียบร้อย');
    }

    /** Validate the form; the slug defaults to the English name and must stay unique. */
    private function validated(Request $request, ?Province $province = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name_en')),
        ]);

        $data = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('provinces', 'slug')->ignore($province?->id),
            ],
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
```

- [ ] **Step 4: Route**

In `routes/web.php`, inside the admin group, directly above
```php
        Route::resource('pickup-locations', App\Http\Controllers\Admin\PickupLocationController::class);
```
add:
```php
        Route::resource('provinces', App\Http\Controllers\Admin\ProvinceController::class)->except(['show']);
```

- [ ] **Step 5: Views**

Create `resources/views/admin/provinces/partials/form.blade.php`:
```blade
<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label">ชื่อจังหวัด (TH) *</label>
    <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th', $province->name_th ?? '') }}" required>
    @error('name_th')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">ชื่อจังหวัด (EN) *</label>
    <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en', $province->name_en ?? '') }}" required>
    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug (ใช้ใน URL)</label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $province->slug ?? '') }}" placeholder="เว้นว่าง = สร้างจากชื่อ EN">
    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label d-block">สถานะ</label>
    <div class="form-check form-switch mt-3">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="province_is_active" {{ old('is_active', $province->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="province_is_active">เปิดใช้งาน (ปิดแล้วทัวร์ในจังหวัดนี้จะไม่แสดงหน้าบ้าน)</label>
    </div>
  </div>
</div>
```

Create `resources/views/admin/provinces/create.blade.php`:
```blade
@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">เพิ่มจังหวัด</h1>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.provinces.store') }}" method="POST">
              @csrf
              @include('admin.provinces.partials.form', ['province' => null])
              <div class="text-end mt-6">
                <a href="{{ route('admin.provinces.index') }}" class="btn btn-light me-2">ยกเลิก</a>
                <button type="submit" class="btn btn-primary">บันทึก</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
```

Create `resources/views/admin/provinces/edit.blade.php`: the same file as `create.blade.php`, with these differences:
- The heading text is `แก้ไขจังหวัด`.
- The form tag is `<form action="{{ route('admin.provinces.update', $province) }}" method="POST">`.
- Put `@method('PUT')` on the line after `@csrf`.
- The include is `@include('admin.provinces.partials.form', ['province' => $province])`.

Create `resources/views/admin/provinces/index.blade.php`:
```blade
@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">จังหวัด</h1>
        <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">+ เพิ่มจังหวัด</a>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
              <thead>
                <tr class="fw-bold text-muted">
                  <th>ชื่อ (TH)</th>
                  <th>ชื่อ (EN)</th>
                  <th>Slug</th>
                  <th>ทัวร์</th>
                  <th>จุดรับส่ง</th>
                  <th>สถานะ</th>
                  <th class="text-end">จัดการ</th>
                </tr>
              </thead>
              <tbody>
                @forelse($provinces as $province)
                  <tr>
                    <td>{{ $province->name_th }}</td>
                    <td>{{ $province->name_en }}</td>
                    <td><code>{{ $province->slug }}</code></td>
                    <td><span class="badge badge-light-primary">{{ $province->tours_count }}</span></td>
                    <td><span class="badge badge-light-primary">{{ $province->pickup_locations_count }}</span></td>
                    <td>
                      @if($province->is_active)
                        <span class="badge badge-light-success">เปิด</span>
                      @else
                        <span class="badge badge-light-secondary">ปิด</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="{{ route('admin.provinces.edit', $province) }}" class="btn btn-sm btn-light-primary">แก้ไข</a>
                      <form action="{{ route('admin.provinces.destroy', $province) }}" method="POST" class="d-inline" onsubmit="return confirm('ลบจังหวัดนี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger">ลบ</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center text-muted">ยังไม่มีจังหวัด</td></tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">{{ $provinces->links('pagination::bootstrap-5') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
```

- [ ] **Step 6: Sidebar entry**

In `resources/views/partials/admin/inc-slidebar.blade.php`, directly above the `<div class="menu-item">` whose link is `{{ route('admin.pickup-locations.index') }}`, add:
```blade
                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.provinces.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Provinces / จังหวัด</span>
                    </a>
                </div>
```

- [ ] **Step 7: Run the tests and compile views**

Run: `/d/xampp/php/php.exe artisan test && /d/xampp/php/php.exe artisan view:cache && /d/xampp/php/php.exe artisan view:clear`
Expected: all tests PASS (ProvinceAdminTest has 7), and the Blade templates are cached successfully.

- [ ] **Step 8: Commit**

```bash
git add app/Http/Controllers/Admin/ProvinceController.php resources/views/admin/provinces routes/web.php resources/views/partials/admin/inc-slidebar.blade.php tests/Feature/Admin/ProvinceAdminTest.php
git commit -m "Add admin CRUD for provinces

A province that still has tours or pickup points cannot be deleted; the
admin is told to move them or deactivate the province instead.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 7: Province field on admin tours and pickup locations

**Files:**
- Modify: `app/Http/Controllers/Admin/TourController.php` (`index`, `create`, `store`, `edit`, `update`)
- Modify: `resources/views/admin/tours/index.blade.php`, `create.blade.php`, `edit.blade.php`
- Modify: `app/Http/Controllers/Admin/PickupLocationController.php` (`index`, `create`, `store`, `edit`, `update`)
- Modify: `resources/views/admin/pickup_locations/index.blade.php`, `create.blade.php`, `edit.blade.php`
- Test: `tests/Feature/Admin/ProvinceFieldsAdminTest.php`

**Interfaces:**
- Consumes: `Province`, `Tour::province()`, `PickupLocation::province()`, `actingAsAdmin()`.
- Produces: the required request field `province_id` on tour and pickup-location forms, and the list filter `?province_id=`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Admin/ProvinceFieldsAdminTest.php`:
```php
<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProvinceFieldsAdminTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_pickup_location_requires_province(): void
    {
        $this->actingAsAdmin()
            ->post(route('admin.pickup-locations.store'), ['name' => 'Bangtao Zone', 'is_active' => 1])
            ->assertSessionHasErrors('province_id');
    }

    public function test_pickup_location_saves_province(): void
    {
        $phuket = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->post(route('admin.pickup-locations.store'), [
                'name' => 'Bangtao Zone',
                'province_id' => $phuket->id,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.pickup-locations.index'));

        $this->assertDatabaseHas('pickup_locations', ['name' => 'Bangtao Zone', 'province_id' => $phuket->id]);
    }

    public function test_pickup_location_update_changes_province(): void
    {
        $pickup = $this->makePickup($this->chiangMai(), ['name' => 'Old Town']);
        $phuket = $this->makeProvince('phuket');

        $this->actingAsAdmin()
            ->put(route('admin.pickup-locations.update', $pickup), [
                'name' => 'Old Town',
                'province_id' => $phuket->id,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.pickup-locations.index'));

        $this->assertSame($phuket->id, $pickup->fresh()->province_id);
    }

    public function test_tour_update_requires_province(): void
    {
        $tour = $this->makeTour($this->chiangMai());

        $this->actingAsAdmin()
            ->put(route('admin.tours.update', $tour->id), [
                'name_th' => 'ทัวร์',
                'name_en' => 'Tour',
                'min_price' => 1000,
                'max_price' => 1000,
            ])
            ->assertSessionHasErrors('province_id');
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=ProvinceFieldsAdminTest`
Expected: FAIL, because `province_id` is not validated yet.

- [ ] **Step 3: Admin TourController**

In `app/Http/Controllers/Admin/TourController.php`:
- Add `use App\Models\Province;`.
- Replace `index()` with:
```php
    public function index(Request $request)
    {
        $provinces = Province::orderBy('name_th')->get();

        $tours = Tour::with('province')
            ->when($request->query('province_id'), fn ($query, $provinceId) => $query->where('province_id', $provinceId))
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.tours.index', compact('tours', 'provinces'));
    }
```
- In `create()`, change `return view('admin.tours.create', compact('tags'));` to:
```php
        $provinces = Province::orderBy('name_th')->get();

        return view('admin.tours.create', compact('tags', 'provinces'));
```
- In `edit()`, add `$provinces = Province::orderBy('name_th')->get();` before the `return`, and add `'provinces'` to its `compact(...)`.
- In both `store()` and `update()` validation arrays, add as the first rule:
```php
            'province_id' => 'required|integer|exists:provinces,id',
```
- In both the `Tour::create([...])` and `$tour->update([...])` arrays, add as the first entry:
```php
            'province_id' => $data['province_id'],
```

- [ ] **Step 4: Tour views**

In `resources/views/admin/tours/create.blade.php`, directly above the line containing `<label class="form-label">Display Status</label>`, add:
```blade
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด *</label>
                                    <select name="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                        <option value="">-- เลือกจังหวัด --</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" @selected((string) old('province_id') === (string) $province->id)>{{ $province->name_th }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
```
In `resources/views/admin/tours/edit.blade.php`, add the same block directly above the line containing `<label class="form-label">Display Status</label>`, with the `@selected` using `old('province_id', $tour->province_id)` instead of `old('province_id')`.

In `resources/views/admin/tours/index.blade.php`:
- After `<th>ชื่อโปรแกรม</th>` add `<th>จังหวัด</th>`.
- After `<td>{{ $tour->name }}</td>` add `<td>{{ $tour->province?->name_th ?? '-' }}</td>`.
- Directly above `<table class="table table-bordered">` add:
```blade
                            <form method="GET" class="mb-5" style="max-width:320px;">
                                <select name="province_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">ทุกจังหวัด</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" @selected((string) request('province_id') === (string) $province->id)>{{ $province->name_th }}</option>
                                    @endforeach
                                </select>
                            </form>
```

- [ ] **Step 5: Admin PickupLocationController**

In `app/Http/Controllers/Admin/PickupLocationController.php`:
- Add `use App\Models\Province;`.
- Replace `index()` with:
```php
    public function index(Request $request)
    {
        $provinces = Province::orderBy('name_th')->get();

        $locations = PickupLocation::with('province')
            ->when($request->query('province_id'), fn ($query, $provinceId) => $query->where('province_id', $provinceId))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pickup_locations.index', compact('locations', 'provinces'));
    }
```
- Replace `create()` body with:
```php
        $provinces = Province::orderBy('name_th')->get();

        return view('admin.pickup_locations.create', compact('provinces'));
```
- Replace `edit()` body with:
```php
        $provinces = Province::orderBy('name_th')->get();

        return view('admin.pickup_locations.edit', compact('pickup_location', 'provinces'));
```
- In both `store()` and `update()` validation arrays, add as the first rule:
```php
        'province_id' => 'required|integer|exists:provinces,id',
```
  (`PickupLocation::create($data)` and `->update($data)` already pass `$data` through, and `province_id` is fillable since Task 2.)

- [ ] **Step 6: Pickup-location views**

In `resources/views/admin/pickup_locations/create.blade.php`, directly after the `<div class="mb-3">` block that holds `name="name"`, add:
```blade
                        <div class="mb-3">
                            <label class="form-label">จังหวัด *</label>
                            <select name="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                <option value="">-- เลือกจังหวัด --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" @selected((string) old('province_id') === (string) $province->id)>{{ $province->name_th }}</option>
                                @endforeach
                            </select>
                            @error('province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
```
In `resources/views/admin/pickup_locations/edit.blade.php`, add the same block after the `<div class="mb-3">` that holds `name="name"`, with `@selected((string) old('province_id', $pickup_location->province_id) === (string) $province->id)`.

In `resources/views/admin/pickup_locations/index.blade.php`:
- After `<th>ชื่อโรงแรม / จุดรับ</th>` add `<th>จังหวัด</th>`.
- After `<td>{{ $loc->name }}</td>` add `<td>{{ $loc->province?->name_th ?? '-' }}</td>`.
- Directly above `<table class="table table-bordered">` add the same filter `<form method="GET" ...>` block as in the tour index (Step 4).

- [ ] **Step 7: Run the tests and compile views**

Run: `/d/xampp/php/php.exe artisan test && /d/xampp/php/php.exe artisan view:cache && /d/xampp/php/php.exe artisan view:clear`
Expected: all tests PASS (ProvinceFieldsAdminTest has 4), and the Blade templates are cached successfully.

- [ ] **Step 8: Commit**

```bash
git add app/Http/Controllers/Admin/TourController.php resources/views/admin/tours app/Http/Controllers/Admin/PickupLocationController.php resources/views/admin/pickup_locations tests/Feature/Admin/ProvinceFieldsAdminTest.php
git commit -m "Require a province on admin tours and pickup locations

Both lists gain a province column and a province filter.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 8: Admin bookings: province-filtered pickup and note

**Files:**
- Modify: `app/Http/Controllers/Admin/BookingController.php` (`store()`, `update()`, plus `use App\Models\PickupLocation;` if missing)
- Modify: `resources/views/admin/bookings/create.blade.php`, `resources/views/admin/bookings/edit.blade.php`
- Test: `tests/Feature/Admin/AdminBookingPickupTest.php`

**Interfaces:**
- Consumes: `PickupLocation::availableIn()`, `actingAsAdmin()`, fixtures.
- Produces: the admin booking request fields `pickup_location_id` (optional, must be in the tour's province) and `pickup_note`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Admin/AdminBookingPickupTest.php`:
```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class AdminBookingPickupTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    public function test_admin_cannot_use_a_pickup_from_another_province(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket'));
        $session = $this->makeSession($tour);
        $otherPickup = $this->makePickup($this->makeProvince('krabi'));
        $customer = Customer::create(['full_name' => 'Guest', 'email' => 'g@example.com', 'phone' => '0800000000']);

        $this->actingAsAdmin()
            ->post(route('admin.bookings.store'), [
                'customer_id' => $customer->id,
                'tour_id' => $tour->id,
                'session_id' => $session->id,
                'date' => now()->addDays(3)->toDateString(),
                'adults' => 1,
                'pickup_location_id' => $otherPickup->id,
            ])
            ->assertSessionHasErrors('pickup_location_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_admin_booking_saves_pickup_and_note(): void
    {
        $phuket = $this->makeProvince('phuket');
        $tour = $this->makeTour($phuket);
        $session = $this->makeSession($tour);
        $pickup = $this->makePickup($phuket);
        $customer = Customer::create(['full_name' => 'Guest', 'email' => 'g@example.com', 'phone' => '0800000000']);

        $this->actingAsAdmin()
            ->post(route('admin.bookings.store'), [
                'customer_id' => $customer->id,
                'tour_id' => $tour->id,
                'session_id' => $session->id,
                'date' => now()->addDays(3)->toDateString(),
                'adults' => 1,
                'pickup_location_id' => $pickup->id,
                'pickup_note' => 'Villa 12',
            ])
            ->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'tour_id' => $tour->id,
            'pickup_location_id' => $pickup->id,
            'pickup_note' => 'Villa 12',
        ]);
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=AdminBookingPickupTest`
Expected: FAIL. The first test gets a redirect with no `pickup_location_id` error. The second test gets no `pickup_note` saved.

- [ ] **Step 3: Controller**

In `app/Http/Controllers/Admin/BookingController.php`, make sure `use App\Models\PickupLocation;` is present (it is already imported at the top; verify).

In `store()`:
- Add `'pickup_note' => 'nullable|string|max:1000',` to the `$request->validate([...])` array, after the `pickup_location_id` rule.
- Directly after `$tour    = Tour::findOrFail($request->tour_id);`, add:
```php
    if ($request->filled('pickup_location_id')
        && !PickupLocation::availableIn((int) $tour->province_id)->whereKey($request->pickup_location_id)->exists()) {
        return back()
            ->withErrors(['pickup_location_id' => 'จุดรับส่งนี้ไม่อยู่ในจังหวัดของทัวร์ที่เลือก'])
            ->withInput();
    }
```
- In `Booking::create([...])`, after `'pickup_location_id' => $request->pickup_location_id,`, add `'pickup_note' => $request->pickup_note,`.

In `update()`, make the same three changes. The province check goes directly after `$tour    = Tour::findOrFail($request->tour_id);`, and `'pickup_note' => $request->pickup_note,` goes after `'pickup_location_id' => $request->pickup_location_id,` in `$booking->update([...])`.

- [ ] **Step 4: Views: tag options with their province, filter by tour, add the note**

In `resources/views/admin/bookings/create.blade.php`:
- In the tour `<select id="tourSelect" ...>`, change `<option value="{{ $t->id }}">{{ $t->name }}</option>` to:
```blade
                                    <option value="{{ $t->id }}" data-province="{{ $t->province_id }}">{{ $t->name }}</option>
```
- Replace the whole `{{-- PICKUP --}}` `<div class="mb-3">...</div>` with:
```blade
                        {{-- PICKUP --}}
                        <div class="mb-3">
                            <label class="form-label">สถานที่รับลูกค้า (เฉพาะจังหวัดของทัวร์)</label>
                            <select name="pickup_location_id" id="pickupSelect" class="form-control">
                                <option value="">-- เลือกสถานที่รับ --</option>
                                @foreach($pickupLocations as $p)
                                    <option value="{{ $p->id }}" data-province="{{ $p->province_id }}" @selected((string) old('pickup_location_id') === (string) $p->id)>
                                        {{ $p->name }}{{ $p->is_meeting_point ? ' (Meeting Point)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pickup_location_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รายละเอียดจุดรับส่งเพิ่มเติม</label>
                            <textarea name="pickup_note" class="form-control" rows="2" maxlength="1000">{{ old('pickup_note') }}</textarea>
                        </div>
```
- Directly before the final `@endsection`, add:
```blade
<script>
// Only offer pickup points in the selected tour's province.
(function () {
    const tourSelect = document.getElementById('tourSelect');
    const pickupSelect = document.getElementById('pickupSelect');
    if (!tourSelect || !pickupSelect) return;

    const syncPickupOptions = () => {
        const provinceId = tourSelect.selectedOptions[0]?.dataset.province || '';
        Array.from(pickupSelect.options).forEach((option) => {
            if (!option.value) return;
            const inProvince = option.dataset.province === provinceId;
            option.hidden = !inProvince;
            option.disabled = !inProvince;
        });
        if (pickupSelect.selectedOptions[0]?.disabled) pickupSelect.value = '';
    };

    tourSelect.addEventListener('change', syncPickupOptions);
    syncPickupOptions();
})();
</script>
```

In `resources/views/admin/bookings/edit.blade.php`, make the same three changes, with these differences:
- The tour option keeps its `{{ $booking->tour_id == $t->id ? 'selected' : '' }}` and gains `data-province="{{ $t->province_id }}"`.
- In the pickup option, use `@selected((string) old('pickup_location_id', $booking->pickup_location_id) === (string) $p->id)`.
- The textarea content is `{{ old('pickup_note', $booking->pickup_note) }}`.

- [ ] **Step 5: Run the tests and compile views**

Run: `/d/xampp/php/php.exe artisan test && /d/xampp/php/php.exe artisan view:cache && /d/xampp/php/php.exe artisan view:clear`
Expected: all tests PASS (AdminBookingPickupTest has 2), and the Blade templates are cached successfully.

If `test_admin_booking_saves_pickup_and_note` fails on something unrelated to pickup (for example, capacity or price calculation in `store()`), read the error. Fix only the test data, such as adding `'children' => 0, 'infants' => 0`. Do not change pricing code.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Admin/BookingController.php resources/views/admin/bookings/create.blade.php resources/views/admin/bookings/edit.blade.php tests/Feature/Admin/AdminBookingPickupTest.php
git commit -m "Limit admin booking pickup points to the tour's province

Adds the pickup note to admin create/edit and rejects a pickup point
from another province on the server as well as in the form.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 9: Province on the public site and the /programs filter

**Files:**
- Modify: `app/Http/Controllers/HomeController.php` (`programV2`, `homeV2`)
- Modify: `app/Http/Controllers/TourController.php` (`showV2` only)
- Modify: `resources/views/frontend_v2/pages/program.blade.php`, `resources/views/frontend_v2/pages/home.blade.php`, `resources/views/frontend_v2/pages/tours/show.blade.php`
- Modify: `resources/lang/en/common.php`, `resources/lang/th/common.php`
- Test: `tests/Feature/ProgramProvinceFilterTest.php`

**Interfaces:**
- Consumes: `Tour::visible()`, `Province::active()`, `Province::name()`.
- Produces: the `/programs?province={slug}` filter, and the view variables `$provinces` and `$selectedProvince` for `program.blade.php`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ProgramProvinceFilterTest.php`:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTours;
use Tests\TestCase;

class ProgramProvinceFilterTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        // SetLocale middleware reads the locale from the session on every request.
        $this->withSession(['locale' => 'en']);
    }

    public function test_province_param_filters_tours(): void
    {
        $phuket = $this->makeProvince('phuket', ['name_en' => 'Phuket']);
        $this->makeTour($phuket, ['name' => 'Phuket Sanctuary Walk']);
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        $this->get('/programs?province=phuket')
            ->assertOk()
            ->assertSee('Phuket Sanctuary Walk')
            ->assertDontSee('Chiang Mai Feeding');
    }

    public function test_unknown_province_shows_everything(): void
    {
        $this->makeTour($this->makeProvince('phuket'), ['name' => 'Phuket Sanctuary Walk']);
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        $this->get('/programs?province=atlantis')
            ->assertOk()
            ->assertSee('Phuket Sanctuary Walk')
            ->assertSee('Chiang Mai Feeding');
    }

    public function test_chips_hidden_with_one_active_province(): void
    {
        $this->makeTour($this->chiangMai(), ['name' => 'Chiang Mai Feeding']);

        // Match the element, not the CSS rule of the same name.
        $this->get('/programs')->assertOk()->assertDontSee('class="program-provinces"', false);
    }

    public function test_chips_shown_with_two_active_provinces(): void
    {
        $this->makeProvince('phuket', ['name_en' => 'Phuket']);

        $this->get('/programs')->assertOk()->assertSee('class="program-provinces"', false)->assertSee('Phuket');
    }

    public function test_tours_in_inactive_province_are_hidden_everywhere(): void
    {
        $closed = $this->makeProvince('krabi', ['is_active' => false]);
        $tour = $this->makeTour($closed, ['name' => 'Krabi Hidden Tour']);

        $this->get('/programs')->assertDontSee('Krabi Hidden Tour');
        $this->get('/')->assertDontSee('Krabi Hidden Tour');
        $this->get(route('frontend.tours.show.v2', $tour->slug))->assertNotFound();
    }

    public function test_tour_page_shows_province(): void
    {
        $tour = $this->makeTour($this->makeProvince('phuket', ['name_en' => 'Phuket']));
        $this->makeSession($tour);

        $this->get(route('frontend.tours.show.v2', $tour->slug))
            ->assertOk()
            ->assertSee('class="tour-province"', false)
            ->assertSee('Phuket');
    }
}
```

- [ ] **Step 2: Run it to verify it fails**

Run: `/d/xampp/php/php.exe artisan test --filter=ProgramProvinceFilterTest`
Expected: FAIL. There is no filter or province chips yet, and the inactive-province tour is still listed.

- [ ] **Step 3: Language keys**

In `resources/lang/en/common.php` add:
```php
    'province'          => 'Province',
    'all_provinces'     => 'All provinces',
```
In `resources/lang/th/common.php` add:
```php
    'province'          => 'จังหวัด',
    'all_provinces'     => 'ทุกจังหวัด',
```

- [ ] **Step 4: HomeController**

In `app/Http/Controllers/HomeController.php`, add `use App\Models\Province;`.

In `programV2()`:
- Directly after the `$availableTags = ...->get();` statement, add:
```php
        $provinces = Province::active()->orderBy('name_th')->get();
        $selectedProvince = $provinces->firstWhere('slug', (string) $request->query('province', ''));
```
- In the `$tours = Tour::query()` chain, replace `->where('is_active', 1)` with `->visible()`, replace `->with(['tags', 'translations'])` with `->with(['tags', 'translations', 'province'])`, and add directly before `->orderByDesc('id')`:
```php
            ->when($selectedProvince, fn ($query) => $query->where('province_id', $selectedProvince->id))
```
- Change the return to:
```php
        return view('frontend_v2.pages.program', compact('tours', 'availableTags', 'selectedTags', 'searchTerm', 'provinces', 'selectedProvince'));
```

In `homeV2()`, in the `$tours = Tour::query()` chain, replace `->where('is_active', 1)` with `->visible()`, and `->with('translations')` with `->with(['translations', 'province'])`.

- [ ] **Step 5: TourController::showV2**

In `app/Http/Controllers/TourController.php` `showV2()`, replace:
```php
        $tour = Tour::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();
```
with:
```php
        $tour = Tour::query()
            ->visible()
            ->with('province')
            ->where('slug', $slug)
            ->firstOrFail();
```

- [ ] **Step 6: /programs view: province chips and card label**

In `resources/views/frontend_v2/pages/program.blade.php`:
- Directly after `<section id="program-list" class="program-list">` and its `<div class="container">`, before `<div class="program-filter">`, add:
```blade
    @if($provinces->count() > 1)
      <div class="program-provinces" aria-label="{{ __('common.province') }}">
        <a href="{{ request()->fullUrlWithoutQuery(['province']) }}" class="program-chip program-chip--selectable {{ $selectedProvince ? '' : 'is-active' }}">{{ __('common.all_provinces') }}</a>
        @foreach($provinces as $province)
          <a href="{{ request()->fullUrlWithQuery(['province' => $province->slug]) }}" class="program-chip program-chip--selectable {{ $selectedProvince?->is($province) ? 'is-active' : '' }}">{{ $province->name() }}</a>
        @endforeach
      </div>
    @endif
```
- Replace:
```blade
            <div class="program-meta">
              <span>From THB {{ number_format($tour->min_price ?? 0) }}</span>
            </div>
```
with:
```blade
            <div class="program-meta">
              <span>From THB {{ number_format($tour->min_price ?? 0) }}</span>
              @if($tour->province)<span class="program-province">{{ $tour->province->name() }}</span>@endif
            </div>
```
- In the page's `<style>` block, directly after the `.program-meta{ ... }` rule, add:
```css
.program-provinces{ display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
.program-provinces a{ text-decoration:none; }
.program-province{ margin-left:10px; opacity:.8; }
```
(The tag-chip JS rebuilds the URL from `window.location`, so it keeps `?province=`. Check this in Step 9.)

- [ ] **Step 7: Home card and tour page labels**

In `resources/views/frontend_v2/pages/home.blade.php`, replace:
```blade
                                            <div class="elephant-rescued">{{ strtoupper(__('common.program')) }}</div>
```
with:
```blade
                                            <div class="elephant-rescued">{{ strtoupper(__('common.program')) }}@if($tour->province) &middot; {{ $tour->province->name() }}@endif</div>
```

In `resources/views/frontend_v2/pages/tours/show.blade.php`, replace:
```blade
          <h1 class="tour-title">{{ $tourName }}</h1>
```
with:
```blade
          <h1 class="tour-title">{{ $tourName }}</h1>
          @if($tour->province)
            <div class="tour-province">{{ __('common.province') }}: {{ $tour->province->name() }}</div>
          @endif
```
and in that file's `<style>` block, directly after the first `.tour-title{ ... }` rule, add:
```css
.tour-province{ font-size:14px; color:#8b8177; margin:-4px 0 10px; }
```

- [ ] **Step 8: Run the tests**

Run: `/d/xampp/php/php.exe artisan test`
Expected: PASS (ProgramProvinceFilterTest has 6 tests, and all earlier tests pass).

- [ ] **Step 9: Browser check**

Create a second active province with one tour locally, or use a temporary one and delete it afterwards. Open `http://localhost:8083/programs`:
- the province chips appear;
- clicking one filters the list, and the URL has `?province=`;
- clicking a tag chip keeps `province` in the URL.

Check the tour page shows `Province: ...`. Record the result in the task report.

- [ ] **Step 10: Commit**

```bash
git add app/Http/Controllers/HomeController.php app/Http/Controllers/TourController.php resources/views/frontend_v2/pages/program.blade.php resources/views/frontend_v2/pages/home.blade.php resources/views/frontend_v2/pages/tours/show.blade.php resources/lang/en/common.php resources/lang/th/common.php tests/Feature/ProgramProvinceFilterTest.php
git commit -m "Show provinces on the public site and filter /programs by province

Province chips appear only when two or more provinces are active. Tours
in an inactive province are hidden from listings and their page 404s.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 10: Remove the nationality column from /admin/customers

**Files:**
- Modify: `resources/views/admin/customers/index.blade.php`
- Modify: `app/Models/Customer.php`

**Interfaces:** none. `customers.nationality` was dropped from the DB in `2025_11_23_101553`. This only removes the leftover UI and the fillable entry.

- [ ] **Step 1: Confirm nothing else uses it**

Run: `grep -rn "nationality" app resources/views --include=*.php`
Expected: only `app/Models/Customer.php` and `resources/views/admin/customers/index.blade.php`. If anything else shows up, stop and report it.

- [ ] **Step 2: Remove it**

In `resources/views/admin/customers/index.blade.php`, delete the line `<th>สัญชาติ</th>` and the line `<td>{{ $c->nationality ?? '-' }}</td>`.

In `app/Models/Customer.php` `$fillable`, delete the line `'nationality',`.

- [ ] **Step 3: Verify**

Run: `grep -rn "nationality" app resources/views --include=*.php && /d/xampp/php/php.exe artisan view:cache && /d/xampp/php/php.exe artisan view:clear && /d/xampp/php/php.exe artisan test`
Expected: grep prints nothing, the views compile, and all tests PASS.

- [ ] **Step 4: Commit**

```bash
git add resources/views/admin/customers/index.blade.php app/Models/Customer.php
git commit -m "Remove the nationality column from the admin customer list

The DB column was dropped in 2025; the list showed '-' for every row.

Co-Authored-By: Claude Opus 5 (1M context) <noreply@anthropic.com>"
```

---

### Task 11: Migrate the dev DB and check the whole flow

**Files:** none changed, unless the checks find a bug. If one does, fix it in the owning task's files and commit as `Fix ...`.

- [ ] **Step 1: Full test run**

Run: `/d/xampp/php/php.exe artisan test`
Expected: all PASS. Record the count.

- [ ] **Step 2: Migrate the dev DB (DigitalOcean, confirmed dev by the user)**

Run: `/d/xampp/php/php.exe artisan migrate --pretend | tail -30`. Read the SQL and confirm it only creates `provinces`, adds `province_id` columns and FKs, adds `pickup_note`, and inserts Chiang Mai.

Run: `/d/xampp/php/php.exe artisan migrate --force`
Expected: `2026_09_19_000001_create_provinces_table ... DONE`.

- [ ] **Step 3: Verify the backfill**

Run:
```bash
/d/xampp/php/php.exe artisan tinker --execute="echo json_encode(['provinces' => DB::table('provinces')->get(['id','slug']), 'tours_without_province' => DB::table('tours')->whereNull('province_id')->count(), 'pickups_without_province' => DB::table('pickup_locations')->whereNull('province_id')->count()]), PHP_EOL;"
```
Expected: one province `chiang-mai`, `tours_without_province: 0`, `pickups_without_province: 0`.

- [ ] **Step 4: Build frontend assets**

Run: `npm run build`
Expected: `✓ built`.

- [ ] **Step 5: Manual end-to-end on `http://localhost:8083`**

1. Admin → Provinces: create "ภูเก็ต / Phuket". Admin → Pick-up Locations: create "Bangtao Zone" in Phuket. Admin → Tours: move one test tour to Phuket.
2. Frontend booking page for that tour: the dropdown lists only "Bangtao Zone" (plus any other Phuket points). Pick it, type a note, and go through to the Stripe payment page (card, test mode).
3. Admin → Bookings → that booking: it shows the pickup "Bangtao Zone" and the note. The PDF and the CSV export show the same.
4. `/programs` shows the province chips and the filter works. Deactivate Phuket: its tour disappears from `/programs` and its tour page returns 404. Reactivate it.
5. Revert the test data: move the tour back to Chiang Mai, and delete "Bangtao Zone" and Phuket if they were only for testing (ask the user before deleting anything they may want to keep).

Record each result in the final report.

- [ ] **Step 6: Deployment notes for the user (do not run on prod)**

In the final report, list the production steps (the server runs Docker; see memory `prod-deploy`):
```bash
git pull
docker exec -w /var/www elephant-php php artisan migrate --force
docker exec -w /var/www elephant-php php artisan config:cache
docker exec -w /var/www elephant-php php artisan view:clear
npm ci && npm run build   # or inside elephant-node, as the user does today
```
Also note: after deploying, the admin must set a province on any tour or pickup point created between this migration and the deploy. Normally there are none, because the backfill covers all existing rows.
