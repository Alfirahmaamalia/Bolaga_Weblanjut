@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    <a href="{{ route('penyewa.dashboard') }}" 
        class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="w-5 h-5 mr-1 stroke-current" 
            fill="none" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" 
                stroke-width="2" 
                stroke-linecap="round" 
                stroke-linejoin="round" />
        </svg>

        <span>Kembali</span>
    </a>

    <!-- Section Title -->
    <section class="text-center mb-10">
        <h1 class="text-3xl font-bold">RIWAYAT BOOKING</h1>
        <p class="text-gray-600">Lihat daftar penyewaan lapangan yang pernah kamu lakukan</p>
    </section>


    <!-- TABLE WRAPPER (AGAR BISA SCROLL KE KANAN/KIRI) -->
    <div class="bg-white rounded-xl shadow-md p-6 overflow-x-auto">

        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="bg-green-600 text-white text-left border-b">
                    <th class="p-3 font-semibold whitespace-nowrap">No</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Nama Lapangan</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Tanggal Sewa</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Jam Sewa</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Status</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Total Harga</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Bukti Pembayaran</th>
                    <th class="p-3 font-semibold whitespace-nowrap">Tanggal Booking</th>
                    <th class="p-3 font-semibold whitespace-nowrap text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($riwayat as $i => $row)
                    <tr class="border-b hover:bg-gray-50 align-top">
                        <td class="p-3 whitespace-nowrap">{{ $i + 1 }}</td>
                        <td class="p-3 font-semibold min-w-[150px] max-w-[200px] whitespace-normal break-words">
                            {{ $row->lapangan->nama_lapangan }}
                        </td>
                        <td class="p-3 whitespace-nowrap">{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                        <td class="p-3 whitespace-nowrap">
                            {{ date('H:i', strtotime($row->jam_mulai)) }}
                            -
                            {{ date('H:i', strtotime($row->jam_selesai)) }}
                        </td>

                        <td class="p-3 whitespace-nowrap">
                            @if($row->status == 'berhasil')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    {{ $row['status'] }}
                                </span>
                            @elseif($row->status == 'menunggu konfirmasi')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                    {{ $row['status'] }}
                                </span>
                            @elseif($row->status == 'belum bayar')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                    {{ $row['status'] }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    {{ $row['status'] }}
                                </span>
                            @endif
                        </td>

                        <td class="p-3 whitespace-nowrap">
                            Rp{{ number_format($row->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="p-3">
                            @if($row->bukti_pembayaran)
                                <img src="{{ asset($row->bukti_pembayaran) }}" class="w-20 h-20 object-cover">
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-3 whitespace-nowrap">{{ $row->created_at->format('d-m-Y H:i') }}</td>

                        <td class="p-3 whitespace-nowrap text-center">
                            @if($row->status == 'belum bayar')
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('penyewa.booking.pembayaran', $row->booking_id) }}"
                                    class="px-3 py-1 bg-blue-600 text-white text-center rounded-lg text-sm hover:bg-blue-700 transition">
                                        Bayar
                                    </a>

                                    <form id="form-batal-{{ $row->booking_id }}" 
                                        action="{{ route('penyewa.booking.batal', $row->booking_id) }}" 
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        
                                        <button type="button" 
                                                onclick="confirmBatal('{{ $row->booking_id }}')"
                                                class="w-full px-3 py-1 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm hover:bg-red-200 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function confirmBatal(bookingId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Booking yang dibatalkan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',     // Warna merah untuk tombol konfirmasi
            cancelButtonColor: '#3085d6',   // Warna biru untuk tombol batal
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak, Kembali',
            reverseButtons: true            // Posisi tombol dibalik agar lebih natural
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya", cari form berdasarkan ID lalu submit
                document.getElementById('form-batal-' + bookingId).submit();
            }
        });
    }

    // Opsional: Menampilkan notifikasi sukses/gagal dari Controller (Flash Message)
    // Jika kamu menggunakan ->with('success', ...) di controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session("error") }}',
        });
    @endif
</script>
@endsection
