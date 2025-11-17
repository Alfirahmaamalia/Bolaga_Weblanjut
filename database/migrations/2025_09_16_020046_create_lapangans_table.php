@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="container py-4">

    <!-- Back -->
    <a href="{{ route('penyewa.dashboard') }}" class="text-dark text-decoration-none mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">

        <!-- Foto -->
        <div class="col-md-6">
            <img src="{{ asset($lapangan->foto) }}"
                 class="w-100 rounded shadow-sm"
                 style="height:350px;object-fit:cover;"
                 onerror="this.src='https://picsum.photos/600/400?random=1'">
        </div>

        <!-- Detail -->
        <div class="col-md-6">

            <h2 class="fw-bold">{{ $lapangan->nama_lapangan }}</h2>

            <p class="text-muted mb-1">
                <i class="bi bi-geo-alt"></i> {{ $lapangan->lokasi }}
            </p>

            <span class="badge bg-warning text-dark mb-3">
                {{ $lapangan->jenis_olahraga }}
            </span>

            <h4 class="fw-semibold text-success mb-3">
                Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}/jam
            </h4>

            <p>{{ $lapangan->deskripsi }}</p>

            <h6 class="fw-bold mt-3">Fasilitas:</h6>
            <ul class="ps-3">
                @foreach ($lapangan->fasilitas as $fas)
                    <li>{{ $fas }}</li>
                @endforeach
            </ul>

            <!-- STATUS -->
            @if ($lapangan->aktif)
                <span class="badge bg-success p-2 px-3 rounded-pill">Lapangan Tersedia</span>
            @else
                <span class="badge bg-danger p-2 px-3 rounded-pill">Lapangan Tidak Tersedia</span>
            @endif


            <!-- FORM BOOKING -->
            <div class="mt-4 p-3 border rounded-4 shadow-sm">

                <form action="{{ route('booking.create') }}" method="POST">
                    @csrf

                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">

                    <label class="fw-semibold mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control mb-3">

                    <label class="fw-semibold mb-1">Jam</label>
                    <select class="form-select mb-3" name="jam">
                        <option>08:00 - 10:00</option>
                        <option>10:00 - 12:00</option>
                        <option>13:00 - 15:00</option>
                        <option>15:00 - 17:00</option>
                        <option>19:00 - 21:00</option>
                    </select>

                    <button class="btn btn-success w-100 py-2 mt-2">
                        Lanjut Ke Pembayaran
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection
