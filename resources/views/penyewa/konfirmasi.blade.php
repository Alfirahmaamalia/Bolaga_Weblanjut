@extends('layouts.app')

@section('title', 'Konfirmasi Booking')

@section('content')
<div class="container py-4">

    <a href="{{ url()->previous() }}" class="text-dark text-decoration-none mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="card p-4 shadow-sm">

        <h3 class="fw-bold mb-4">Konfirmasi Booking</h3>

        <p><strong>Lapangan:</strong> {{ $lapangan->nama_lapangan }}</p>
        <p><strong>Tanggal:</strong> {{ $tanggal }}</p>
        <p><strong>Jam:</strong> {{ $jam_mulai }} - {{ $jam_selesai }}</p>
        <p><strong>Harga:</strong> Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}</p>
        <p><strong>Biaya Admin:</strong> Rp{{ number_format($admin,0,',','.') }}</p>

        <h4 class="fw-bold text-success mt-3">
            Total: Rp{{ number_format($total,0,',','.') }}
        </h4>

        <form action="{{ route('penyewa.booking.pembayaran') }}" method="POST">
            @csrf

            <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam" value="{{ $jam }}">
            <input type="hidden" name="jam_mulai" value="{{ $jam_mulai }}">
            <input type="hidden" name="jam_selesai" value="{{ $jam_selesai }}">
            <input type="hidden" name="total" value="{{ $total }}">

            <button class="btn btn-success w-100 mt-3 py-2">
                Lanjut ke Pembayaran
            </button>
        </form>
    </div>

</div>
@endsection
