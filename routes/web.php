<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourSessionController;
use App\Http\Controllers\Admin\TourAvailabilityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TourController as FrontTourController;
use App\Http\Controllers\BookingController as FrontBookingController;
use App\Http\Controllers\PickupLocationLookupController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\BookingPublicController;
use App\Http\Controllers\GoogleReviewsController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ElephantController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\AgentReportController;
use App\Http\Controllers\Admin\SiteTextController;
use App\Http\Controllers\Admin\TourTagController;
use App\Http\Controllers\Admin\PageMediaController;

Route::get('/api/google-reviews', [GoogleReviewsController::class, 'index']);



Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');
    

Route::get('/b/{code}', [BookingPublicController::class, 'show'])->name('booking.public');

// หน้าแรก
Route::name('frontend.')->group(function () {
    Route::get('/locale/{locale}', function (string $locale) {
        if (!in_array($locale, ['th', 'en'], true)) {
            $locale = 'th';
        }
        session(['locale' => $locale]);
        return redirect()->back();
    })->name('locale.switch');

    // Primary public frontend now uses V2.
    Route::get('/', [HomeController::class, 'homeV2'])->name('home');
    Route::get('/home', fn () => redirect()->route('frontend.home'));
    Route::get('/v2', fn () => redirect()->route('frontend.home'))->name('home.v2');

    Route::get('/about', [HomeController::class, 'aboutV2'])->name('about');
    Route::get('/v2/about', fn () => redirect()->route('frontend.about'))->name('about.v2');

    Route::get('/programs', [HomeController::class, 'programV2'])->name('program');
    Route::get('/v2/programs', [HomeController::class, 'programV2'])->name('program.v2');

    Route::get('/contact', [HomeController::class, 'contactV2'])->name('contact');
    Route::post('/contact', [HomeController::class, 'contactStoreV2'])->name('contact.store');
    Route::get('/v2/contact', fn () => redirect()->route('frontend.contact'))->name('contact.v2');
    Route::post('/v2/contact', [HomeController::class, 'contactStoreV2'])->name('contact.v2.store');

    Route::get('/our-elephants', [HomeController::class, 'elephantsV2'])->name('elephants');
    Route::get('/v2/our-elephants', fn () => redirect()->route('frontend.elephants'))->name('elephants.v2');

    
    Route::view('/what-to-bring', 'frontend_v2.pages.what-to-bring')->name('what_to_bring');
    Route::view('/support-us', 'frontend_v2.pages.support-us')->name('support_us');
    Route::view('/terms', 'frontend_v2.pages.terms')->name('terms');
    Route::view('/policy', 'frontend_v2.pages.policy')->name('policy');

    Route::view('/v2/what-to-bring', 'frontend_v2.pages.what-to-bring')->name('what_to_bring.v2');
    Route::view('/v2/support-us', 'frontend_v2.pages.support-us')->name('support_us.v2');
    Route::view('/v2/terms', 'frontend_v2.pages.terms')->name('terms.v2');
    Route::view('/v2/policy', 'frontend_v2.pages.policy')->name('policy.v2');

    // ตรวจสถานะ PromptPay (polling จากหน้า QR)
    Route::get('/booking/{booking}/payment-status', [FrontBookingController::class, 'paymentStatus'])
        ->name('booking.payment_status');

    // Success (Card) – Stripe redirect กลับมา
    Route::get('/booking/{booking}/success', function (\App\Models\Booking $booking) {
        return redirect()->route('frontend.booking.confirmed', $booking);
    })->name('booking.success');

    Route::get('/v2/booking/{booking}/success', function (\App\Models\Booking $booking) {
        return redirect()->route('frontend.booking.confirmed.v2', $booking);
    })->name('booking.success.v2');

    // Cancel (Card)
    Route::get('/booking/{booking}/cancel', function (\App\Models\Booking $booking) {
        $booking->update([
            'payment_status' => 'canceled',
        ]);

        return redirect()
            ->route('frontend.booking.create')
            ->with('error', 'Payment was canceled');
    })->name('booking.cancel');

    Route::get('/v2/booking/{booking}/cancel', function (\App\Models\Booking $booking) {
        $booking->update([
            'payment_status' => 'canceled',
        ]);

        return redirect()
            ->route('frontend.booking.create')
            ->with('error', 'Payment was canceled');
    })->name('booking.cancel.v2');


    // ✅ ใช้ Frontend TourController
    Route::get('/tours', fn () => redirect()->route('frontend.program'))->name('tours.index');
    Route::get('/tours/{slug}', [FrontTourController::class, 'showV2'])->name('tours.show');
    Route::get('/v2/tours/{slug}', [FrontTourController::class, 'showV2'])->name('tours.show.v2');

    // ดึงรอบตามวันที่ (ไว้ทำเปลี่ยนวันแบบในรูป)
    Route::get('/tours/{slug}/sessions', [FrontTourController::class, 'sessionsForDate'])
        ->name('tours.sessions');

        Route::get('/booking', [FrontBookingController::class, 'createV2'])
        ->name('booking.create');

    Route::post('/booking', [FrontBookingController::class, 'store'])
        ->name('booking.store');
    Route::get('/v2/booking', [FrontBookingController::class, 'createV2'])
        ->name('booking.create.v2');
    Route::get('/v2/booking/confirmed/{booking}', [FrontBookingController::class, 'confirmedV2'])
        ->name('booking.confirmed.v2');
    Route::post('/booking/validate-discount', [FrontBookingController::class, 'validateDiscount'])
        ->name('booking.validate-discount');


        Route::get('/pickup-locations/search', [PickupLocationLookupController::class, 'search'])
    ->name('pickup-locations.search');

Route::get('/pickup-locations/resolve', [PickupLocationLookupController::class, 'resolve'])
    ->name('pickup-locations.resolve');

    Route::get('/booking/confirmed/{booking}', [FrontBookingController::class, 'confirmedV2'])
    ->name('booking.confirmed');


});

