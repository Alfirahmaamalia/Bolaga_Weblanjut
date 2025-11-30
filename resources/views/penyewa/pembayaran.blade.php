@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">

    <div class="w-full max-w-4xl bg-white shadow-xl rounded-lg border border-gray-100 p-6 md:p-8">

        <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Pembayaran Booking</h3>

        <form action="{{ route('penyewa.booking.konfirmasi-pembayaran', $booking->booking_id) }}" 
            method="POST" 
            enctype="multipart/form-data"
        >
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- ===========================
                     KOLOM KIRI: METODE PEMBAYARAN 
                ============================ --}}
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Pilih Metode Pembayaran</h4>

                    <div class="grid grid-cols-2 gap-3">

                        {{-- Metode Pembayaran --}}
                        @php
                            $metodeList = [
                                ['id' => 'qris', 'nama' => 'QRIS', 'logo' => '/images/logoPembayaran/qris.png'],
                                ['id' => 'bca', 'nama' => 'BCA', 'logo' => '/images/logoPembayaran/bca.png'],
                                ['id' => 'bri', 'nama' => 'BRI', 'logo' => '/images/logoPembayaran/bri.png'],
                                ['id' => 'bni', 'nama' => 'BNI', 'logo' => '/images/logoPembayaran/bni.png'],
                                ['id' => 'mandiri', 'nama' => 'Mandiri', 'logo' => '/images/logoPembayaran/mandiri.png'],
                                ['id' => 'transfer bank', 'nama' => 'Transfer Bank', 'logo' => '/images/logoPembayaran/bank.png'],
                                ['id' => 'kartu kredit', 'nama' => 'Kartu Kredit', 'logo' => '/images/logoPembayaran/creditcard.png'],
                                ['id' => 'dana', 'nama' => 'Dana', 'logo' => '/images/logoPembayaran/dana.png'],
                            ];
                        @endphp

                        @foreach ($metodeList as $m)
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="{{ $m['id'] }}" class="peer hidden" required>
                                
                                <div class="p-3 border rounded-lg bg-white shadow-sm hover:shadow-md 
                                    peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-300 
                                    transition duration-200 flex flex-col items-center">
                                    
                                    <img src="{{ $m['logo'] }}" alt="" class="w-10 h-10 mb-2 object-contain">
                                    <span class="text-sm font-medium text-gray-800">{{ $m['nama'] }}</span>
                                </div>
                            </label>
                        @endforeach

                    </div>
                </div>

                {{-- ===========================
                     KOLOM KANAN: DETAIL PEMBAYARAN 
                ============================ --}}
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Detail Pembayaran</h4>

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
                            <span class="font-semibold text-gray-900">{{ $jam_mulai }} - {{ $jam_selesai }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Durasi:</span>
                            <span class="font-semibold text-gray-900">{{ $durasi }} Jam</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3 text-gray-600">
                        <div class="flex justify-between">
                            <span>Harga / Jam</span>
                            <span>Rp{{ number_format($lapangan->harga_perjam, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subtotal ({{ $durasi }} jam)</span>
                            <span>Rp{{ number_format($lapangan->harga_perjam * $durasi, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Admin</span>
                            <span>Rp{{ number_format($admin, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="flex justify-between items-center mb-5">
                        <span class="text-xl font-extrabold text-gray-900">TOTAL BAYAR</span>
                        <span class="text-2xl font-extrabold text-green-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    {{-- Hidden Input --}}
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="jam_mulai" value="{{ $jam_mulai }}">
                    <input type="hidden" name="jam_selesai" value="{{ $jam_selesai }}">
                    <input type="hidden" name="total" value="{{ $total }}">
                    
                    <hr class="my-6 border-gray-300">

                    {{-- input gambar bukti pembayaran --}}
                    <div class="mt-6">
                        <label class="block font-semibold text-gray-700 mb-2">Upload Bukti Pembayaran</label>

                        <input type="file" 
                            name="bukti_pembayaran"
                            accept="image/*"
                            required
                            class="w-full p-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400" />
                        
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (max 2MB)</p>
                    </div>

                    <button type="submit" class="mt-5 w-full py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700">
                        Konfirmasi dan Bayar
                    </button>
                    
                    <p class="text-sm text-red-500 mt-2 text-center">
                        Pastikan data sudah valid
                    </p>

                </div>

            </div>

        </form>
    </div>

</div>

@endsection
