<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:penyewa,penyedia',
            'terms' => 'required|accepted',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Kata sandi wajib diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.confirmed' => 'Kata sandi tidak cocok',
            'role.required' => 'Pilih tipe akun',
            'role.in' => 'Tipe akun tidak valid',
            'terms.required' => 'Anda harus menyetujui ketentuan layanan',
            'terms.accepted' => 'Anda harus menyetujui ketentuan layanan',
        ]);

        $user = User::create([
            'nama' => explode('@', $request->email)[0], // Default name from email
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Berhasil membuat akun, silahkan login');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Kata sandi wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            if (Auth::user()->role === 'penyedia') {
                return redirect()->intended('/penyedia/dashboard');
            }
            
            return redirect()->intended('/penyewa/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau kata sandi salah'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    public function googleRedirect()
{
    return Socialite::driver('google')->redirect();
}

public function googleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt('google_default_password'),
                'role' => 'penyewa', // default login sebagai penyewa
                'foto' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user);

        return $user->role === 'penyedia'
            ? redirect()->route('penyedia.dashboard')
            : redirect()->route('penyewa.dashboard');

    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Google login gagal.');
    }
}
}