// เพิ่ม route alias สำหรับ login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// --------------------
// ไม่ต้อง login
// --------------------
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:superAdmin|admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Site settings
        Route::get('/settings', [SiteSettingController::class, 'edit'])
            ->name('settings.edit');
        Route::post('/settings', [SiteSettingController::class, 'update'])
            ->name('settings.update');

        // Site texts (v2)
        Route::get('/site-texts/home', [SiteTextController::class, 'editHome'])
            ->name('site-texts.home');
        Route::post('/site-texts/home', [SiteTextController::class, 'updateHome'])
            ->name('site-texts.home.update');
        Route::get('/site-texts/about', [SiteTextController::class, 'editAbout'])
            ->name('site-texts.about');
        Route::post('/site-texts/about', [SiteTextController::class, 'updateAbout'])
            ->name('site-texts.about.update');

        // Elephants
        Route::resource('elephants', ElephantController::class);
        Route::get('/elephants/{elephant}/toggle', [ElephantController::class, 'toggle'])
            ->name('elephants.toggle');

        // Agents & Discount Codes
        Route::resource('agents', AgentController::class);
        Route::resource('discount-codes', DiscountCodeController::class);
        Route::get('/reports/agents', [AgentReportController::class, 'index'])
            ->name('reports.agents');
        Route::get('/reports/agents/export', [AgentReportController::class, 'exportCsv'])
            ->name('reports.agents.export');

        // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');


        /*
        |--------------------------------------------------------------------------
        | TOURS CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('tours', TourController::class);
        Route::resource('tour-tags', TourTagController::class)->except(['show']);
        Route::resource('page-media', PageMediaController::class)
            ->parameters(['page-media' => 'pageMedia'])
            ->except(['show']);
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
        Route::resource('bookings', BookingController::class)->only([
            'index', 'create', 'store'
        ]);




        Route::resource('pickup-locations', App\Http\Controllers\Admin\PickupLocationController::class);

       Route::get('/bookings/get-sessions', [BookingController::class, 'ajaxSessions'])
            ->name('bookings.ajax-sessions');

        Route::get('/bookings/check-capacity', [BookingController::class, 'ajaxCapacity'])
            ->name('bookings.ajax-capacity');


            // View booking
        Route::get('/bookings/{id}', [BookingController::class, 'show'])
            ->name('bookings.show');

        // Edit booking
        Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])
            ->name('bookings.edit');

        Route::post('/bookings/{id}/update', [BookingController::class, 'update'])
            ->name('bookings.update');

        // Cancel booking (ไม่ลบ)
        Route::get('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
            ->name('bookings.cancel');

        // PDF Invoice
        Route::get('/bookings/{id}/pdf', [BookingController::class, 'pdf'])
            ->name('bookings.pdf');

        // เปิด/ปิดการมองเห็นโปรแกรม
        Route::get('tours/{id}/toggle', [TourController::class, 'toggle'])
            ->name('tours.toggle');

            Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
    Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');

    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');


        /*
        |--------------------------------------------------------------------------
        | SESSIONS: ของโปรแกรมแต่ละตัว
        |--------------------------------------------------------------------------
        */
        Route::prefix('tours/{tour}/sessions')
            ->name('tours.sessions.')
            ->group(function () {

                Route::get('/', [TourSessionController::class, 'index'])->name('index');
                Route::get('/create', [TourSessionController::class, 'create'])->name('create');
                Route::post('/', [TourSessionController::class, 'store'])->name('store');
                Route::get('/{session}/edit', [TourSessionController::class, 'edit'])->name('edit');
                Route::put('/{session}', [TourSessionController::class, 'update'])->name('update');
                Route::delete('/{session}', [TourSessionController::class, 'destroy'])->name('destroy');
            });


            // Availability รายวัน
        Route::prefix('tours/{tour}/availability')
            ->name('tours.availability.')
            ->group(function () {

                Route::get('/', [TourAvailabilityController::class, 'index'])->name('index');
                Route::post('/', [TourAvailabilityController::class, 'store'])->name('store');

            });


        /*
        |--------------------------------------------------------------------------
        | SESSIONS: แสดง Sessions ของทุกโปรแกรม
        |--------------------------------------------------------------------------
        */

        Route::get('/sessions', [TourSessionController::class, 'all'])
            ->name('sessions.all');


        /*
        |--------------------------------------------------------------------------
        | TEST
        |--------------------------------------------------------------------------
        */
        Route::get('/test', function () {
            return "rewrite ok";
        });


        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */
        Route::get('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });


// --------------------
// ตัวอย่างหน้า customer login/profile
// --------------------
Route::middleware(['auth', 'role:customer'])->group(function () {
    // เพิ่ม route สำหรับ customer ที่นี่
});
