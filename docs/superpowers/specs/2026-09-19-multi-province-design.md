# Multi-province support: design

- Date: 2026-09-19
- Status: waiting for spec review.
- Revision: on the booking page, the pickup section is now a dropdown plus a textarea. Google Maps and the radius zone are removed from the booking page.

## Goal

The system currently supports only one elephant camp, in Chiang Mai. The client wants the admin to be able to:

- create several provinces;
- assign each camp (a `Tour` in this system) to a province;
- assign each pickup location to a province.

On the booking page, the guest picks a pickup point from the list for the tour's province. If their hotel is not in the list, they type the details themselves.

The same batch also removes the "Nationality" column from `/admin/customers`.

## Decisions made in chat

| Question | Decision |
|---|---|
| What is a "camp" in the system? | An existing `Tour`. The structure is Province → Tour; there is no separate camp table. |
| How does pickup work? | The "Additional information" card on the booking page keeps 3 things: (1) the "I will travel by myself / เดินทางไปเอง" checkbox, (2) a pickup point dropdown drawn from the tour's province, (3) a textarea for details when the hotel is not in the list. |
| Google Maps | Removed **from the booking page only**: hotel search (Places), the meeting-point map, and the in/out-of-zone check. The admin pickup location form keeps its pin-drop map. |
| How much of the province appears on the frontend? | A province label, plus a province filter on `/programs`. The filter is hidden when only one province is active. |
| Development DB | The DigitalOcean DB in the local `.env` is a dev DB (confirmed by the user), so migrations can run on it. Automated tests use sqlite in-memory only. |

## Current state (checked in the code)

- `tours` has no location field. `pickup_locations` has `name`, `latitude`, `longitude`, `is_active`, `is_meeting_point`.
- The "Additional information" card in `frontend_v2/pages/booking/create.blade.php` currently has:
  - the `self_drive` checkbox;
  - hotel search with Google Places (hidden fields `google_place_*`, `google_lat/lng`, `pickup_source`, `pickup_out_of_bounds`);
  - a `meeting_point_id` dropdown with a meeting-point map, shown when the hotel is outside the Chiang Mai rectangle.
- The Chiang Mai rectangle is hard-coded in both `BookingController::isWithinChiangMaiBounds()` and the JS, and the two use different numbers. Both go away in this design.
- `bookings` already has these columns: `pickup_location_id`, `self_drive`, `pickup_source`, `pickup_place_name`, `pickup_place_address`.
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
| is_active | boolean default 1 | |
| timestamps | | |

The `name_th`/`name_en` naming follows the existing `tour_tags` table.

### Changes to existing tables

- `tours.province_id`: foreign key to `provinces.id`, `restrictOnDelete`.
- `pickup_locations.province_id`: foreign key to `provinces.id`, `restrictOnDelete`.
- These columns are nullable at the DB level so that the migration can backfill existing rows. Admin validation requires them on create and edit.
- `bookings.pickup_note`: `text`, nullable. It stores what the guest types in the textarea.
  - The old columns (`pickup_source`, `pickup_place_*`) are kept, because old bookings use them. New bookings no longer write `pickup_place_*`.

### Data migration

1. Create the province "เชียงใหม่ / Chiang Mai" (`chiang-mai`).
2. Set every existing tour and pickup location to this province.
3. `down()` reverses this in the opposite order.

### Model `Province`

- `hasMany` tours and pickupLocations.
- `name(?string $locale)` returns `name_th` or `name_en` for the current locale, falling back to `name_th`.
- Scope `active()`.

`Tour` and `PickupLocation` each get `belongsTo(Province::class)` and `province_id` added to `$fillable`. `Booking` gets `pickup_note` added to `$fillable`.

## 2. Admin

- New menu "จังหวัด" (Provinces) at `/admin/provinces`, using `Route::resource` without `show`. Views follow the pattern of `admin/pickup_locations`.
  - Form: name_th, name_en, slug (auto-generated from name_en, editable), is_active.
  - Delete is blocked, with a message, when tours or pickup locations still use the province.
- Tour create/edit: a required province dropdown. The tour list shows a province column and a province filter.
- Pickup location create/edit: add a required province dropdown. The map and everything else stays as it is. The list shows a province column and a province filter.
  - The `is_meeting_point` flag is kept, and is used to group options in the dropdown (see section 3).
- Admin booking create/edit: the pickup location dropdown shows only locations in the selected tour's province.
  - Render `data-province="{id}"` on each pickup `<option>` and on each tour `<option>`.
  - When the tour changes, JS hides the pickup options from other provinces and clears the value if it no longer matches.
  - No new endpoint is needed.
  - Add a `pickup_note` textarea, the same as on the frontend.
- Wherever the admin displays a booking's pickup (bookings index/show, PDF, CSV export), show the pickup point name plus `pickup_note`. For old bookings that have no `pickup_note`, fall back to `pickup_place_name`/`pickup_place_address` as today.
- `/admin/customers`: remove `<th>สัญชาติ</th>` and its `<td>` from `admin/customers/index.blade.php`, and remove `'nationality'` from `Customer::$fillable`. No migration is needed.

## 3. Booking page (frontend_v2)

