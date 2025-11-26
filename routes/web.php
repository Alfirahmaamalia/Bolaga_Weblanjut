<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\SocialAuthController;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'penyedia'
            ? redirect()->route('penyedia.dashboard')
            : redirect()->route('penyewa.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/beranda', function () {
    return view('beranda');
});

Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Riwayat booking
    Route::get('/penyewa/booking', function () {
        return "Riwayat booking (belum dibuat)";
    })->name('penyewa.booking');

    // Detail Lapangan
    Route::get('/penyewa/lapangan/{id}', [LapanganController::class, 'detail'])
        ->name('penyewa.lapangan.detail');

    Route::get(
        '/penyewa/booking/konfirmasi/{lapangan_id}', 
         [LapanganController::class, 'konfirmasi']
    )->name('penyewa.booking.konfirmasi');


    Route::post('/penyewa/booking/pembayaran', 
        [LapanganController::class, 'pembayaran']
    )->name('penyewa.booking.pembayaran');



    // ================================
    // 🔥 Cek Slot Booking — Baru Ditambah
    // ================================
    Route::get('/penyewa/cek-slot', [LapanganController::class, 'cekSlot'])
        ->name('penyewa.cekSlot');
    
    // Route::post('/penyewa/cek-slot', [LapanganController::class, 'cekSlot'])
    //     ->name('penyewa.cekSlot');

    // DASHBOARD Penyedia
    Route::get('/penyedia/dashboard', function () {
        return view('penyedia.dashboard');
    })->name('penyedia.dashboard');

    Route::get('/penyewa/dashboard', [LapanganController::class, 'dashboard'])
        ->name('penyewa.dashboard');

    Route::get('/penyedia/kelola-lapangan', [LapanganController::class, 'kelolalapangan'])
        ->name('penyedia.kelolalapangan');

    Route::post('/penyedia/lapangan', [LapanganController::class, 'store'])
        ->name('penyedia.lapangan.store');

    Route::put('/penyedia/lapangan/{lapangan}', [LapanganController::class, 'update'])
        ->name('penyedia.lapangan.update');

    Route::delete('/penyedia/lapangan/{lapangan}', [LapanganController::class, 'destroy'])
        ->name('penyedia.lapangan.destroy');
});
