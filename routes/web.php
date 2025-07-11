<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TourPlaceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventPlaceController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CulinaryPlaceController;
use App\Http\Controllers\Dashboard\DashboardArtistProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DashboardCulinaryPlaceController;
use App\Http\Controllers\Dashboard\DashboardEventPlaceController;
use App\Http\Controllers\Dashboard\DashboardTourPlaceController;
use App\Http\Controllers\Dashboard\DashboardUserController;
use App\Http\Controllers\MyCulinaryPlaceController;
use App\Http\Controllers\MyEventParticipationController;
use App\Http\Controllers\MyTourPlaceController;
use App\Http\Controllers\Profile\ProfileController;

// Home dan About
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');

// Event
Route::get('/event/{slug}', [EventPlaceController::class, 'show'])->name('eventplace.show');

// Wisata
Route::get('/wisata', [TourPlaceController::class, 'index'])->name('tourplace.index');
Route::get('/wisata/{slug}', [TourPlaceController::class, 'show'])->name('tourplace.show');

// Kuliner
Route::get('/kuliner', [CulinaryPlaceController::class, 'index'])->name('culinaryplace.index');
Route::get('/kuliner/{slug}', [CulinaryPlaceController::class, 'show'])->name('culinaryplace.show');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:5,5');

    // Register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,5');
});

