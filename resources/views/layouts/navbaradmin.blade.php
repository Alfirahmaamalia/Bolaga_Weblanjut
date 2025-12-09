<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bolaga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="bg-gradient-to-r from-indigo-600 via-sky-600 to-emerald-500 border-b shadow-sm text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" 
               class="font-serif text-2xl font-bold tracking-tight flex items-center gap-3">
                Bolaga
                <span class="text-sm font-medium text-white/90 ml-1">Admin</span>
            </a>

            <!-- Mobile Button -->
            <button id="mobileBtn" aria-label="Toggle menu" class="md:hidden text-white text-2xl focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <!-- Menu -->
            <div id="navbarMenu" class="hidden md:flex gap-6 items-center">

                <!-- Kelola User -->
                <a href="{{ route('admin.usermanajemen') }}"
                   class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.usermanajemen') ? 'bg-white/20 text-white font-semibold' : 'text-white hover:bg-white/10' }}">
                    User
                </a>

                <!-- Validasi Lapangan -->
                <a href="{{ route('admin.validasilapangan') }}"
                   class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.validasilapangan') ? 'bg-white/20 text-white font-semibold' : 'text-white hover:bg-white/10' }}">
                    Lapangan
                </a>

                <!-- Beranda -->
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white font-semibold' : 'text-white hover:bg-white/10' }}">
                    Beranda
                </a>

                <!-- Profile Dropdown -->
                <div class="relative flex items-center gap-3">
                    <div class="text-right mr-2 hidden sm:block">
                        <div class="text-sm font-semibold">{{ Auth::user()->name ?? Auth::user()->email }}</div>
                        <div class="text-xs text-white/80">Administrator</div>
                    </div>
                    <div class="relative">
                        <button id="profileBtn" class="flex items-center ring-1 ring-white/10 rounded-full p-0.5">
                            <img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset('images/default-profile.png') }}"
                                 onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                                 class="w-10 h-10 rounded-full border object-cover cursor-pointer">
                        </button>

                        <!-- Dropdown -->
                        <div id="profileMenu"
                             class="absolute right-0 mt-2 w-44 bg-white shadow-lg border rounded-lg hidden">

                            <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100 text-black">
                                Profil Saya
                            </a>

                            <hr class="my-1">

                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 font-semibold">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden pb-4 space-y-2">

                <a href="{{ route('admin.usermanajemen') }}"
                    class="block px-3 py-2 text-white hover:bg-white/10 rounded-lg {{ request()->routeIs('admin.usermanajemen') ? 'font-semibold text-white' : '' }}">
                User
            </a>

                <a href="{{ route('admin.validasilapangan') }}"
                    class="block px-3 py-2 text-white hover:bg-white/10 rounded-lg {{ request()->routeIs('admin.validasilapangan') ? 'font-semibold text-white' : '' }}">
                Lapangan
            </a>

                <a href="{{ route('admin.dashboard') }}"
                    class="block px-3 py-2 text-white hover:bg-white/10 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'font-semibold text-white' : '' }}">
                Beranda
            </a>

            <!-- Profile -->
            <div class="px-2 py-2 border-t border-white/5 pt-3">
                <a href="{{ route('profil') }}" class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg">Profil Saya</a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-red-400 hover:bg-red-500/20 rounded-lg font-semibold">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>


<!-- CONTENT -->
<div class="py-6">
    @yield('content')
</div>


@stack('scripts')

<script>
    // Toggle dropdown profile (safe guards if elements missing)
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', (e) => {
            if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    }

    // Mobile toggle (safe)
    const mobileBtn = document.getElementById('mobileBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>

</body>
</html>
