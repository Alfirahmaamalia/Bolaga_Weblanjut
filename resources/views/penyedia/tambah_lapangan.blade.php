@extends('layouts.navbarPenyedia')

@section('title', 'Tambah Lapangan Baru')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Tombol Kembali -->
    <a href="{{ route('penyedia.kelolalapangan') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Lapangan Baru</h1>
            <p class="text-gray-600 mt-1">Lengkapi informasi di bawah ini untuk mendaftarkan lapangan.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Terjadi Kesalahan!</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penyedia.lapangan.store') }}" method="POST" enctype="multipart/form-data" id="mainForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lapangan</label>
                            <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 border-gray-300" placeholder="Contoh: Lapangan Futsal Garuda">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Olahraga</label>
                            <select name="jenis_olahraga" required class="w-full px-4 py-2 border rounded-lg bg-white border-gray-300">
                                <option value="">Pilih Jenis</option>
                                @foreach(['Futsal', 'Badminton', 'Basket', 'Voli', 'Sepak Bola', 'Tenis'] as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_olahraga') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Sewa / Jam (Rp)</label>
                            <input type="number" name="harga_perjam" value="{{ old('harga_perjam') }}" required min="0"
                                class="w-full px-4 py-2 border rounded-lg border-gray-300" placeholder="Contoh: 150000">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" required
                                class="w-full px-4 py-2 border rounded-lg border-gray-300" placeholder="Alamat lengkap lokasi lapangan">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Lapangan</label>
                            <textarea name="deskripsi" rows="3" required
                                class="w-full px-4 py-2 border rounded-lg border-gray-300" placeholder="Jelaskan kondisi lapangan...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Jam Operasional</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold">
                                <tr>
                                    <th class="p-3 w-1/4">Hari</th>
                                    <th class="p-3 w-1/4">Jam Buka</th>
                                    <th class="p-3 w-1/4">Jam Tutup</th>
                                    <th class="p-3 w-1/4 text-center">Libur?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $days = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
                                    $hours = [];
                                    for($i=0; $i<24; $i++) {
                                        $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                                    }
                                @endphp

                                @foreach([1, 2, 3, 4, 5, 6, 0] as $dayIndex) 
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-medium text-gray-800">{{ $days[$dayIndex] }}</td>
                                    
                                    <td class="p-3">
                                        <select name="jadwal[{{ $dayIndex }}][buka]" 
                                                class="jam-input jam-buka w-full border rounded p-2 bg-white focus:ring-green-500 focus:border-green-500" 
                                                required 
                                                onchange="adjustJamTutup(this)">
                                            @foreach($hours as $hour)
                                                <option value="{{ $hour }}" {{ $hour == '08:00' ? 'selected' : '' }}>{{ $hour }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="p-3">
                                        <select name="jadwal[{{ $dayIndex }}][tutup]" 
                                                class="jam-input jam-tutup w-full border rounded p-2 bg-white focus:ring-green-500 focus:border-green-500" 
                                                required>
                                            @foreach($hours as $hour)
                                                <option value="{{ $hour }}" {{ $hour == '22:00' ? 'selected' : '' }}>{{ $hour }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="jadwal[{{ $dayIndex }}][libur]" 
                                            class="w-5 h-5 text-red-600 rounded focus:ring-red-500 cursor-pointer libur-checkbox"
                                            onchange="toggleLibur(this)">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Fasilitas Lapangan</h3>
                    
                    <div id="fasilitas-container" class="space-y-3 mb-4">
                        </div>

                    <button type="button" id="btnAddFasilitas" onclick="addFasilitasInput()" 
                        class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold hover:bg-green-200 transition flex items-center gap-2">
                        <span>+</span> Tambah Fasilitas
                    </button>
                    <p class="text-xs text-gray-500 mt-2">*Maksimal 10 fasilitas. Isi input sebelum menambah baris baru.</p>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Lapangan</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 relative">
                        <input type="file" name="foto" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'fotoPreview')">
                        <div class="space-y-2">
                            <span class="text-4xl">📷</span>
                            <p class="text-sm text-gray-500">Upload Foto</p>
                        </div>
                        <img id="fotoPreview" class="hidden w-full h-48 object-cover rounded-md mt-2">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Data QRIS</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Nama Merchant</label>
                            <input type="text" name="nama_qris" required class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">NMID</label>
                            <input type="text" name="nmid" required class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">QR Code Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-3 text-center relative">
                                <input type="file" name="qrcode_qris" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'qrisPreview')">
                                <p class="text-sm text-green-600">+ Upload QR</p>
                                <img id="qrisPreview" class="hidden w-32 h-32 object-contain mx-auto mt-2">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow hover:bg-green-700 transition">
                    💾 Simpan Semua Data
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // --- 1. LOGIKA PREVIEW GAMBAR ---
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- 2. LOGIKA JAM OPERASIONAL (LIBUR) ---
    function toggleLibur(checkbox) {
        // Cari row terdekat (tr)
        const row = checkbox.closest('tr');
        // Cari semua select di dalam row tersebut
        const selects = row.querySelectorAll('select.jam-input');
        
        if (checkbox.checked) {
            // Jika Libur dicentang, disable dropdown
            selects.forEach(select => {
                select.disabled = true;
                select.classList.add('bg-gray-100', 'text-gray-400');
                select.value = ""; // Reset value (opsional)
            });
        } else {
            // Jika Libur tidak dicentang, enable kembali
            selects.forEach(select => {
                select.disabled = false;
                select.classList.remove('bg-gray-100', 'text-gray-400');
                // Set default value jika kosong
                if(select.name.includes('buka')) select.value = "08:00";
                if(select.name.includes('tutup')) select.value = "22:00";
            });
        }
    }

    // --- 3. LOGIKA FASILITAS DINAMIS ---
    const container = document.getElementById('fasilitas-container');
    const btnAdd = document.getElementById('btnAddFasilitas');
    const MAX_FACILITIES = 10;

    function addFasilitasInput() {
        // Cek jumlah saat ini
        const currentCount = container.children.length;

        if (currentCount >= MAX_FACILITIES) {
            alert("Maksimal 10 fasilitas.");
            return;
        }

        // Validasi: Input terakhir harus terisi sebelum nambah baru
        if (currentCount > 0) {
            const lastInput = container.lastElementChild.querySelector('input');
            if (!lastInput.value.trim()) {
                alert("Harap isi fasilitas sebelumnya terlebih dahulu.");
                lastInput.focus();
                return;
            }
        }

        // Buat elemen div wrapper
        const wrapper = document.createElement('div');
        wrapper.className = "flex items-center gap-2";

        // Buat Input
        const input = document.createElement('input');
        input.type = "text";
        input.name = "fasilitas[]";
        input.placeholder = "Nama Fasilitas (Contoh: Wifi, AC)";
        input.className = "flex-1 px-4 py-2 border rounded-lg focus:ring-green-500 border-gray-300";
        input.required = true;

        // Buat Tombol Hapus (X)
        const btnDelete = document.createElement('button');
        btnDelete.type = "button";
        btnDelete.innerHTML = "🗑";
        btnDelete.className = "px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200";
        btnDelete.onclick = function() {
            wrapper.remove();
            checkButtonState();
        };

        // Gabungkan
        wrapper.appendChild(input);
        wrapper.appendChild(btnDelete);
        container.appendChild(wrapper);

        // Fokus ke input baru
        input.focus();

        checkButtonState();
    }

    function checkButtonState() {
        // Jika sudah mencapai max, sembunyikan tombol tambah
        if (container.children.length >= MAX_FACILITIES) {
            btnAdd.style.display = 'none';
        } else {
            btnAdd.style.display = 'flex';
        }
    }

    function adjustJamTutup(startSelect) {
        // 1. Cari elemen select Jam Tutup di baris yang sama
        const row = startSelect.closest('tr');
        const endSelect = row.querySelector('.jam-tutup');
        
        // 2. Ambil nilai jam buka (integer). Contoh "16:00" -> 16
        const startTime = parseInt(startSelect.value.split(':')[0]);
        
        // 3. Loop semua opsi di Jam Tutup
        // Kita reset dulu, lalu sembunyikan yang tidak valid
        let firstValidOption = null;

        Array.from(endSelect.options).forEach(option => {
            const timeVal = parseInt(option.value.split(':')[0]);

            if (timeVal <= startTime) {
                // Jika jam tutup <= jam buka, sembunyikan/disable
                option.style.display = 'none'; // Sembunyikan visual
                option.disabled = true;        // Disable agar tidak bisa dipilih via keyboard
            } else {
                // Jika valid (lebih besar), tampilkan
                option.style.display = 'block';
                option.disabled = false;
                
                // Simpan opsi valid pertama yang ditemukan
                if (!firstValidOption) firstValidOption = option.value;
            }
        });

        // 4. Validasi Nilai Terpilih Saat Ini
        // Jika nilai Jam Tutup yang sekarang dipilih ternyata tidak valid (lebih kecil dari jam buka baru)
        // Maka otomatis ganti ke opsi valid pertama (Jam Buka + 1 jam)
        const currentEndTime = parseInt(endSelect.value.split(':')[0]);
        if (currentEndTime <= startTime) {
            endSelect.value = firstValidOption;
        }
    }

    // --- INISIALISASI SAAT HALAMAN DIMUAT ---
    // Agar logika ini jalan saat pertama kali buka halaman (untuk default value)
    document.addEventListener("DOMContentLoaded", function() {
        const allStartSelects = document.querySelectorAll('.jam-buka');
        allStartSelects.forEach(select => {
            adjustJamTutup(select);
        });
    });
</script>

@endsection