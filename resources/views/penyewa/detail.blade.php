@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="container py-4">

    <!-- Back -->
    <a href="{{ route('penyewa.dashboard') }}" class="text-decoration-none text-dark mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">

        <!-- ======================== FOTO LAPANGAN ======================== -->
        <div class="col-md-6">
            <img src="{{ asset($lapangan->foto) }}"
                 class="w-100 rounded shadow-sm"
                 style="height: 450px; object-fit: cover;"
                 onerror="this.src='https://picsum.photos/600/400?random=1'">
        </div>

        <!-- ======================== FORM BOOKING ======================== -->
        <div class="col-md-6">

            <div class="p-4 shadow rounded-4 bg-white">
                <h4 class="fw-bold mb-4">Form Booking</h4>

                <!-- ======================== FORM ======================== -->
                <form action="{{ route('penyewa.booking.konfirmasi') }}" method="GET">

                    <!-- Hidden Lapangan ID -->
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">

                    <!-- Nama Lapangan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lapangan</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $lapangan->nama_lapangan }}"
                               readonly>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               class="form-control"
                               required>
                    </div>

                    <!-- Jam -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam</label>
                        <select name="jam" class="form-select" required>
                            <option value="08:00 - 10:00">08.00 - 10.00</option>
                            <option value="10:00 - 12:00">10.00 - 12.00</option>
                            <option value="13:00 - 15:00">13.00 - 15.00</option>
                            <option value="16:00 - 18:00">16.00 - 18.00</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <span class="badge bg-success px-3 py-2">Lapangan Tersedia</span>
                    </div>

                    <!-- Harga -->
                    <div class="p-3 rounded bg-light border">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Harga Per 2 jam</span>
                            <span>Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Admin</span>
                            <span>Rp5.000</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-success">
                                Rp{{ number_format($lapangan->harga_perjam + 5000,0,',','.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn btn-success w-100 mt-4 py-2 fw-semibold">
                        Lanjut ke Konfirmasi Booking
                    </button>

                </form>
            </div>

        </div>

    </div>
</div>

@endsection
