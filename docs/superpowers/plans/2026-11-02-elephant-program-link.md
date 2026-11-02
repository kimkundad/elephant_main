# Elephant Program Link Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Each elephant names its program; "Read more" on the home page opens it, and Our Elephants is in the header and footer menus.

**Architecture:** A nullable `tour_id` foreign key on `elephants` (null on tour delete), required by the admin form. `Elephant::programUrl()` returns the tour page when the tour is visible, else the Our Elephants anchor. Menus get one more link each.

**Tech Stack:** Laravel 11, Blade, PHPUnit (sqlite in memory).

**Spec:** `docs/superpowers/specs/2026-11-02-elephant-program-link-design.md`

## Global Constraints

- Tour links use `route('frontend.tours.show.v2', $tour->slug)`, as the program cards on the home page do.
- Fallback link is today's: `route('frontend.elephants.v2') . '#' . $elephant->slug`.
- Menu label: `common.nav_elephants` = "Our Elephants" / "ช้างของเรา", placed right after Programs.

## Review Focus

- Elephant whose tour is inactive, or in an inactive province → falls back (uses `Tour::visible()`).
- Tour deleted → elephant survives with `tour_id` null, falls back.
- Existing elephant edited without choosing a program → form rejects it with an error (required).
- Admin posts a `tour_id` that does not exist → validation error, not a 500.
- Shared hover image now stands in for four menu items → `MenuHoverImagesTest` count goes 4 → 5.

---

### Task 1: Elephant belongs to a tour; admin form requires it

**Files:**
- Create: `database/migrations/2026_11_02_000001_add_tour_id_to_elephants_table.php`
- Modify: `app/Models/Elephant.php`, `app/Http/Controllers/Admin/ElephantController.php`, `resources/views/admin/elephants/create.blade.php`, `resources/views/admin/elephants/edit.blade.php`
- Test: `tests/Feature/Admin/ElephantProgramTest.php`

**Interfaces:**
- Produces: `Elephant::tour(): BelongsTo`, `Elephant::programUrl(): string`; `tour_id` fillable.

- [ ] **Step 1:** Tests: `test_store_requires_a_program` (no `tour_id` → session error `tour_id`), `test_store_saves_the_program`, `test_update_requires_a_program`, `test_unknown_program_is_rejected`, `test_deleting_the_tour_keeps_the_elephant` (tour_id becomes null), `test_program_url_points_at_a_visible_tour`, `test_program_url_falls_back_without_a_tour`, `test_program_url_falls_back_for_an_inactive_tour`. Fake `public` storage / uploader as needed for images.
- [ ] **Step 2:** Run, expect failures.
- [ ] **Step 3:** Migration `foreignId('tour_id')->nullable()->after('slug')->constrained('tours')->nullOnDelete()`. Validation `'tour_id' => ['required', 'integer', 'exists:tours,id']` in store and update; save it. Pass `$tours` (all tours by name, active and inactive, inactive labelled) to create/edit; add a required `<select name="tour_id">` row.
- [ ] **Step 4:** Run, expect pass.

### Task 2: Read more uses the program

**Files:**
- Modify: `app/Http/Controllers/HomeController.php:285-290` (eager load `tour.province`), `resources/views/frontend_v2/pages/home.blade.php:1185`
- Test: `tests/Feature/ElephantReadMoreTest.php`

- [ ] **Step 1:** Tests: home page shows `/v2/tours/<slug>` for an elephant with a visible tour, and `our-elephants#<slug>` for one without.
- [ ] **Step 2–4:** fail → replace href with `$elephant->programUrl()` → pass.

### Task 3: Our Elephants in header and footer

**Files:**
- Modify: `resources/lang/{en,th}/common.php`, `resources/views/frontend_v2/partials/header.blade.php` (pill nav + hamburger, `$menuLinkImages['elephants']` from `v2.header.menu.elephants_image`), `resources/views/frontend_v2/partials/footer.blade.php`, `app/Http/Controllers/Admin/PageMediaController.php` (preset key + description), `tests/Feature/MenuHoverImagesTest.php`
- Test: `tests/Feature/ElephantMenuTest.php`

- [ ] **Step 1:** Test home page HTML contains `route('frontend.elephants')` link in `.pillhdr__nav`, hamburger, and `.menu-footer`; update hover count 4 → 5 and add the elephants image to the own-picture test.
- [ ] **Step 2–4:** fail → implement → pass. `aria-current` when `request()->routeIs('frontend.elephants*')`.

### Task 4: Full suite

- [ ] `php artisan test` all green; commit when the user asks.
