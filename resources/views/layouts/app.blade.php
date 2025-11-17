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
<nav class="w-100 border-bottom bg-white" style="height:65px;">
    <div class="container-fluid d-flex align-items-center justify-content-between h-100 px-4">

        {{-- Logo --}}
        <a href="{{ route('penyewa.dashboard') }}" 
           class="fw-bold fs-4 text-dark"
           style="font-family: 'Georgia', serif;">
            Bolaga
        </a>

        {{-- Middle Menu --}}
        <div class="d-flex align-items-center gap-4">

            {{-- Search Icon --}}
            <a href="#" class="text-dark" style="font-size:18px;">
                <i class="bi bi-search"></i>
            </a>

            <a href="{{ route('penyewa.booking') }}" class="text-dark text-decoration-none">
                Riwayat
            </a>

            <a href="{{ route('penyewa.dashboard') }}" class="text-dark text-decoration-none">
                Beranda
            </a>

            <a href="#kategori" class="text-dark text-decoration-none">
                Kategori
            </a>
        </div>

        {{-- Profile Picture Dropdown --}}
        <div class="dropdown">
            <a class="d-flex align-items-center dropdown-toggle" 
               href="#" role="button" data-bs-toggle="dropdown">
               
                <img src="{{ Auth::user()->foto ?? asset('images/default-profile.png') }}"
                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                     class="rounded-circle"
                     width="38" height="38"
                     style="object-fit: cover;">
            </a>

            <ul class="dropdown-menu dropdown-menu-end mt-2">
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