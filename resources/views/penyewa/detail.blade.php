@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="container py-4">

    <!-- Back Button -->
    <a href="{{ route('penyewa.dashboard') }}" class="text-decoration-none text-dark mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">

        <!-- Foto -->
        <div class="col-md-6">
            <img src="{{ asset($lapangan->foto) }}"
                 class="w-100 rounded shadow-sm"
                 style="height:350px; object-fit:cover;"
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

            <h4 class="fw-semibold mb-3 text-success">
                Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}/jam
            </h4>

            <p class="mb-3">{{ $lapangan->deskripsi }}</p>

            <h6 class="fw-bold mt-3">Fasilitas:</h6>
            <ul class="ps-3">
                @foreach (explode(',', $lapangan->fasilitas) as $fas)
                    <li>{{ trim($fas) }}</li>
                @endforeach
            </ul>

            <a href="#" class="btn btn-success mt-3 px-4 py-2">
                Pilih Jadwal
            </a>

        </div>

    </div>

</div>

@endsection
