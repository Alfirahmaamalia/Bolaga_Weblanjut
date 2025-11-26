@extends('layouts.app')

@section('title', 'Konfirmasi Booking')

@section('content')
{{-- Kontainer utama: Flexbox untuk menengahkan secara vertikal dan horizontal --}}
<div class="flex items-center justify-center min-h-screen"> 
    {{-- Wrapper untuk mengatur lebar maksimum card --}}
    <div class="w-full max-w-sm p-4 md:p-6 lg:max-w-md"> 

        {{-- Tombol kembali (rata kiri) --}}
        <div class="mb-4">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Judul --}}
        <h3 class="text-2xl font-extrabold text-gray-900 mb-6 text-left">Konfirmasi Booking</h3>

        {{-- Card Detail Booking (Shadow dan Padding) --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-100">
            <div class="p-6 sm:p-8">

                {{-- Detail Sewa --}}
                <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Sewa</h4>
                <div class="space-y-3 text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Lapangan:</span>
                        <span class="font-semibold text-gray-900">{{ $lapangan->nama_lapangan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Tanggal:</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Jam:</span>
                        <span class="font-semibold text-gray-900">
                            {{ implode(', ', $jam) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Durasi:</span>
                        <span class="font-semibold text-gray-900">{{ $durasi }} Jam</span>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- Rincian Biaya --}}
                <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rincian Biaya</h4>
                <div class="space-y-3 text-gray-600">
                    <div class="flex justify-between">
                        <span>Harga / Jam</span>
                        <span>Rp{{ number_format($lapangan->harga_perjam, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Subtotal ({{ $durasi }} Jam)</span>
                        <span>Rp{{ number_format($lapangan->harga_perjam * $durasi, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Admin</span>
                        <span>Rp{{ number_format($admin, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="my-6 border-gray-300">

                {{-- Total --}}
                <div class="flex justify-between items-center py-2">
                    <span class="text-xl font-extrabold text-gray-900">TOTAL BAYAR</span>
                    <span class="text-2xl font-extrabold text-green-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>


                {{-- Form Pembayaran --}}
                <form action="{{ route('penyewa.booking.simpan') }}" method="POST">
                 @csrf


                    {{-- Hidden Input --}}
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    @foreach ($jam as $j)
                        <input type="hidden" name="jam[]" value="{{ $j }}">
                    @endforeach
                    <input type="hidden" name="total" value="{{ $total }}">

                    <p class="text-sm text-red-500 mb-2">
                        Pastikan data sudah benar
                    </p>

                    <button type="submit" class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Konfirmasi dan Lanjut Pembayaran
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection