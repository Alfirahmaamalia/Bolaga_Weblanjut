<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bolaga</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
   
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm py-2">
    <div class="container-fluid">

        {{-- Logo --}}
        <a class="navbar-brand fw-bold fs-4" 
           href="{{ route('penyewa.dashboard') }}"
           style="font-family:'Georgia', serif;">
            Bolaga
        </a>

        {{-- Toggle Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">

            {{-- MENU TEPAT DI SEBELAH KANAN --}}
            <ul class="navbar-nav d-flex align-items-center gap-4">

                {{-- Riwayat --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('penyewa.booking') ? 'fw-semibold' : '' }}"
                       href="{{ route('penyewa.booking') }}">
                        Riwayat
                    </a>
                </li>

                {{-- Beranda --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('penyewa.dashboard') ? 'fw-semibold' : '' }}"
                       href="{{ route('penyewa.dashboard') }}">
                        Beranda
                    </a>
                </li>

                {{-- Kategori --}}
                <li class="nav-item">
                    <a class="nav-link" href="#kategori">Kategori</a>
                </li>

                {{-- Profile Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" role="button" data-bs-toggle="dropdown">

                        <img src="{{ Auth::user()->foto ?? asset('images/default-profile.png') }}"
                             onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                             class="rounded-circle border"
                             width="38" height="38"
                             style="object-fit:cover;">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a class="dropdown-item" href="#">Profil Saya</a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>

    </div>
</nav>

{{-- Bootstrap Icons CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



    <!-- Content -->
    <div class="py-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>