Route::middleware(['auth', 'active_user'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('throttle:5,5')->name('logout');

    // Profil Saya
    Route::get('/profil-saya', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-saya', [ProfileController::class, 'update'])->middleware('throttle:5,5')->name('profile.update');

    // Memberi Review
    Route::post('/wisata/{slug}/review', [TourPlaceController::class, 'storeReview'])->middleware('throttle:5,5')->name('tourplace.review.store');
    Route::post('/kuliner/{slug}/review', [CulinaryPlaceController::class, 'storeReview'])->middleware('throttle:5,5')->name('culinaryplace.review.store');

    // Hanya Seniman
    Route::middleware(['role:Seniman'])->group(function () {
        Route::get('/partisipasi-saya', [MyEventParticipationController::class, 'index'])->name('myeventparticipation.index');
        Route::post('/partisipasi-saya/tambah', [MyEventParticipationController::class, 'store'])->middleware('throttle:5,5')->name('myeventparticipation.store');
        Route::delete('/partisipasi-saya/{slug}/hapus', [MyEventParticipationController::class, 'destroy'])->middleware('throttle:5,5')->name('myeventparticipation.destroy');
    });

    // Hanya Pengusaha Wisata
    Route::middleware(['role:Pengusaha Wisata'])->group(function () {
        Route::get('/wisata-saya', [MyTourPlaceController::class, 'index'])->name('mytourplace.index');
        Route::get('/wisata-saya/tambah', [MyTourPlaceController::class, 'create'])->name('mytourplace.create');
        Route::post('/wisata-saya/tambah', [MyTourPlaceController::class, 'store'])->name('mytourplace.store')->middleware('throttle:5,5');
        Route::get('/wisata-saya/{slug}/ubah', [MyTourPlaceController::class, 'edit'])->name('mytourplace.edit');
        Route::put('/wisata-saya/{slug}/ubah', [MyTourPlaceController::class, 'update'])->name('mytourplace.update')->middleware('throttle:5,5');
    });

    // Hanya Pengusaha Kuliner
    Route::middleware(['role:Pengusaha Kuliner'])->group(function () {
        Route::get('/kuliner-saya', [MyCulinaryPlaceController::class, 'index'])->name('myculinaryplace.index');
        Route::get('/kuliner-saya/tambah', [MyCulinaryPlaceController::class, 'create'])->name('myculinaryplace.create');
        Route::post('/kuliner-saya/tambah', [MyCulinaryPlaceController::class, 'store'])->name('myculinaryplace.store')->middleware('throttle:5,5');
        Route::get('/kuliner-saya/{slug}/ubah', [MyCulinaryPlaceController::class, 'edit'])->name('myculinaryplace.edit');
        Route::put('/kuliner-saya/{slug}/ubah', [MyCulinaryPlaceController::class, 'update'])->name('myculinaryplace.update')->middleware('throttle:5,5');
    });

    // Hanya Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Status Akun
        Route::put('/dashboard/user/{user}/toggle', [DashboardUserController::class, 'toggleStatus'])->middleware('throttle:10,5')->name('dashboard.user.toggle');

        // Status Partisipan
        Route::patch('/dashboard/event/participant/{id}/update-status', [DashboardEventPlaceController::class, 'updateStatus'])->middleware('throttle:10,5')
            ->name('dashboard.eventparticipant.updateStatus');

        // Manajemen Event
        Route::get('/dashboard/event', [DashboardEventPlaceController::class, 'index'])->name('dashboard.eventplace.index');
        Route::get('/dashboard/event/tambah', [DashboardEventPlaceController::class, 'create'])->name('dashboard.eventplace.create');
        Route::post('/dashboard/event/tambah', [DashboardEventPlaceController::class, 'store'])->middleware('throttle:10,5')->name('dashboard.eventplace.store');
        Route::get('/dashboard/event/{slug}/ubah', [DashboardEventPlaceController::class, 'edit'])->name('dashboard.eventplace.edit');
        Route::put('/dashboard/event/{slug}/ubah', [DashboardEventPlaceController::class, 'update'])->middleware('throttle:10,5')->name('dashboard.eventplace.update');
        Route::delete('/dashboard/event/{slug}/hapus', [DashboardEventPlaceController::class, 'destroy'])->middleware('throttle:10,5')->name('dashboard.eventplace.destroy');

        // Manajemen Wisata
        Route::get('/dashboard/wisata', [DashboardTourPlaceController::class, 'index'])->name('dashboard.tourplace.index');
        Route::get('/dashboard/wisata/{slug}/ubah', [DashboardTourPlaceController::class, 'edit'])->name('dashboard.tourplace.edit');
        Route::put('/dashboard/wisata/{slug}/ubah', [DashboardTourPlaceController::class, 'update'])->middleware('throttle:10,5')->name('dashboard.tourplace.update');
        Route::delete('/dashboard/wisata/{slug}/hapus', [DashboardTourPlaceController::class, 'destroy'])->middleware('throttle:10,5')->name('dashboard.tourplace.destroy');

        // Manajemen Kuliner
        Route::get('/dashboard/kuliner', [DashboardCulinaryPlaceController::class, 'index'])->name('dashboard.culinaryplace.index');
        Route::get('/dashboard/kuliner/{slug}/ubah', [DashboardCulinaryPlaceController::class, 'edit'])->name('dashboard.culinaryplace.edit');
        Route::put('/dashboard/kuliner/{slug}/ubah', [DashboardCulinaryPlaceController::class, 'update'])->middleware('throttle:10,5')->name('dashboard.culinaryplace.update');
        Route::delete('/dashboard/kuliner/{slug}/hapus', [DashboardCulinaryPlaceController::class, 'destroy'])->middleware('throttle:10,5')->name('dashboard.culinaryplace.destroy');

        // Manajemen Seniman
        Route::get('/dashboard/seniman', [DashboardArtistProfileController::class, 'index'])->name('dashboard.artistprofile.index');
        Route::get('/dashboard/seniman/{id}/ubah', [DashboardArtistProfileController::class, 'edit'])->name('dashboard.artistprofile.edit');
        Route::put('/dashboard/seniman/{id}/ubah', [DashboardArtistProfileController::class, 'update'])->middleware('throttle:10,5')->name('dashboard.artistprofile.update');
        Route::delete('/dashboard/seniman/{id}/hapus', [DashboardArtistProfileController::class, 'destroy'])->middleware('throttle:10,5')->name('dashboard.artistprofile.destroy');
    });
});
