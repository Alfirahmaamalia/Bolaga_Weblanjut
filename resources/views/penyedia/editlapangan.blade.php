@extends('layouts.navbarPenyedia')

@section('title', 'Edit Lapangan')

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
            <h1 class="text-3xl font-bold text-gray-800">Edit Lapangan</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi lapangan Anda.</p>
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

    <form action="{{ route('penyedia.lapangan.update', $lapangan->lapangan_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lapangan</label>
                            <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 border-gray-300">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Olahraga</label>
                            <select name="jenis_olahraga" required class="w-full px-4 py-2 border rounded-lg bg-white border-gray-300">
                                @foreach(['Futsal', 'Badminton', 'Basket', 'Voli', 'Sepak Bola', 'Tenis'] as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_olahraga', $lapangan->jenis_olahraga) == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Sewa / Jam (Rp)</label>
                            <input type="number" name="harga_perjam" value="{{ old('harga_perjam', $lapangan->harga_perjam) }}" required min="0"
                                class="w-full px-4 py-2 border rounded-lg border-gray-300">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $lapangan->lokasi) }}" required
                                class="w-full px-4 py-2 border rounded-lg border-gray-300">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Lapangan</label>
                            <textarea name="deskripsi" rows="3" required
                                class="w-full px-4 py-2 border rounded-lg border-gray-300">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="aktif" value="1" 
                                    class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                                    {{ old('aktif', $lapangan->aktif) ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-gray-700">Status Aktif (Tampilkan lapangan ini)</span>
                            </label>
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
                                    for($i=0; $i<24; $i++) { $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; }
                                @endphp

                                @foreach([1, 2, 3, 4, 5, 6, 0] as $dayIndex)
                                    @php
                                        // Ambil data jadwal dari database untuk hari ini
                                        $jadwal = $lapangan->jam_operasional->where('hari', $dayIndex)->first();
                                        
                                        // Set default value jika data tidak ada
                                        $isLibur = $jadwal ? $jadwal->is_libur : false;
                                        $jamBuka = $jadwal ? \Carbon\Carbon::parse($jadwal->jam_buka)->format('H:i') : '08:00';
                                        $jamTutup = $jadwal ? \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H:i') : '22:00';
                                    @endphp

                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 font-medium text-gray-800">{{ $days[$dayIndex] }}</td>
                                        
                                        <td class="p-3">
                                            <select name="jadwal[{{ $dayIndex }}][buka]" 
                                                class="jam-input jam-buka w-full border rounded p-2 bg-white focus:ring-green-500" 
                                                required onchange="adjustJamTutup(this)"
                                                {{ $isLibur ? 'disabled' : '' }}>
                                                @foreach($hours as $hour)
                                                    <option value="{{ $hour }}" {{ $hour == $jamBuka ? 'selected' : '' }}>{{ $hour }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="p-3">
                                            <select name="jadwal[{{ $dayIndex }}][tutup]" 
                                                class="jam-input jam-tutup w-full border rounded p-2 bg-white focus:ring-green-500" 
                                                required
                                                {{ $isLibur ? 'disabled' : '' }}>
                                                @foreach($hours as $hour)
                                                    <option value="{{ $hour }}" {{ $hour == $jamTutup ? 'selected' : '' }}>{{ $hour }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="p-3 text-center">
                                            <input type="checkbox" name="jadwal[{{ $dayIndex }}][libur]" 
                                                class="w-5 h-5 text-red-600 rounded focus:ring-red-500 cursor-pointer libur-checkbox"
                                                onchange="toggleLibur(this)"
                                                {{ $isLibur ? 'checked' : '' }}>
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
                    <p class="text-xs text-gray-500 mt-2">*Maksimal 10 fasilitas.</p>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Lapangan</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 relative">
                        <input type="file" name="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'fotoPreview')">
                        <div class="space-y-2 mb-2">
                            <span class="text-4xl">📷</span>
                            <p class="text-xs text-gray-400">Klik untuk ganti foto (Opsional)</p>
                        </div>
                        <img id="fotoPreview" src="{{ asset($lapangan->foto) }}" class="w-full h-48 object-cover rounded-md mt-2 block">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Data QRIS</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Nama Merchant</label>
                            <input type="text" name="nama_qris" value="{{ old('nama_qris', $lapangan->nama_qris) }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">NMID</label>
                            <input type="text" name="nmid" value="{{ old('nmid', $lapangan->nmid) }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">QR Code Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-3 text-center relative">
                                <input type="file" name="qrcode_qris" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event, 'qrisPreview')">
                                <p class="text-xs text-green-600 mb-2">+ Ganti QR (Opsional)</p>
                                <img id="qrisPreview" src="{{ asset($lapangan->qrcode_qris) }}" class="w-32 h-32 object-contain mx-auto mt-2 border rounded">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow hover:bg-green-700 transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // --- 1. PREVIEW GAMBAR ---
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- 2. LOGIKA JAM BUKA/TUTUP & LIBUR ---
    function toggleLibur(checkbox) {
        const row = checkbox.closest('tr');
        const selects = row.querySelectorAll('select.jam-input');
        
        if (checkbox.checked) {
            selects.forEach(select => {
                select.disabled = true;
                select.classList.add('bg-gray-100', 'text-gray-400');
            });
        } else {
            selects.forEach(select => {
                select.disabled = false;
                select.classList.remove('bg-gray-100', 'text-gray-400');
            });
        }
    }

    function adjustJamTutup(startSelect) {
        const row = startSelect.closest('tr');
        const endSelect = row.querySelector('.jam-tutup');
        const startTime = parseInt(startSelect.value.split(':')[0]);
        let firstValidOption = null;

        Array.from(endSelect.options).forEach(option => {
            const timeVal = parseInt(option.value.split(':')[0]);
            if (timeVal <= startTime) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = 'block';
                option.disabled = false;
                if (!firstValidOption) firstValidOption = option.value;
            }
        });

        const currentEndTime = parseInt(endSelect.value.split(':')[0]);
        if (currentEndTime <= startTime) {
            endSelect.value = firstValidOption;
        }
    }

    // --- 3. LOGIKA FASILITAS DINAMIS ---
    const container = document.getElementById('fasilitas-container');
    const btnAdd = document.getElementById('btnAddFasilitas');
    const MAX_FACILITIES = 10;

    // Load Data Lama dari Database (Array PHP -> JSON JS)
    // Gunakan json_decode di controller atau pastikan model sudah cast array
    const existingFasilitas = @json(is_array($lapangan->fasilitas) ? $lapangan->fasilitas : json_decode($lapangan->fasilitas));

    // Render input saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        if (existingFasilitas && existingFasilitas.length > 0) {
            existingFasilitas.forEach(fasilitas => {
                createFasilitasInput(fasilitas);
            });
        } else {
            // Jika kosong, tampilkan 1 input kosong
            // createFasilitasInput(""); 
        }
        
        // Init validasi jam tutup
        document.querySelectorAll('.jam-buka').forEach(select => adjustJamTutup(select));
    });

    function addFasilitasInput() {
        if (container.children.length >= MAX_FACILITIES) {
            alert("Maksimal 10 fasilitas.");
            return;
        }
        createFasilitasInput("");
    }

    function createFasilitasInput(value) {
        const wrapper = document.createElement('div');
        wrapper.className = "flex items-center gap-2";

        const input = document.createElement('input');
        input.type = "text";
        input.name = "fasilitas[]";
        input.value = value;
        input.placeholder = "Nama Fasilitas";
        input.className = "flex-1 px-4 py-2 border rounded-lg focus:ring-green-500 border-gray-300";
        input.required = true;

        const btnDelete = document.createElement('button');
        btnDelete.type = "button";
        btnDelete.innerHTML = "🗑";
        btnDelete.className = "px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200";
        btnDelete.onclick = function() {
            wrapper.remove();
            checkButtonState();
        };

        wrapper.appendChild(input);
        wrapper.appendChild(btnDelete);
        container.appendChild(wrapper);
        checkButtonState();
    }

    function checkButtonState() {
        btnAdd.style.display = (container.children.length >= MAX_FACILITIES) ? 'none' : 'flex';
    }
</script>

@endsection