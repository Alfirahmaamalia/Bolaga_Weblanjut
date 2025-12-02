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
            <h1 class="text-3xl font-bold text-slate-900">Edit User</h1>
            <p class="text-slate-600 mt-1">Perbarui informasi user: <span class="font-semibold">{{ $user->nama }}</span></p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                    Form Edit User
                </h2>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-8">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 font-semibold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Terjadi kesalahan:
                        </p>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.update', $user->user_id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Field -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $user->nama) }}" 
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-4 py-3 rounded-lg border @error('nama') border-red-500 @else border-slate-200 @enderror bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150"
                               required>
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 000-1.414l-6.01-6.01a1 1 0 10-1.414 1.414L16.475 12l-5.798 5.798a1 1 0 101.414 1.414l6.01-6.01z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="Masukkan alamat email"
                               class="w-full px-4 py-3 rounded-lg border @error('email') border-red-500 @else border-slate-200 @enderror bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 000-1.414l-6.01-6.01a1 1 0 10-1.414 1.414L16.475 12l-5.798 5.798a1 1 0 101.414 1.414l6.01-6.01z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Role Field -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-900 mb-2">
                            Role / Peran
                        </label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-4 py-3 rounded-lg border @error('role') border-red-500 @else border-slate-200 @enderror bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 appearance-none"
                                required>
                            <option value="">-- Pilih Role --</option>
                            <option value="penyewa" {{ old('role', $user->role) === 'penyewa' ? 'selected' : '' }}>
                                🏠 Penyewa
                            </option>
                            <option value="penyedia" {{ old('role', $user->role) === 'penyedia' ? 'selected' : '' }}>
                                🏢 Penyedia
                            </option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                👤 Admin
                            </option>
                        </select>
                        <style>
                            select {
                                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
                                background-position: right 0.75rem center;
                                background-repeat: no-repeat;
                                background-size: 1.5em 1.5em;
                                padding-right: 2.5rem;
                            }
                        </style>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 000-1.414l-6.01-6.01a1 1 0 10-1.414 1.414L16.475 12l-5.798 5.798a1 1 0 101.414 1.414l6.01-6.01z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                            Password Baru
                            <span class="text-xs font-normal text-slate-500">(opsional, kosongkan jika tidak ingin ubah)</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password baru (minimal 8 karakter)"
                               class="w-full px-4 py-3 rounded-lg border @error('password') border-red-500 @else border-slate-200 @enderror bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 000-1.414l-6.01-6.01a1 1 0 10-1.414 1.414L16.475 12l-5.798 5.798a1 1 0 101.414 1.414l6.01-6.01z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-600">
                            💡 Jika Anda mengubah password, user harus login dengan password baru
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-6 border-t border-slate-200">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-150 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold py-3 rounded-lg transition-colors duration-150 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm flex gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span><strong>Tips:</strong> Perubahan akan berlaku segera setelah Anda menyimpan. User akan memerlukan login kembali jika Anda mengubah passwordnya.</span>
            </p>
        </div>
    </div>
</div>
@endsection
