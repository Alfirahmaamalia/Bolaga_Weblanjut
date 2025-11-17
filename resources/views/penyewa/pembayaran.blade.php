@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-4">

    <a href="{{ url()->previous() }}" class="text-dark text-decoration-none mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="card p-4 shadow-sm">

        <h3 class="fw-bold mb-4">Pembayaran</h3>

        <p><strong>Total Dibayar:</strong>
            <span class="text-success fw-bold">
                Rp{{ number_format($data['total'],0,',','.') }}
            </span>
        </p>

        <hr>

        <h5 class="fw-bold">Transfer Ke:</h5>
        <p>
            BRI - 1234 5678 9123  
            <br> a/n Bolaga Indonesia
        </p>

        <hr>

        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" class="form-control mb-3" required>

            <button class="btn btn-primary w-100 py-2">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>

</div>
@endsection
