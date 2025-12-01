@extends('layouts.navbarPenyedia')

@section('title', 'Dashboard Penyedia')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Penyedia</h1>
        <p class="text-gray-600">Ringkasan aktivitas lapangan yang Anda kelola</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Total Lapangan -->
        <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-green-500">
            <h3 class="text-sm font-semibold text-gray-500">Total Lapangan</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLapangan ?? 0 }}</p>
        </div>

        <!-- Booking Aktif -->
        <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-blue-500">
            <h3 class="text-sm font-semibold text-gray-500">Booking Aktif</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $bookingAktif ?? 0 }}</p>
        </div>

        <!-- Pendapatan -->
        <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-yellow-500">
            <h3 class="text-sm font-semibold text-gray-500">Pendapatan Bulan Ini</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                Rp{{ number_format($pendapatan ?? 0,0,',','.') }}
            </p>
        </div>
    </div>

    <!-- Shortcut Buttons -->
    <div class="grid md:grid-cols-2 gap-6">

        <!-- Kelola Lapangan -->
        <a href="{{ route('penyedia.kelolalapangan') }}"
            class="bg-green-600 hover:bg-green-700 shadow-md p-6 rounded-xl 
                   text-white font-semibold flex justify-between items-center">
            <span>Kelola Lapangan</span>
            <span class="text-2xl">⚽</span>
        </a>

        <!-- Manajemen Booking -->
        <a href="{{ route('penyedia.manajemenbooking') ?? '#' }}"
            class="bg-blue-600 hover:bg-blue-700 shadow-md p-6 rounded-xl 
                   text-white font-semibold flex justify-between items-center">
            <span>Manajemen Booking</span>
            <span class="text-2xl">📅</span>
        </a>

    </div>

</div>

@endsection
