<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Bolaga</title>
    <style>
        :root {
            --bg-image: url('{{ asset('images/lapangan.jpg') }}');
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen">
    <div class="flex min-h-screen">
        <!-- Left Panel - Image -->
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative overflow-hidden" style="background-image: var(--bg-image);">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-blue-500/20"></div>
        </div>

        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex flex-col">
            <!-- Header -->
            <header class="flex justify-between items-center p-6">
                <h1 class="text-2xl font-bold text-gray-900">Bolaga</h1>
                <div class="flex items-center gap-4">
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition">Daftar</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-5 py-2 rounded-md transition">Masuk</a>
                </div>
            </header>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-6 py-12">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Buat akunmu</h2>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-4">
                            @foreach($errors->all() as $error)
                                <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm mb-2">
                                    {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Akun</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-green-500 transition {{ old('role') == 'penyewa' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="role" value="penyewa" class="sr-only" {{ old('role') == 'penyewa' ? 'checked' : '' }} required>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Penyewa</div>
                                        <div class="text-sm text-gray-500">Sewa lapangan</div>
                                    </div>
                                    @if(old('role') == 'penyewa')
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </label>
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-green-500 transition {{ old('role') == 'penyedia' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="role" value="penyedia" class="sr-only" {{ old('role') == 'penyedia' ? 'checked' : '' }} required>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Penyedia</div>
                                        <div class="text-sm text-gray-500">Sewakan lapangan</div>
                                    </div>
                                    @if(old('role') == 'penyedia')
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Masukkan email" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                required
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Masukkan kata sandi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition pr-10"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                >
                                    <svg id="password-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Masukkan kata sandi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition pr-10"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                >
                                    <svg id="password_confirmation-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="terms" 
                                name="terms" 
                                value="1"
                                class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                required
                            >
                            <label for="terms" class="ml-2 text-sm text-gray-700">
                                Saya menyetujui semua ketentuan layanan dan kebijakan privasi
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-green-600 text-white py-3 rounded-md font-medium hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Selanjutnya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                field.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>
</body>
</html>

