<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Bolaga</title>
    <style>
        :root {
            --bg-image: url('{{ asset('images/volly.jpg') }}');
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
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900 px-5 py-2 rounded-md transition">Daftar</a>
                    <a href="{{ route('login') }}" class="bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition">Masuk</a>
                </div>
            </header>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-6 py-12">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Hai! Bejumpa Kembali</h2>

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

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

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
                                autofocus
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

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember" 
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                >
                                <label for="remember" class="ml-2 text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="#" class="text-sm text-green-600 hover:text-green-700">
                                Lupa kata sandi?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-green-600 text-white py-3 rounded-md font-medium hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Masuk
                        </button>
                    </form>

                    <!-- Create Account Button -->
                    <div class="mt-4">
                        <a 
                            href="{{ route('register') }}" 
                            class="w-full block text-center border-2 border-green-600 text-green-600 py-3 rounded-md font-medium hover:bg-green-50 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Buat Akun
                        </a>
                    </div>

                    <!-- Google Login -->
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">atau</span>
                            </div>
                        </div>

                        <a 
                            href="{{ route('google.redirect') }}"
                            class="mt-6 w-full flex items-center justify-center gap-3 border-2 border-gray-300 text-gray-700 py-3 rounded-md font-medium hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Masuk dengan Google
                        </a>
                    </div>
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

