@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-6">

    <!-- Section Title -->
    <section class="mb-6">
        <h1 class="text-3xl font-bold">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi profil Anda</p>
    </section>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        
        <!-- Profile Picture -->
        <div class="flex justify-center mb-6">
            <img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset('images/default-profile.png') }}"
                 onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                 class="w-32 h-32 rounded-full border-4 border-green-600 object-cover">
        </div>

        <!-- User Info -->
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Profile Photo Upload -->
            <div>
                <label class="block font-semibold mb-2">Foto Profil</label>
                <input type="file" name="foto" accept="image/*"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                @error('foto')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label class="block font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ $user->nama }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       required>
                @error('nama')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email (Read-only) -->
            <div>
                <label class="block font-semibold mb-2">Email</label>
                <input type="email" value="{{ $user->email }}"
                       class="w-full border rounded-lg px-3 py-2 bg-gray-100 focus:outline-none"
                       disabled>
            </div>

            <!-- Phone -->
            <div>
                <label class="block font-semibold mb-2">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ $user->phone ?? '' }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       placeholder="Masukkan nomor telepon">
                @error('phone')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role (Read-only) -->
            <div>
                <label class="block font-semibold mb-2">Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}"
                       class="w-full border rounded-lg px-3 py-2 bg-gray-100 focus:outline-none"
                       disabled>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">
                    Simpan Perubahan
                </button>
                <a href="{{ Auth::user()->role === 'penyedia' ? route('penyedia.dashboard') : route('penyewa.dashboard') }}"
                   class="flex-1 bg-gray-600 text-white py-2 rounded-lg font-semibold hover:bg-gray-700 text-center">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
