@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Dashboard Admin</h1>
            <p class="text-slate-600">Ringkasan statistik sistem dan menu cepat.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Card Total User -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total User Aktif</p>
                    <h2 class="text-3xl font-bold text-slate-900">{{ $totalUser }}</h2>
                    <p class="text-xs text-slate-400 mt-2">Termasuk Admin, Penyedia, Penyewa</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>

            <!-- Card Total Lapangan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Lapangan Terdaftar</p>
                    <h2 class="text-3xl font-bold text-slate-900">{{ $totalLapangan }}</h2>
                    <p class="text-xs text-slate-400 mt-2">Lapangan futsal, badminton, dll.</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Menu Actions -->
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Menu Kelola Sistem</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Tombol User Management -->
            <a href="{{ route('admin.usermanajemen') }}" class="group relative flex items-center gap-4 p-6 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:shadow-md transition-all duration-200">
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

            <!-- Tombol Validasi Lapangan -->
            <a href="#" class="group relative flex items-center gap-4 p-6 bg-white border border-slate-200 rounded-xl hover:border-emerald-500 hover:shadow-md transition-all duration-200">
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