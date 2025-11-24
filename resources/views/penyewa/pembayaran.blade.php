@extends('layouts.app')

@section('title', 'Konfirmasi Booking')

@section('content')
{{-- Kontainer utama, d-flex sudah benar untuk centering vertikal dan horizontal --}}
<div class="flex items-center justify-center min-h-screen">
    {{-- Wrapper untuk mengatur lebar maksimum card --}}
    <div class="w-full max-w-sm p-4 md:p-6 lg:max-w-md"> 

        <!-- ... Bagian Detail Lapangan di sini ... -->

        {{-- Form Pembayaran --}}
        {{-- PASTIKAN action-nya BENAR menunjuk ke rute POST /pembayaran --}}
        <form action="{{ route('penyewa.booking.pembayaran') }}" method="POST" class="mt-8">
            @csrf

            {{-- Hidden Input yang dikirimkan ke Controller pembayaran --}}
            <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam" value="{{ $jam }}">
            <input type="hidden" name="jam_mulai" value="{{ $jam_mulai }}">
            <input type="hidden" name="jam_selesai" value="{{ $jam_selesai }}">
            <input type="hidden" name="total" value="{{ $total }}">

            <button type="submit" class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Lanjut ke Pembayaran
            </button>
        </form>

    </div>
</div>
@endsection