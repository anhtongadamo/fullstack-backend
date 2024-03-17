<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test', function () {
    return [
        'test' => "test api endpoint success"
    ];
});
Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('/tours')->group( function() {
        Route::get('/', [TourController::class, 'index'])->name('tours.index');
        Route::get('/{id}', [TourController::class, 'show'])->name('tours.show');
        Route::post('/', [TourController::class, 'store'])->name('tours.store');
        Route::put('/{id}', [TourController::class, 'update'])->name('tours.update');
        Route::put('/disabled-tour-date', [TourController::class, 'update'])->name('tours.update');
    });

    Route::prefix('/tours-date')->group( function() {
        Route::post('/{id}/update', [TourController::class, 'toggleDate'])->name('tours.disabled');
    });

    Route::prefix('/bookings')->group( function() {
        Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/{id}', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/{id}', [BookingController::class, 'update'])->name('bookings.update');
    });
});
