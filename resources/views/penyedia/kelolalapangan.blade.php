@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Kelola Lapangan</h1>
            <p class="text-gray-500 text-sm">Tambah, edit, dan kelola semua lapangan Anda</p>
        </div>
        <a href="{{ route('penyedia.lapangan.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
            + Tambah Lapangan
        </a>
    </div>

    <!-- Filter + Search -->
    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow mb-6">
        <input type="text"
               placeholder="🔍 Cari lapangan..."
               class="w-1/3 border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500">

        <select class="border rounded-lg px-3 py-2">
            <option>Semua Jenis</option>
            <option>Futsal</option>
            <option>Badminton</option>
            <option>Basket</option>
            <option>Voli</option>
            <option>Sepak Bola</option>
            <option>Tenis</option>
        </select>
    </div>

    <!-- List Lapangan -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($lapangan as $item)
        <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">

            <!-- Gambar -->
            <img src="{{ asset($item->foto) }}"
                 class="h-44 w-full object-cover"
                 onerror="this.src='https://picsum.photos/600/400?random={{ rand(100,999) }}'">

            <div class="p-4">

                <!-- Nama + Status -->
                <div class="flex justify-between items-center">
                    <p class="font-semibold text-lg">{{ $item->nama_lapangan }}</p>

                    <span class="px-2 py-1 text-xs font-medium rounded-lg
                        {{ $item->status == 'Tersedia' ? 'bg-green-100 text-green-700' :
                           ($item->status == 'Maintenance' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ $item->status }}
                    </span>
                </div>

                <p class="text-sm text-gray-500 mt-1">{{ $item->jenis_olahraga }}</p>

                <p class="text-green-600 font-bold mt-2">
                    Rp{{ number_format($item->harga_perjam, 0, ',', '.') }}/jam
                </p>

                <p class="text-sm mt-3">Fasilitas:</p>
                <div class="flex flex-wrap gap-1 mt-1">
                    @foreach($item->fasilitas as $f)
                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                        {{ $f }}
                    </span>
                    @endforeach
                </div>

                <!-- Tombol Edit + Hapus -->
                <div class="mt-4 flex justify-between gap-2">
                    <a href="{{ route('penyedia.lapangan.edit', $item->lapangan_id) }}"
                       class="w-full bg-green-600 text-white py-2 rounded-lg text-sm hover:bg-green-700">
                        ✏ Edit
                    </a>

                    <form action="{{ route('penyedia.lapangan.destroy', $item->lapangan_id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="w-10 h-10 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                            🗑
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach

    </div>

</div>

@endsection
