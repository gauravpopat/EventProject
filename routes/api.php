<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    //Admin
    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        Route::post('create-event', 'create')->name('create-event');
        Route::get('show-bookings','showBookings')->name('show-bookings');
        Route::get('show-event-users','showEventUsers')->name('showEventUsers');
    });

    // Booking
    Route::controller(UserController::class)->group(function () {
        Route::get('/list-event', 'list')->name('list');
        Route::post('/book-event','book')->name('book');
        Route::get('/show-history', 'show')->name('show');
        Route::get('/get-current-event','getCurrentEvent')->name('get-current-event');
        Route::get('/get-event-by-day','getEventByDay')->name('get-event-by-day');
        Route::get('/get-event-by-month','getEventByMonth')->name('get-event-by-month');
        Route::get('/get-event-by-year','getEventByYear')->name('get-event-by-year');
        // For Example Purpose : Route::get('/all-Example','allExample')->name('all-Example');
    });
});

// Guest User
Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('/create', 'create')->name('create');
    Route::post('/login', 'login')->name('login');

});

// Admin Login
Route::post('admin/login', [AuthController::class,'adminLogin'])->name('login');