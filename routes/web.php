<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', function () {
    return view('welcome');
});





// ไม่ต้อง login
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

Route::get('/logout', [AuthController::class, 'logout']);

// ต้อง login + role
Route::middleware(['auth', 'role:superAdmin|admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/test', function(){
        return "rewrite ok";
    });

        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });


// ตัวอย่างหน้า customer login/profile
Route::middleware(['auth', 'role:customer'])->group(function () {

});


