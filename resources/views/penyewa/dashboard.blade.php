@extends('layouts.app')

@section('title', 'Dashboard Penyewa')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Section Title -->
    <section class="text-center mb-10">
        <h1 class="text-3xl font-bold">CARI LAPANGAN OLAHRAGA</h1>
        <p class="text-gray-600">Temukan lapangan terbaik sesuai kebutuhanmu</p>
    </section>

    <!-- FILTER BAR -->
    <section class="bg-white rounded-xl p-6 shadow-md mb-10">
        <form method="GET" class="grid md:grid-cols-4 gap-6">

            <!-- Jenis Olahraga -->
            <div>
                <label class="block font-semibold mb-1">Jenis Olahraga</label>
                <select class="w-full rounded-lg p-2 bg-gray-100 focus:bg-white focus:ring-2 focus:ring-gray-400">
                    <option>Semua</option>
                    <option>Futsal</option>
                    <option>Sepak Bola</option>
                    <option>Badminton</option>
                    <option>Basket</option>
                    <option>Voli</option>
                    <option>Tenis</option>
                </select>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block font-semibold mb-1">Lokasi</label>
                <select class="w-full rounded-lg p-2 bg-gray-100 focus:bg-white focus:ring-2 focus:ring-gray-400">
                    <option>Semua Lokasi</option>
                    <option>Jakarta</option>
                    <option>Bandung</option>
                    <option>Surabaya</option>
                    <option>Yogyakarta</option>
                </select>
            </div>

            <!-- Rentang Harga -->
            <div>
                <label class="block font-semibold mb-1">Rentang Harga</label>
                <select class="w-full rounded-lg p-2 bg-gray-100 focus:bg-white focus:ring-2 focus:ring-gray-400">
                    <option>Semua Harga</option>
                    <option><= Rp100.000</option>
                    <option>Rp100.000 - Rp250.000</option>
                    <option>>= Rp250.000</option>
                </select>
            </div>

            <!-- Cari -->
            <div>
                <label class="block font-semibold mb-1">Cari</label>
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-800">
                    Cari Lapangan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                    </svg>
                </button>
            </div>

        </form>
    </section>

    <!-- GRID LAPANGAN -->
    <section class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition hover:shadow-lg">

            <img src="{{ asset($item['foto']) }}"
                 class="w-full h-48 object-cover"
                 onerror="this.src='https://picsum.photos/600/400?random={{ rand(1,9999) }}'">

            <div class="p-4">

                <!-- Jenis + Rating -->
                <div class="flex justify-between items-center mb-2">
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold">
                        {{ $item['jenis_olahraga'] }}
                    </span>
                    <span class="text-yellow-500 text-sm flex items-center gap-1">
                        ★ 4.7
                    </span>
                </div>

                <h3 class="font-bold text-lg mb-1">{{ $item['nama_lapangan'] }}</h3>

                <p class="text-gray-600 text-sm mb-2 flex items-center gap-1">
                    📍 {{ $item['lokasi'] }}
                </p>

                <p class="text-gray-600 text-sm mb-2 flex items-center gap-1">
                    {{ $item['deskripsi'] }}
                </p>

                <p class="text-sm text-gray-600 mb-1">Fasilitas:</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($item['fasilitas'] as $f)
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                        {{ $f }}
                    </span>
                    @endforeach
                </div>

                <div class="flex justify-between items-center">
                    <p class="font-bold">
                        Rp{{ number_format($item['harga_perjam'],0,',','.') }}
                        <span class="text-sm text-gray-600">/jam</span>
                    </p>

                    <a href="{{ route('penyewa.lapangan.detail', $item['lapangan_id']) }}"
                       class="px-4 py-2 bg-green-600 text-white rounded-full text-xs hover:bg-green-700">
                        Booking
                    </a>
                </div>

            </div>
        </div>
        @endforeach

    </section>

</div>

@endsection
