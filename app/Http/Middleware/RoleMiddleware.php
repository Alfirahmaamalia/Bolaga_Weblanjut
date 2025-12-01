<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Cek apakah role user SAAT INI sesuai dengan role yang DIMINTA route ($role)
        if ($user->role !== $role) {
            
            // LOGIKA REDIRECT:
            // Jika role tidak cocok, kita cek role aslinya apa, 
            // lalu lempar ke dashboard masing-masing.

            if ($user->role === 'penyedia') {
                return redirect()->route('penyedia.dashboard');
            } 
            
            if ($user->role === 'penyewa') {
                return redirect()->route('penyewa.dashboard');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Opsional: Jika role tidak dikenali sama sekali (default fallback)
            return redirect()->route('login');
        }

        return $next($request);
    }
}