### The "Additional information" card

It keeps 3 parts:

1. **Checkbox "I will travel by myself / เดินทางไปเอง"** (`self_drive`): unchanged. When ticked, parts 2 and 3 are hidden and not required.
2. **Dropdown "Hotel Pick up & Drop Off (If your hotel is not on the list, select your zone, e.g. "Bangtao" and enter your hotel name manually)"**, `name="pickup_location_id"`:
   - Options are the `is_active` pickup locations with `province_id` equal to the tour's province.
   - They are ordered by `name` and grouped with `<optgroup>`: hotels/zones (`is_meeting_point = 0`) first, then meeting points.
   - Required when self drive is not ticked (red asterisk, as in the reference design).
   - Placeholder: "Please select where you are staying".
3. **Textarea "If your hotel is not listed, please provide address below:"**, `name="pickup_note"`:
   - Optional, max 1000 characters.

The label text comes from `resources/lang/{th,en}/booking.php`.

### Removed from the booking page

- The Google Maps script tag (`maps.googleapis.com ... callback=initHotelAutocomplete`), `initHotelAutocomplete`, the `google_*` and `pickup_out_of_bounds` hidden fields, the meeting-point map (`#meetingMap`), and the `meeting_point_id` dropdown.
- The client-side validation messages related to these: `selectHotel`, `selectManualAddress`, `pinLocation`, `outOfBounds`, `selectMeetingPoint`.
  - They are replaced by one check: "Please select a pickup point", shown when self drive is not ticked and the dropdown is empty.

### `BookingController::createV2()`

- `$tour->load('province')`.
- Pass `$pickupLocations` to the view, filtered by `province_id = $tour->province_id` and `is_active = 1`, instead of `$meetingPoints`.

### `BookingController::store()`

- Validation:
  - `pickup_location_id` is `required_unless:self_drive,1` and must be an `exists` row that is `is_active`.
  - It must also have `province_id` equal to the tour's province. Otherwise return a validation error on `pickup_location_id`.
  - `pickup_note` is `nullable|string|max:1000`.
- Save `pickup_location_id`, `pickup_note` and `self_drive`, and set `pickup_source` to `list` or `self_drive`.
- Remove `isWithinChiangMaiBounds()` and the logic for `google_*`, `pickup_out_of_bounds` and `meeting_point_id`.
- A tour whose province is inactive cannot be booked. `createV2` and `store` return 404.

The legacy v1 booking page (`frontend/pages/booking/create.blade.php`) is not used by any route, so it is out of scope.

## 4. Frontend display

- Show the province name (in the current locale) on:
  - tour cards on the home and `/programs` pages;
  - the tour page (`tours/show`);
  - the booking page (the hero `booking-hero-sub`);
  - the confirmed page, `/b/{code}` (`public/booking.blade.php`), and the confirmation email.
- The confirmed page, `/b/{code}` and the email also show the pickup point name plus `pickup_note`, when there is one.
- `/programs` (`HomeController::programV2`):
  - The `?province=slug` parameter filters tours by province. It combines with the existing tag filter and search.
  - Province chips are shown only when there are 2 or more active provinces.
  - An unknown slug is ignored, and all tours are shown.
- Tours whose province is inactive are hidden from every frontend listing.
- Old bookings need no change, because the province is derived from `booking.tour.province`.

## 5. Translations

- `resources/lang/{th,en}/common.php`: the province label and "all provinces".
- `resources/lang/{th,en}/booking.php`: the dropdown label, placeholder, textarea label, and the error "Please select a pickup point". Remove keys that are no longer used.
- Province names come from the DB (`name_th`/`name_en`).

## Out of scope

- The Google Maps on the admin pickup location form. It stays as it is.
- The `/pickup-locations/search` and `/pickup-locations/resolve` endpoints. No view calls them today, so they are left unchanged.
- The legacy v1 frontend (`resources/views/frontend/**`), which no route uses.
- Pricing per province, and reports split by province.
- A search box inside the dropdown. A native `<select>` is used first; add a searchable select later if there are many pickup points.

## Testing

Tests use sqlite in-memory. Enable `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` in `phpunit.xml`, which currently has them commented out, so that `RefreshDatabase` never touches the real DB. If the old migrations do not run on sqlite, fix that as the first step of the plan.

1. **Feature** booking store:
   - A pickup point in another province is rejected.
   - An inactive pickup point is rejected.
   - No pickup point and no self drive is rejected.
   - Self drive with no pickup point passes.
   - `pickup_note` is saved.
2. **Feature** booking page:
   - The dropdown shows only the pickup points of the tour's province.
   - The page no longer loads `maps.googleapis.com`.
3. **Feature** `/programs?province=`: returns only tours in that province; an unknown slug returns everything; the chips are hidden when only one province is active.
4. **Feature** admin: deleting a province that has tours or pickup points is blocked.
5. **Migration**: after migrating, every existing tour and pickup location has `province_id` set to Chiang Mai. Check this on the dev DB.
6. **Manual** in a browser:
   - Tick and untick self drive.
   - Choose a pickup point plus a note, go through to the payment page, and check that the admin sees the pickup point and the note.
