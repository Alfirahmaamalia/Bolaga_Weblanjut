@extends('layouts.app')

@section('title', 'Manajemen Booking')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Judul -->
    <h1 class="text-2xl font-bold mb-1">Manajemen Booking</h1>
    <p class="text-gray-500 mb-6">Kelola semua booking dan reservasi lapangan Anda</p>

    <!-- Statistik -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-3xl font-bold text-blue-600">{{ $total }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Menunggu</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $menunggu }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Dikonfirmasi</p>
            <p class="text-3xl font-bold text-green-600">{{ $dikonfirmasi }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Selesai</p>
            <p class="text-3xl font-bold text-purple-600">{{ $selesai }}</p>
        </div>
    </div>

    <!-- Search + Filter -->
    <div class="bg-white p-4 rounded-xl shadow mb-5">
        <input type="text"
               placeholder="🔍 Cari berdasarkan nama, lapangan, atau nomor telepon..."
               class="w-full border rounded-lg p-2 focus:ring-green-500 focus:border-green-500">
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-3 mb-5">
        <button class="px-4 py-2 bg-green-600 text-white rounded-full text-sm">Semua ({{ $total }})</button>
        <button class="px-4 py-2 bg-yellow-200 text-yellow-700 rounded-full text-sm">Menunggu ({{ $menunggu }})</button>
        <button class="px-4 py-2 bg-green-200 text-green-700 rounded-full text-sm">Dikonfirmasi ({{ $dikonfirmasi }})</button>
        <button class="px-4 py-2 bg-purple-200 text-purple-700 rounded-full text-sm">Selesai ({{ $selesai }})</button>
    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 font-medium">
                <tr>
                    <th class="text-left p-3">Customer</th>
                    <th class="text-left p-3">Lapangan</th>
                    <th class="text-left p-3">Tanggal & Waktu</th>
                    <th class="text-left p-3">Total</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Pembayaran</th>
                    <th class="text-center p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($bookings as $item)
                <tr class="border-b hover:bg-gray-50">

                    <!-- Customer -->
                    <td class="p-3">
                        <p class="font-medium">{{ $item->penyewa->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->penyewa->phone ?? '-' }}</p>
                    </td>

                    <!-- Lapangan -->
                    <td class="p-3">
                        <p class="font-medium">{{ $item->lapangan->nama_lapangan }}</p>
                        <p class="text-xs text-gray-500">{{ $item->lapangan->jenis_olahraga }}</p>
                    </td>

                    <!-- Tanggal dan Jam -->
                    <td class="p-3">
                        <p>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $item->jam_mulai }} - {{ $item->jam_selesai }} <span class="ml-1">({{ $item->durasi }} jam)</span>
                        </p>
                    </td>

                    <!-- Harga -->
                    <td class="p-3 font-semibold text-gray-900">
                        Rp{{ number_format($item->total,0,',','.') }}
                    </td>

                    <!-- Status Booking -->
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($item->status == 'Menunggu') bg-yellow-100 text-yellow-700
                            @elseif($item->status == 'Dikonfirmasi') bg-green-100 text-green-700
                            @elseif($item->status == 'Selesai') bg-purple-100 text-purple-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $item->status }}
                        </span>
                    </td>

                    <!-- Status Pembayaran -->
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($item->status_pembayaran == 'Terverifikasi') bg-green-100 text-green-700
                            @elseif($item->status_pembayaran == 'Menunggu') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $item->status_pembayaran }}
                        </span>
                    </td>

                    <!-- Aksi -->
                    <td class="p-3 text-center flex gap-2 justify-center">

                        <a href="#" title="Detail" class="text-green-600 hover:text-green-800 text-lg">👁</a>

                        @if($item->status == 'Menunggu')
                        <form action="#" method="POST">
                            @csrf
                            <button title="Konfirmasi" class="text-blue-600 hover:text-blue-800 text-lg">✔</button>
                        </form>
                        @endif

                        @if($item->status != 'Selesai')
                        <form action="#" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                            @csrf @method('DELETE')
                            <button title="Batalkan" class="text-red-600 hover:text-red-800 text-lg">✖</button>
                        </form>
                        @endif
                        
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
