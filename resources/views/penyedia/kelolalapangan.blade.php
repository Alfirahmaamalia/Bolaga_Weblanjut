@extends('layouts.navbarPenyedia')

@section('title', 'Kelola Lapangan')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('penyedia.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <!-- Header & Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Kelola Lapangan</h1>
            <p class="text-gray-500 text-sm">Tambah, edit, dan kelola lapangan Anda</p>
        </div>
        <a href="{{ route('penyedia.lapangan.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition flex items-center gap-2">
            <span>+</span> Tambah Lapangan
        </a>
    </div>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('penyedia.kelolalapangan') }}" class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center bg-white p-3 rounded-xl shadow gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari nama lapangan atau lokasi..." class="w-full md:w-1/3 border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500">

            <div class="flex gap-2 w-full md:w-auto">
                <select name="kategori" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 w-full md:w-auto cursor-pointer">
                    <option value="Semua Jenis">Semua Jenis</option>
                    @foreach(['Futsal', 'Badminton', 'Basket', 'Voli', 'Sepak Bola', 'Tenis'] as $cat)
                        <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">Cari</button>
            </div>
        </div>
    </form>

    <!-- List Lapangan -->
    @if($lapangan->isEmpty())
        <div class="text-center py-10 bg-white rounded-xl shadow border border-dashed border-gray-300">
            <p class="text-gray-500 text-lg">Belum ada lapangan yang ditemukan.</p>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lapangan as $item)
            <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition flex flex-col h-full border border-gray-100">

                <!-- Gambar & Status -->
                <div class="relative">
                    <img src="{{ asset($item->foto) }}" class="h-44 w-full object-cover" onerror="this.src='https://picsum.photos/600/400?random={{ $loop->index }}'">
                    @php
                        // Menentukan warna badge berdasarkan status string
                        $badgeClass = match($item->status) {
                            'aktif' => 'bg-green-100 text-green-700',              // Hijau
                            'menunggu validasi' => 'bg-yellow-100 text-yellow-700',  // Kuning
                            'ditolak' => 'bg-red-100 text-red-700',                 // Merah (Baru)
                            'non aktif' => 'bg-gray-200 text-gray-700',             // Abu-abu (Baru)
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp

                    <span class="absolute top-2 right-2 px-2 py-1 text-xs font-bold rounded-lg shadow {{ $badgeClass }} capitalize">
                        {{-- Menampilkan status (misal: Menunggu Validasi) --}}
                        {{ $item->status }}
                    </span>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-semibold text-lg leading-tight">{{ $item->nama_lapangan }}</h3>
                    </div>

                    <p class="text-sm text-gray-500 mb-2">{{ $item->jenis_olahraga }}</p>
                    <p class="text-sm text-gray-400 mb-3 flex items-center">📍 {{ Str::limit($item->lokasi, 30) }}</p>

                    <p class="text-green-600 font-bold text-lg">
                        Rp{{ number_format($item->harga_perjam, 0, ',', '.') }} <span class="text-sm text-gray-500 font-normal">/jam</span>
                    </p>

                    <!-- Fasilitas -->
                    <div class="mt-3 mb-4">
                        <p class="text-xs text-gray-400 mb-1">Fasilitas:</p>
                        <div class="flex flex-wrap gap-1">
                            @if(is_array($item->fasilitas))
                                @foreach(array_slice($item->fasilitas, 0, 3) as $f) 
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-1 rounded border">{{ $f }}</span>
                                @endforeach
                                @if(count($item->fasilitas) > 3)
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-1 rounded">+{{ count($item->fasilitas) - 3 }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="mt-auto flex justify-between gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('penyedia.lapangan.edit', $item->lapangan_id) }}"
                           class="flex-1 bg-yellow-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 text-center transition">
                            ✏ Edit
                        </a>

                        <!-- FORM DELETE DENGAN ID UNIK -->
                        <form id="delete-form-{{ $item->lapangan_id }}" 
                              action="{{ route('penyedia.lapangan.destroy', $item->lapangan_id) }}" 
                              method="POST" 
                              class="w-auto">
                            @csrf
                            @method('DELETE')
                            
                            <!-- 
                                PERUBAHAN: 
                                1. type="button" agar tidak langsung submit.
                                2. onclick memanggil fungsi JS confirmDelete
                            -->
                            <button type="button" 
                                    onclick="confirmDelete('{{ $item->lapangan_id }}', '{{ $item->nama_lapangan }}')"
                                    class="w-10 h-full bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                🗑
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>

<!-- SCRIPT SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, namaLapangan) {
        Swal.fire({
            title: 'Hapus Lapangan?',
            text: "Anda akan menghapus permanently: " + namaLapangan + ". Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form berdasarkan ID unik lalu submit manual
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Menampilkan notifikasi sukses dari controller (Redirect back)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>

@endsection