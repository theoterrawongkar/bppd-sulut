<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TourPlaceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventPlaceController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CulinaryPlaceController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');

Route::get('/event/{slug}', [EventPlaceController::class, 'show'])->name('eventplace.show');

Route::get('/wisata', [TourPlaceController::class, 'index'])->name('tourplace.index');
Route::get('/wisata/{slug}', [TourPlaceController::class, 'show'])->name('tourplace.show');

Route::get('/kuliner', [CulinaryPlaceController::class, 'index'])->name('culinaryplace.index');
Route::get('/kuliner/{slug}', [CulinaryPlaceController::class, 'show'])->name('culinaryplace.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:5,5');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,5');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/wisata/{slug}/review', [TourPlaceController::class, 'storeReview'])->name('tourplace.review.store');
    Route::post('/kuliner/{slug}/review', [CulinaryPlaceController::class, 'storeReview'])->name('culinaryplace.review.store');
});
