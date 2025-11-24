@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('penyewa.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="grid md:grid-cols-2 gap-8">

        <!-- ======================== FOTO LAPANGAN ======================== -->
        <div>
            <img src="{{ asset($lapangan->foto) }}"
                 class="w-full h-[450px] rounded-xl shadow-md object-cover"
                 onerror="this.src='https://picsum.photos/600/400?random=1'">
        </div>

        <!-- ======================== FORM BOOKING ======================== -->
        <div>
            <div class="bg-white p-6 shadow-md rounded-xl">

                <h2 class="text-2xl font-bold mb-4">Form Booking</h2>

                <form action="{{ route('penyewa.booking.konfirmasi') }}" method="GET">

                    <!-- Hidden Lapangan ID -->
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">

                    <!-- Nama Lapangan -->
                    <div class="mb-4">
                        <label class="font-semibold">Lapangan</label>
                        <input type="text"
                               value="{{ $lapangan->nama_lapangan }}"
                               class="w-full p-2 rounded-lg bg-gray-100"
                               readonly>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <label class="font-semibold">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               class="w-full p-2 rounded-lg border"
                               required>
                    </div>

                    <!-- Jam -->
                    <div class="mb-4">
                        <label class="font-semibold">Jam</label>
                        <select name="jam"
                                class="w-full p-2 rounded-lg border"
                                required>
                            <option value="08:00 - 10:00">08.00 - 10.00</option>
                            <option value="10:00 - 12:00">10.00 - 12.00</option>
                            <option value="13:00 - 15:00">13.00 - 15.00</option>
                            <option value="16:00 - 18:00">16.00 - 18.00</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            Lapangan Tersedia
                        </span>
                    </div>

                    <!-- Harga -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex justify-between mb-1">
                            <span>Harga Per 2 jam</span>
                            <span>Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}</span>
                        </div>

                        <div class="flex justify-between mb-1">
                            <span>Admin</span>
                            <span>Rp5.000</span>
                        </div>

                        <hr class="my-2">

                        <div class="flex justify-between font-semibold">
                            <span>Total</span>
                            <span class="text-green-600">
                                Rp{{ number_format($lapangan->harga_perjam + 5000,0,',','.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                            class="w-full mt-5 bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                        Lanjut ke Konfirmasi Booking
                    </button>

                </form>
            </div>
        </div>

    </div>

</div>

@endsection
