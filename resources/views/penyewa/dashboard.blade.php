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
        <form method="GET" action="{{ route('penyewa.dashboard') }}" class="grid md:grid-cols-5 gap-6">

            <!-- Pencarian Lapangan -->
            <div>
                <label class="block font-semibold mb-1">Cari Lapangan</label>
                <input type="text" name="search" 
                    value="{{ request('search') }}"
                    placeholder="Nama lapangan..." 
                    class="w-full rounded-lg p-2 bg-gray-100">
            </div>

            <!-- Jenis Olahraga -->
            <div>
                <label class="block font-semibold mb-1">Jenis Olahraga</label>
                <select name="jenis" class="w-full rounded-lg p-2 bg-gray-100">
                    <option value="">Semua</option>
                    <option value="Futsal" {{ request('jenis')=='Futsal'?'selected':'' }}>Futsal</option>
                    <option value="Sepak Bola" {{ request('jenis')=='Sepak Bola'?'selected':'' }}>Sepak Bola</option>
                    <option value="Badminton" {{ request('jenis')=='Badminton'?'selected':'' }}>Badminton</option>
                    <option value="Basket" {{ request('jenis')=='Basket'?'selected':'' }}>Basket</option>
                    <option value="Voli" {{ request('jenis')=='Voli'?'selected':'' }}>Voli</option>
                    <option value="Tenis" {{ request('jenis')=='Tenis'?'selected':'' }}>Tenis</option>
                </select>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block font-semibold mb-1">Lokasi</label>
                <select name="lokasi" class="w-full rounded-lg p-2 bg-gray-100">
                    <option value="">Semua Lokasi</option>
                    <option value="Jakarta" {{ request('lokasi')=='Jakarta'?'selected':'' }}>Jakarta</option>
                    <option value="Bandung" {{ request('lokasi')=='Bandung'?'selected':'' }}>Bandung</option>
                    <option value="Surabaya" {{ request('lokasi')=='Surabaya'?'selected':'' }}>Surabaya</option>
                    <option value="Yogyakarta" {{ request('lokasi')=='Yogyakarta'?'selected':'' }}>Yogyakarta</option>
                    <option value="Semarang" {{ request('lokasi')=='Semarang'?'selected':'' }}>Semarang</option>
                    <option value="Bogor" {{ request('lokasi')=='Bogor'?'selected':'' }}>Bogor</option>
                    <option value="Lampung" {{ request('lokasi')=='Lampung'?'selected':'' }}>Lampung</option>
                </select>
            </div>

            <!-- Rentang Harga -->
            <div>
                <label class="block font-semibold mb-1">Rentang Harga</label>
                <select name="harga" class="w-full rounded-lg p-2 bg-gray-100">
                    <option value="">Semua Harga</option>
                    <option value="<=100" {{ request('harga')=='<=100'?'selected':'' }}><= Rp100.000</option>
                    <option value="100-250" {{ request('harga')=='100-250'?'selected':'' }}>Rp100.000 - Rp250.000</option>
                    <option value=">=250" {{ request('harga')=='>=250'?'selected':'' }}>>= Rp250.000</option>
                </select>
            </div>

            <!-- Submit -->
            <div>
                <label class="block font-semibold mb-1">Cari</label>
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg">
                    Cari Lapangan
                </button>
            </div>

        </form>
    </section>

    <!-- GRID LAPANGAN -->
    <section class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition hover:shadow-lg flex flex-col h-full">

            <img src="{{ asset($item['foto']) }}"
                 class="w-full h-48 object-cover"
                 onerror="this.src='https://picsum.photos/600/400?random={{ rand(1,9999) }}'">

            <div class="p-4 flex flex-col h-full">

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

                <div class="flex justify-between items-center mt-auto">
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
