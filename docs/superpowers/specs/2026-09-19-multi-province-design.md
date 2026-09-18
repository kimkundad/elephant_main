# Multi-province support: design

- Date: 2026-09-19
- Status: approved in chat, waiting for spec review

## Goal

The system currently supports only one elephant camp, in Chiang Mai. The client wants the admin to be able to:

- create several provinces;
- assign each camp (a `Tour` in this system) to a province;
- assign each pickup location to a province.

Guests should see which province a tour is in and be able to filter tours by province.

The same batch also removes the "Nationality" column from `/admin/customers`.

## Decisions made in chat

| Question | Decision |
|---|---|
| What is a "camp" in the system? | An existing `Tour`. The structure is Province → Tour; there is no separate camp table. |
| How does pickup work across provinces? | Same as today, but per province. Each province has its own free hotel-pickup zone, defined as a center point plus a radius in km. A guest outside the zone must choose a meeting point in the tour's province. |
| How much of the province appears on the frontend? | A province label, plus a province filter on `/programs`. The filter is hidden when only one province is active. |
| Development DB | The DigitalOcean DB in the local `.env` is a dev DB (confirmed by the user), so migrations can run on it. Automated tests use sqlite in-memory only. |

## Current state (checked in the code)

- `tours` has no location field. `pickup_locations` has `name`, `latitude`, `longitude`, `is_active`, `is_meeting_point`.
- The free pickup zone is hard-coded as a Chiang Mai rectangle in two places, and the two use different numbers:
  - Server: `BookingController::isWithinChiangMaiBounds()` uses lat 18.730–18.840, lng 98.930–99.050.
  - JS: `frontend_v2/pages/booking/create.blade.php` uses lat 18.760–18.815, lng 98.955–99.025, and centers the map at 18.7883, 98.9853.
- `createV2()` loads every active meeting point, from every area.
- `/admin/customers` still shows the "สัญชาติ" (nationality) column, but the column was dropped from the DB in migration `2025_11_23_101553`, so every row shows `-`.
- Current data: 5 tours, 7 pickup locations.

## 1. Data

### New table `provinces`

| column | type | note |
|---|---|---|
| id | bigint PK | |
| name_th | string | e.g. เชียงใหม่ |
| name_en | string | e.g. Chiang Mai |
| slug | string unique | used in the `?province=` URL parameter, e.g. `chiang-mai` |
| center_lat | decimal(10,7) | center of the free pickup zone |
| center_lng | decimal(10,7) | |
| pickup_radius_km | decimal(6,2) | radius of the free pickup zone |
| is_active | boolean default 1 | |
| timestamps | | |

The `name_th`/`name_en` naming follows the existing `tour_tags` table.

### Changes to existing tables

- `tours.province_id`: foreign key to `provinces.id`, `restrictOnDelete`.
- `pickup_locations.province_id`: foreign key to `provinces.id`, `restrictOnDelete`.
- The column is nullable at the DB level so that the migration can backfill existing rows. Admin validation requires it on create and edit.

### Data migration

In the same migration:

1. Create the province "เชียงใหม่ / Chiang Mai" (`chiang-mai`) with center 18.7883, 98.9853 and radius 6 km. This roughly covers the old rectangle; the admin can adjust it later.
2. Set every existing tour and pickup location to this province.
3. `down()` removes the foreign keys and columns, then drops the table.

### Model `Province`

- `hasMany` tours and pickupLocations.
- `name(?string $locale)` returns `name_th` or `name_en` for the current locale, falling back to `name_th`.
- `containsPoint(float $lat, float $lng): bool` computes the haversine distance from the center and compares it with `pickup_radius_km`. This is the single place where the free pickup zone is decided.
- Scope `active()`.

`Tour` and `PickupLocation` each get `belongsTo(Province::class)` and `province_id` added to `$fillable`.

## 2. Admin

- New menu "จังหวัด" (Provinces) at `/admin/provinces`, using `Route::resource` without `show`. Views follow the pattern of `admin/pickup_locations`.
  - Form: name_th, name_en, slug (auto-generated from name_en, editable), a map for picking the center (Google Maps is already used on the site; clicking the map sets lat/lng), radius in km, and is_active.
  - Delete is blocked, with a message, when tours or pickup locations still use the province.
