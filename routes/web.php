<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PenyediaController;

// -------------------------------------------------
// ROOT
// -------------------------------------------------
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'penyedia'
            ? redirect()->route('penyedia.dashboard')
            : redirect()->route('penyewa.dashboard');
    }
    return redirect()->route('login');
});

// -------------------------------------------------
// HALAMAN BERANDA PUBLIK
// -------------------------------------------------
Route::get('/beranda', fn() => view('beranda'))->name('beranda');

// -------------------------------------------------
// LOGIN + REGISTER (Guest Only)
// -------------------------------------------------
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Google Auth
    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');
});

// -------------------------------------------------
// LOGOUT (Harus Login)
// -------------------------------------------------
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// -------------------------------------------------
// RUTE PENYEWA (role: penyewa)
// -------------------------------------------------
Route::middleware(['auth', 'role:penyewa'])->prefix('penyewa')->name('penyewa.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [LapanganController::class, 'dashboard'])->name('dashboard');

    // Detail lapangan
    Route::get('/lapangan/{id}', [LapanganController::class, 'detail'])->name('lapangan.detail');

    // Booking
    Route::get('/booking/konfirmasi', [BookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
    Route::post('/booking/simpan', [BookingController::class, 'simpanBooking'])->name('booking.simpan');

    // Pembayaran
    Route::get('/booking/pembayaran/{booking}', [BookingController::class, 'pembayaran'])->name('booking.pembayaran');
    Route::post('/booking/konfirmasi-pembayaran/{booking}', [BookingController::class, 'konfirmasiPembayaran'])->name('booking.konfirmasi-pembayaran');

    // Pembatalan booking
    Route::post('/booking/batal/{booking}', [BookingController::class, 'batalkanBooking'])->name('booking.batal');

    // Riwayat
    Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');

    // Cek Jadwal
    Route::get('/cek-jadwal', [LapanganController::class, 'cekJadwal'])->name('cek.jadwal');

    // Cek Slot
    Route::get('/cek-slot', [LapanganController::class, 'cekSlot'])->name('cekSlot');
});

// -------------------------------------------------
// RUTE PENYEDIA (role: penyedia)
// -------------------------------------------------
// -------------------------------------------------
// RUTE PENYEDIA (role: penyedia)
// -------------------------------------------------
Route::middleware(['auth', 'role:penyedia'])->prefix('penyedia')->name('penyedia.')->group(function () {

    // Dashboard Penyedia
    Route::get('/dashboard', [PenyediaController::class, 'dashboard'])
        ->name('dashboard');

    // Manajemen Booking
    Route::get('/manajemen-booking', [PenyediaController::class, 'manajemenBooking'])
        ->name('manajemenbooking');

    // Form tambah lapangan
    Route::get('/lapangan/tambah', [LapanganController::class, 'create'])
        ->name('lapangan.create');

    // Kelola Lapangan
    Route::get('/kelola-lapangan', [PenyediaController::class, 'kelolalapangan'])
        ->name('kelolalapangan');

    // CRUD Lapangan
    Route::post('/lapangan', [LapanganController::class, 'store'])
        ->name('lapangan.store');

    Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])
        ->name('lapangan.edit');

    Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])
        ->name('lapangan.update');

    Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])
        ->name('lapangan.destroy');
});
