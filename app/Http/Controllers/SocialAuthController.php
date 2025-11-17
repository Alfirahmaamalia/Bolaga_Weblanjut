<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // create or update user by email
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'nama' => $googleUser->getName() ?? $googleUser->getNickname(),
                'password' => bcrypt(Str::random(24)), // random password
                'role' => 'penyewa', // set default role sesuai kebutuhan
                'foto' => $googleUser->getAvatar(), // optional
            ]
        );

        Auth::login($user, true);

        return redirect()->route('beranda'); // ganti route tujuan setelah login
    }
}