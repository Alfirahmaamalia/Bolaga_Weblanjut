@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 py-10">

    <div class="w-full max-w-5xl bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">

        <div class="bg-green-600 px-8 py-4">
            <h3 class="text-2xl font-bold text-white">Pembayaran Booking</h3>
            <p class="text-green-100 text-sm">Selesaikan pembayaran untuk mengamankan jadwal Anda</p>
        </div>

        <form action="{{ route('penyewa.booking.konfirmasi-pembayaran', $booking->booking_id) }}" 
            method="POST" 
            enctype="multipart/form-data"
            class="p-6 md:p-8"
        >
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                {{-- ===========================
                     KOLOM KIRI: SCAN QRIS 
                ============================ --}}
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-300">
                    
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Scan QRIS</h4>
                    <p class="text-gray-500 text-sm mb-6 text-center">Buka aplikasi e-wallet atau m-banking Anda dan scan kode di bawah ini.</p>

                    <div class="bg-white p-4 shadow-lg rounded-lg border">
                        <img src="{{ asset($lapangan->qrcode_qris) }}" 
                             alt="Kode QRIS {{ $lapangan->nama_lapangan }}" 
                             class="w-64 h-64 object-contain"
                             onerror="this.src='https://placehold.co/300x300?text=QRIS+Not+Found'">
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-sm font-semibold text-gray-700">NMID / Atas Nama:</p>
                        <p class="text-lg font-bold text-green-700">{{ $lapangan->nmid }} / {{ $lapangan->nama_qris }}</p>
                    </div>
                </div>

                {{-- ===========================
                     KOLOM KANAN: DETAIL & UPLOAD 
                ============================ --}}
                <div class="flex flex-col justify-between">
                    
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rincian Pesanan</h4>

                        <div class="space-y-3 text-sm md:text-base">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lapangan</span>
                                <span class="font-semibold text-gray-900 text-right">{{ $lapangan->nama_lapangan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal</span>
                                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jam Sewa</span>
                                <span class="font-semibold text-gray-900">{{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durasi</span>
                                <span class="font-semibold text-gray-900">{{ $durasi }} Jam</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg mt-6 border border-yellow-100">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Harga Sewa</span>
                                    <span>Rp{{ number_format($lapangan->harga_perjam * $durasi, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Biaya Admin</span>
                                    <span>Rp{{ number_format($admin, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t border-yellow-200 my-2 pt-2 flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-800">TOTAL BAYAR</span>
                                    <span class="text-xl font-extrabold text-green-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <label class="block font-semibold text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        
                        <div class="relative">
                            <input type="file" 
                                name="bukti_pembayaran"
                                accept="image/*"
                                required
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-3 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-green-50 file:text-green-700
                                hover:file:bg-green-100
                                border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none
                                " 
                            />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">*Format: JPG, PNG (Max 2MB)</p>

                        <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <input type="hidden" name="jam_mulai" value="{{ $jam_mulai }}">
                        <input type="hidden" name="jam_selesai" value="{{ $jam_selesai }}">
                        <input type="hidden" name="total" value="{{ $total }}">

                        <button type="submit" class="mt-6 w-full py-4 bg-green-600 text-white font-bold rounded-xl shadow-lg hover:bg-green-700 transition transform hover:-translate-y-1">
                            Konfirmasi Pembayaran
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

@endsection