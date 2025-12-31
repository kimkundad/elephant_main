<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourSessionController;
use App\Http\Controllers\Admin\TourAvailabilityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\HomeController;

// หน้าแรก
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
    Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');

    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
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

        // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');


        /*
        |--------------------------------------------------------------------------
        | TOURS CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('tours', TourController::class);
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
