@extends('layouts.navbarPenyedia')

@section('title', 'Manajemen Booking')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="max-w-7xl mx-auto px-4 py-6">

    
    <!-- Tombol Kembali -->
    <a href="{{ route('penyedia.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-1">Manajemen Booking</h1>
    <p class="text-gray-500 mb-6">Kelola semua booking dan reservasi lapangan Anda</p>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    
        {{-- 1. PENDAPATAN --}}
        {{-- Mobile: col-span-2 (Full Width/Sendiri) --}}
        {{-- Desktop: lg:col-span-2 (Setengah lebar, berbagi dengan Total Booking) --}}
        <div class="bg-white p-4 rounded-xl shadow text-center border border-green-100 relative overflow-hidden group col-span-2 lg:col-span-2">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Pendapatan (Bulan Ini)</p>
            <p class="text-xl md:text-2xl font-extrabold text-emerald-600 mt-1">
                Rp{{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
            </p>
        </div>
        
        {{-- 2. TOTAL BOOKING --}}
        {{-- Mobile: col-span-2 (Full Width/Sendiri di baris kedua) --}}
        {{-- Desktop: lg:col-span-2 (Setengah lebar, berbagi dengan Pendapatan) --}}
        <div class="bg-white p-4 rounded-xl shadow text-center col-span-2 lg:col-span-2">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-3xl font-bold text-purple-600">{{ $total }}</p>
        </div>

        {{-- 3. MENUNGGU KONFIRMASI --}}
        {{-- Mobile: Default (col-span-1, sejajar dengan Belum Bayar) --}}
        {{-- Desktop: Default (col-span-1, sejajar berempat di bawah) --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
            <p class="text-3xl font-bold text-blue-500">{{ $menunggu_konfirmasi }}</p>
        </div>

        {{-- 4. BELUM BAYAR --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Belum Bayar</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $belum_bayar }}</p>
        </div>

        {{-- 5. GAGAL --}}
        {{-- Mobile: Default (sejajar dengan Berhasil) --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Gagal</p>
            <p class="text-3xl font-bold text-red-600">{{ $gagal }}</p>
        </div>

        {{-- 6. BERHASIL --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Berhasil</p>
            <p class="text-3xl font-bold text-green-500">{{ $berhasil }}</p>
        </div>
    </div>

    {{-- BAGIAN FILTER --}}
    <div class="bg-white p-4 rounded-xl shadow mb-5">
        <form action="{{ route('penyedia.manajemenbooking') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                {{-- 1. Filter Lapangan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Lapangan</label>
                    <select name="lapangan_id" 
                            class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500 text-sm border">
                        <option value="">Semua Lapangan</option>
                        @foreach($daftar_lapangan as $lap)
                            <option value="{{ $lap->lapangan_id }}" {{ request('lapangan_id') == $lap->id ? 'selected' : '' }}>
                                {{ $lap->nama_lapangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Filter Status --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Booking</label>
                    <select name="status" 
                            class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500 text-sm border">
                        <option value="">Semua Status</option>
                        <option value="menunggu konfirmasi" {{ request('status') == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="belum bayar" {{ request('status') == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="berhasil" {{ request('status') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                {{-- 3. Filter Urutan (Default Terbaru) --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Urutan Waktu</label>
                    <select name="urutan" 
                            class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500 text-sm border">
                        <option value="terbaru" {{ request('urutan', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru (Default)</option>
                        <option value="terlama" {{ request('urutan') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg w-full text-sm transition">
                        Terapkan
                    </button>
                    <a href="{{ route('penyedia.manajemenbooking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm transition text-center">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm min-w-[900px]">
            <thead class="bg-gray-100 text-gray-600 font-medium">
                <tr>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Lapangan</th>
                    
                    <th class="text-left p-3 whitespace-nowrap">Tanggal & Waktu</th>
                    <th class="text-left p-3 whitespace-nowrap">Total</th>
                    
                    <th class="text-left p-3 whitespace-nowrap">Status</th>
                    
                    <th class="text-left p-3 whitespace-nowrap">Pembayaran</th>
                    <th class="text-center p-3 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $item)
                    <tr class="border-b hover:bg-gray-50">
                        
                        <td class="p-3">
                            <p class="font-medium">{{ $item->penyewa->nama ?? 'User Terhapus' }}</p>
                        </td>

                        <td class="p-3">
                            <p class="font-medium">{{ $item->lapangan->nama_lapangan ?? 'Lapangan Terhapus' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->lapangan->jenis_olahraga ?? '-' }}</p>
                        </td>

                        <td class="p-3 whitespace-nowrap">
                            <p>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                <span class="ml-1">({{ $item->durasi }} jam)</span>
                            </p>
                        </td>

                        <td class="p-3 font-semibold text-gray-900 whitespace-nowrap">
                            Rp{{ number_format(($item->total_harga ?? $item->total ?? 0) - 5000, 0, ',', '.') }}
                        </td>

                        <td class="p-3 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->status == 'berhasil') bg-green-100 text-green-700
                                @elseif($item->status == 'gagal') bg-red-100 text-red-700
                                @elseif($item->status == 'belum bayar') bg-yellow-100 text-yellow-700
                                @elseif($item->status == 'menunggu konfirmasi') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">

                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td class="p-3 whitespace-nowrap">
                            @if ($item->bukti_pembayaran)
                                <a href="{{ asset($item->bukti_pembayaran) }}" 
                                target="_blank"
                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
                                Lihat Bukti
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-3 whitespace-nowrap text-center">

                            {{-- STATUS: menunggu konfirmasi --}}
                            @if($item->status == 'menunggu konfirmasi')
                                <div class="flex flex-col items-center gap-2">

                                    <!-- Tombol Konfirmasi -->
                                    <form action="{{ route('penyedia.manajemenbooking.konfirmasi', $item->booking_id) }}" method="POST">
                                        @csrf
                                        <button title="Konfirmasi" 
                                                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md">
                                            Konfirmasi
                                        </button>
                                    </form>

                                    <!-- Tombol Batalkan -->
                                    <form action="{{ route('penyedia.manajemenbooking.batalkan', $item->booking_id) }}" 
                                        method="POST" 
                                        class="form-batalkan">
                                        @csrf 
                                        <button title="Batalkan" 
                                                class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                                            Batalkan
                                        </button>
                                    </form>

                                </div>

                            {{-- STATUS: belum bayar --}}
                            @elseif($item->status == 'belum bayar')
                                <form action="{{ route('penyedia.manajemenbooking.batalkan', $item->booking_id) }}" 
                                    method="POST"
                                    class="form-batalkan">
                                    @csrf 
                                    <button title="Batalkan" 
                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                                         Batalkan
                                    </button>
                                </form>

                            {{-- STATUS lainnya --}}
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500 bg-white">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-4xl mb-2">📭</span>
                                <p class="text-lg font-medium">Data tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.form-batalkan');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah submit form langsung

                Swal.fire({
                    title: 'Batalkan Booking?',
                    text: "Booking yang dibatalkan statusnya akan berubah menjadi Gagal/Batal.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Warna merah untuk tombol konfirmasi
                    cancelButtonColor: '#3085d6', // Warna biru untuk tombol batal
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true // Posisi tombol dibalik agar UX lebih aman
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user klik Ya, submit form secara manual
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection