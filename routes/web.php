<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Penyedia\DashboardController as PenyediaDashboard;
use App\Http\Controllers\Penyewa\DashboardController as PenyewaDashboard;
use App\Http\Controllers\Penyedia\LapanganController;
use App\Http\Controllers\Penyewa\BookingController;


// ---- Landing: redirect sesuai kondisi login ----
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'penyedia'
            ? redirect()->route('penyedia.dashboard')
            : redirect()->route('penyewa.dashboard');
    }
    return redirect()->route('login');
});


// ---- Halaman Beranda untuk user umum (tanpa login) ----
Route::get('/beranda', function () {
    return view('beranda');
})->name('beranda');


// ---- Authentication ----
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// ---- Dashboard Penyedia ----
Route::middleware(['auth', 'role:penyedia'])->group(function () {

    Route::get('/penyedia/dashboard', [PenyediaDashboard::class, 'index'])
        ->name('penyedia.dashboard');

    // CRUD Laporan / Data Lapangan
    Route::resource('/penyedia/lapangan', LapanganController::class);

    // melihat booking dari penyewa
    Route::get('/penyedia/booking', [LapanganController::class, 'bookingMasuk'])
        ->name('penyedia.booking');
});


// ---- Dashboard Penyewa ----
Route::middleware(['auth', 'role:penyewa'])->group(function () {

    Route::get('/penyewa/dashboard', [PenyewaDashboard::class, 'index'])
        ->name('penyewa.dashboard');

    // mencari & booking lapangan
    Route::get('/penyewa/cari-lapangan', [BookingController::class, 'cariLapangan'])
        ->name('penyewa.cari');

    Route::post('/penyewa/booking', [BookingController::class, 'store'])
        ->name('penyewa.booking.store');

    // lihat status booking
    Route::get('/penyewa/riwayat-booking', [BookingController::class, 'riwayat'])
        ->name('penyewa.booking.riwayat');
});
