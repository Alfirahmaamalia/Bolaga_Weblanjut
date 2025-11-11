<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return redirect()->route('login');
// });

Route::get('/', function () {
    if (auth()->check()) {
        // Arahkan ke dashboard sesuai role user
        return auth()->user()->role === 'penyedia'
            ? redirect()->route('penyedia.dashboard')
            : redirect()->route('penyewa.dashboard');
    }

    // Kalau belum login, arahkan ke halaman login
    return redirect()->route('login');
});


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Routes
    Route::get('/penyedia/dashboard', function () {
        return view('penyedia.dashboard');
    })->name('penyedia.dashboard');

    Route::get('/penyewa/dashboard', function () {
        return view('penyewa.dashboard');
    })->name('penyewa.dashboard');
});