- Tour create/edit: a required province dropdown. The tour list shows a province column and a province filter.
- Pickup location create/edit: a required province dropdown. The list shows a province column and a province filter.
- Admin booking create/edit: the pickup location dropdown shows only locations in the selected tour's province.
  - Render `data-province="{id}"` on each pickup `<option>` and on each tour `<option>`.
  - When the tour changes, JS hides the pickup options from other provinces and clears the value if it no longer matches.
  - No new endpoint is needed.
- `/admin/customers`: remove `<th>สัญชาติ</th>` and its `<td>` from `admin/customers/index.blade.php`, and remove `'nationality'` from `Customer::$fillable`. No migration is needed.

## 3. Booking page (frontend_v2)

`BookingController::createV2()`:

- `$tour->load('province')`.
- `$meetingPoints` is filtered with `where('province_id', $tour->province_id)`.
- Pass `pickupZone = { lat, lng, radiusKm }` from the province to the view.

`frontend_v2/pages/booking/create.blade.php` (JS):

- Remove the hard-coded rectangle and the hard-coded map center. Use `pickupZone` instead:
  - Hotel autocomplete bias: bounds from `new google.maps.Circle({center, radius}).getBounds()`.
  - Map center: the province's center.
  - `isWithinBounds(lat, lng)`: haversine distance ≤ radiusKm. This is the same formula as the server, which avoids the two mismatched rectangles we have today.

`BookingController::store()`:

- Replace `isWithinChiangMaiBounds()` with `$tour->province->containsPoint($lat, $lng)`.
- If a meeting point is chosen, check that `pickup_location.province_id === tour.province_id`. If not, return a validation error on `meeting_point_id`.
- A tour whose province is inactive cannot be booked. `createV2` and `store` return 404.

The legacy v1 booking page (`frontend/pages/booking/create.blade.php`) is not used by any route, so it is out of scope.

## 4. Frontend display

- Show the province name (in the current locale) on:
  - tour cards on the home and `/programs` pages;
  - the tour page (`tours/show`);
  - the booking page (the hero `booking-hero-sub`);
  - the confirmed page, `/b/{code}` (`public/booking.blade.php`), and the confirmation email.
- `/programs` (`HomeController::programV2`):
  - The `?province=slug` parameter filters tours by province. It combines with the existing tag filter and search.
  - Province chips are shown only when there are 2 or more active provinces.
  - An unknown slug is ignored, and all tours are shown.
- Tours whose province is inactive are hidden from every frontend listing.
- Old bookings need no change, because the province is derived from `booking.tour.province`.

## 5. Translations

- New keys in `resources/lang/{th,en}/common.php`: the province label and "all provinces".
- Province names come from the DB (`name_th`/`name_en`).

## Out of scope

- The `/pickup-locations/search` and `/pickup-locations/resolve` endpoints. No view calls them today, so they are left unchanged.
- The legacy v1 frontend (`resources/views/frontend/**`), which no route uses.
- Pricing per province, and reports split by province.

## Testing

Tests use sqlite in-memory. Enable `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` in `phpunit.xml`, which currently has them commented out, so that `RefreshDatabase` never touches the real DB. If the old migrations do not run on sqlite, fix that as the first step of the plan.

1. **Unit** `Province::containsPoint`: a point at the center, a point just inside the radius, a point just outside it.
2. **Feature** booking store:
   - A meeting point in another province is rejected.
   - A hotel inside the radius passes without a meeting point.
   - A hotel outside the radius without a meeting point is rejected.
3. **Feature** `/programs?province=`: returns only tours in that province; an unknown slug returns everything; the chips are hidden when only one province is active.
4. **Feature** admin: deleting a province that has tours is blocked.
5. **Migration**: after migrating, every existing tour and pickup location has `province_id` set to Chiang Mai. Check this on the dev DB.
6. **Manual** on the booking page: the map opens at the tour's province, the meeting points listed belong only to that province, and the in-zone/out-of-zone result matches the server.
