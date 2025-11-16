<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyewa - Bolaga</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

<!-- ========================= NAVBAR ========================= -->
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



<!-- ========================= MAIN CONTENT ========================= -->
<main class="container py-5">

    <!-- Section Title -->
    <section class="text-center mb-5">
        <h1 class="fw-bold fs-2">CARI LAPANGAN OLAHRAGA</h1>
        <p class="text-muted">Temukan lapangan terbaik sesuai kebutuhanmu</p>
    </section>


    <!-- ========================= FILTER BAR ========================= -->
    <section class="bg-white border rounded-4 p-4 shadow-sm mb-5">

        <form method="GET">

            <div class="row g-4">

                <!-- Jenis Olahraga -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Olahraga</label>
                    <select class="form-select">
                        <option>Semua</option>
                        <option>Futsal</option>
                        <option>Badminton</option>
                        <option>Basket</option>
                        <option>Voli</option>
                        <option>Tenis</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Lokasi</label>
                    <select class="form-select">
                        <option>Semua Lokasi</option>
                        <option>Jakarta</option>
                        <option>Bandung</option>
                        <option>Surabaya</option>
                        <option>Yogyakarta</option>
                    </select>
                </div>

                <!-- Rentang Harga -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Rentang Harga</label>
                    <select class="form-select">
                        <option>Semua Harga</option>
                        <option><= Rp100.000</option>
                        <option>Rp100.000 - Rp250.000</option>
                        <option>>= Rp250.000</option>
                    </select>
                </div>

                <!-- Tombol Cari -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cari</label>
                    <button class="btn btn-dark w-100 d-flex justify-content-center align-items-center gap-2">
                        Cari Lapangan
                        <i class="bi bi-search"></i>
                    </button>
                </div>

            </div>

        </form>

    </section>


    <!-- ========================= GRID LAPANGAN ========================= -->
    <section class="row g-4">

        @foreach($items as $item)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-4">

                <!-- Gambar -->
                <div class="ratio ratio-4x3">
                    <img src="{{ $item['gambar'] }}"
                        class="card-img-top object-fit-cover"
                        onerror="this.src='https://picsum.photos/600/400?random={{ rand(1,9999) }}'">
                </div>

                <div class="card-body">

                    <!-- Jenis + Rating -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge text-dark px-3 py-2" style="background-color:#FDE68A;">
                            {{ $item['jenis'] }}
                        </span>

                        <span class="text-warning d-flex align-items-center gap-1">
                            <i class="bi bi-star-fill"></i> 4.7
                        </span>
                    </div>

                    <!-- Nama -->
                    <h5 class="fw-bold mb-2">{{ $item['nama'] }}</h5>

                    <!-- Lokasi -->
                    <p class="text-muted small d-flex align-items-center gap-1 mb-2">
                        <i class="bi bi-geo-alt"></i> {{ $item['lokasi'] }}
                    </p>

                    <!-- Fasilitas -->
                    <p class="text-muted small mb-1">Fasilitas:</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($item['fasilitas'] as $f)
                        <span class="badge rounded-pill border border-success text-success"
                              style="background-color:#ECFDF5; font-size:12px;">
                            {{ $f }}
                        </span>
                        @endforeach
                    </div>

                    <!-- Harga + Booking -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold">
                            Rp{{ number_format($item['harga'],0,',','.') }}
                            <span class="text-muted small">/jam</span>
                        </div>

                        <a href="{{ route('penyewa.lapangan.detail', $item['id']) }}"
   						class="px-4 py-2 bg-green-600 text-white rounded-full text-xs hover:bg-green-700">
                            Booking
                        </a>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

    </section>

</main>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
