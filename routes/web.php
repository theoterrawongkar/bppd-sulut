<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TourPlaceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventPlaceController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CulinaryPlaceController;
use App\Http\Controllers\MyCulinaryPlaceController;
use App\Http\Controllers\MyTourPlaceController;
use App\Http\Controllers\Profile\ProfileController;

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

    Route::get('/profil-saya', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-saya', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/wisata/{slug}/review', [TourPlaceController::class, 'storeReview'])->name('tourplace.review.store');
    Route::post('/kuliner/{slug}/review', [CulinaryPlaceController::class, 'storeReview'])->name('culinaryplace.review.store');

    Route::get('/kuliner-saya', [MyCulinaryPlaceController::class, 'index'])->name('myculinaryplace.index');
    Route::get('/kuliner-saya/tambah', [MyCulinaryPlaceController::class, 'create'])->name('myculinaryplace.create');
    Route::post('/kuliner-saya/tambah', [MyCulinaryPlaceController::class, 'store'])->name('myculinaryplace.store');
    Route::get('/kuliner-saya/{slug}/ubah', [MyCulinaryPlaceController::class, 'edit'])->name('myculinaryplace.edit');
    Route::put('/kuliner-saya/{slug}/ubah', [MyCulinaryPlaceController::class, 'update'])->name('myculinaryplace.update');

    Route::get('/wisata-saya', [MyTourPlaceController::class, 'index'])->name('mytourplace.index');
    Route::get('/wisata-saya/tambah', [MyTourPlaceController::class, 'create'])->name('mytourplace.create');
    Route::post('/wisata-saya/tambah', [MyTourPlaceController::class, 'store'])->name('mytourplace.store');
    Route::get('/wisata-saya/{slug}/ubah', [MyTourPlaceController::class, 'edit'])->name('mytourplace.edit');
    Route::put('/wisata-saya/{slug}/ubah', [MyTourPlaceController::class, 'update'])->name('mytourplace.update');
});
