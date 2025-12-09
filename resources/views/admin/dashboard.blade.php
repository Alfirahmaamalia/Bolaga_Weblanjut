@extends('layouts.navbaradmin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#f8fafc] to-[#eef2ff] p-8">
    <div class="max-w-7xl mx-auto">

        <div class="relative mb-10 overflow-hidden rounded-2xl p-8 text-white z-20" style="background: linear-gradient(135deg,#6366f1 0%, #06b6d4 100%);">
            <div class="absolute inset-0 opacity-20 pointer-events-none" aria-hidden="true">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 600 200"><defs><linearGradient id="g" x1="0" x2="1"><stop stop-color="#ffffff" stop-opacity="0.3" offset="0"/><stop stop-color="#ffffff" stop-opacity="0.06" offset="1"/></linearGradient></defs><rect width="600" height="200" fill="url(#g)"></rect></svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Dashboard Admin</h1>
                    <p class="mt-2 text-slate-100/90">Ringkasan sistem, statistik penting, dan akses cepat.</p>
                    <div class="mt-4 flex items-center gap-3">
                        <a href="{{ route('admin.usermanajemen') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold">Manajemen User</a>
                        <a href="{{ route('admin.validasilapangan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm">Validasi Lapangan</a>
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="bg-white/10 p-4 rounded-xl backdrop-blur glass-card border-white/10">
                        <p class="text-xs text-white/90">Tanggal</p>
                        <p class="font-semibold text-lg mt-1">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 relative z-10">
            <div class="p-6 rounded-2xl glass-card border border-white/6">
                <p class="text-sm text-slate-600">Total User Aktif</p>
                <div class="mt-3 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-slate-900">{{ $totalUser }}</h2>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">Termasuk Admin, Penyedia, Penyewa</p>
            </div>

            <div class="p-6 rounded-2xl glass-card border border-white/6">
                <p class="text-sm text-slate-600">Total Lapangan Terdaftar</p>
                <div class="mt-3 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-slate-900">{{ $totalLapangan }}</h2>
                    <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">Lapangan futsal, badminton, dan lainnya</p>
            </div>

            <div class="p-6 rounded-2xl glass-card border border-white/6">
                <p class="text-sm text-slate-600">Booking Hari Ini</p>
                <div class="mt-3 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-slate-900">{{ $bookingsToday ?? 0 }}</h2>
                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center text-pink-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">Jumlah booking yang terjadwal hari ini</p>
            </div>

            <div class="p-6 rounded-2xl glass-card border border-white/6">
                <p class="text-sm text-slate-600">Pendapatan (perkiraan)</p>
                <div class="mt-3 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-slate-900">Rp {{ number_format($estimatedRevenue ?? 0, 0, ',', '.') }}</h2>
                    <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2M12 19v2M5.2 6.6l1.4 1.4M17.4 16.4l1.4 1.4M3 12h2M19 12h2M5.2 17.4l1.4-1.4M17.4 7.6l1.4-1.4"/></svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">Estimasi dari data pembayaran terakhir</p>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-900 mb-4">Menu Kelola Sistem</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.usermanajemen') }}" class="group relative flex items-center gap-4 p-6 bg-white rounded-xl hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-200">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">Manajemen User</h4>
                    <p class="text-sm text-slate-500 mt-1">Tambah, edit, atau hapus pengguna sistem.</p>
                </div>
                <div class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.validasilapangan') }}" class="group relative flex items-center gap-4 p-6 bg-white rounded-xl hover:shadow-lg transition-all duration-200">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center group-hover:bg-emerald-600 transition-colors duration-200">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors duration-200">Validasi Lapangan</h4>
                    <p class="text-sm text-slate-500 mt-1">Verifikasi lapangan baru dari penyedia.</p>
                </div>
                <div class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

        </div>

    </div>
</div>
@endsection