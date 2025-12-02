@extends('layouts.navbaradmin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 flex items-center gap-2 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke User Management
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Hapus User</h1>
        </div>

        <!-- Warning Card -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Konfirmasi Penghapusan User
                </h2>
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <!-- Alert Box -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">
                        <span class="inline-block mr-2">⚠️</span>
                        Anda akan menghapus user berikut secara permanen
                    </p>
                </div>

                <!-- User Information -->
                <div class="bg-slate-50 rounded-lg p-6 mb-6 space-y-4">
                    <div class="flex items-start justify-between pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-600">Nama</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $user->nama }}</span>
                    </div>
                    <div class="flex items-start justify-between pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-600">Email</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-slate-600">Role</span>
                        <div>
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    <span class="w-2 h-2 bg-purple-600 rounded-full mr-2"></span>
                                    Admin
                                </span>
                            @elseif($user->role === 'penyedia')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                    Penyedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                                    Penyewa
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Danger Alert -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                    <p class="text-red-800 text-sm">
                        <span class="font-semibold">🔴 Perhatian Penting:</span> Tindakan ini <span class="font-bold">tidak dapat dibatalkan</span>. Semua data pengguna termasuk booking dan pembayaran akan dihapus secara permanen dari sistem.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <form action="{{ route('admin.destroy', $user->user_id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-colors duration-150 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Ya, Hapus User
                        </button>
                    </form>
                    <a href="{{ route('admin.dashboard') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold py-3 rounded-lg transition-colors duration-150 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm flex gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span>Jika Anda yakin ingin menghapus user ini, silakan klik tombol "Ya, Hapus User" di atas.</span>
            </p>
        </div>
    </div>
</div>
@endsection
