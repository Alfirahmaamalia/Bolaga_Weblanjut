<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bolaga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <a href="{{ route('penyewa.dashboard') }}" 
               class="font-serif text-2xl font-bold">
                Bolaga
            </a>

            <!-- Mobile Button -->
            <button id="mobileBtn" class="md:hidden text-gray-700 text-2xl focus:outline-none">
                ☰
            </button>

            <!-- Menu -->
            <div id="navbarMenu" class="hidden md:flex gap-6 items-center">

                <!-- Riwayat -->
                <a href="{{ route('penyewa.booking') }}"
                   class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('penyewa.booking') ? 'font-semibold text-blue-600' : '' }}">
                    Riwayat
                </a>

                <!-- Beranda -->
                <a href="{{ route('penyewa.dashboard') }}"
                   class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('penyewa.dashboard') ? 'font-semibold text-blue-600' : '' }}">
                    Beranda
                </a>

                <!-- Kategori -->
                <a href="#kategori" 
                   class="text-gray-700 hover:text-blue-600">
                    Kategori
                </a>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileBtn" class="flex items-center">
                        <img src="{{ Auth::user()->foto ?? asset('images/default-profile.png') }}"
                             onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                             class="w-10 h-10 rounded-full border object-cover cursor-pointer">
                    </button>

                    <!-- Dropdown -->
                    <div id="profileMenu"
                         class="absolute right-0 mt-2 w-40 bg-white shadow-lg border rounded-lg hidden">

                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                            Profil Saya
                        </a>

                        <hr class="my-1">

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden pb-4 space-y-2">

            <a href="{{ route('penyewa.booking') }}"
               class="block px-2 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('penyewa.booking') ? 'font-semibold text-blue-600' : '' }}">
                Riwayat
            </a>

            <a href="{{ route('penyewa.dashboard') }}"
               class="block px-2 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('penyewa.dashboard') ? 'font-semibold text-blue-600' : '' }}">
                Beranda
            </a>

            <a href="#kategori"
               class="block px-2 py-2 text-gray-700 hover:text-blue-600">
                Kategori
            </a>

            <!-- Profile -->
            <div class="px-2 py-2">
                <a href="#" class="block py-1">Profil Saya</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-red-600 hover:text-red-700">Logout</button>
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
    // Toggle dropdown profile
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', (e) => {
        if (!profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // Mobile toggle
    const mobileBtn = document.getElementById('mobileBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

</body>
</html>
