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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Sewa / Jam</label>
                            
                            <input type="text" 
                                id="harga_display" 
                                class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Contoh: Rp 150.000"
                                required>

                            <input type="hidden" 
                                name="harga_perjam" 
                                id="harga_actual" 
                                value="{{ old('harga_perjam') }}">
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

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Bukti Kepemilikan (PDF)</h3>
                    
                    <div class="relative w-full">
                        
                        <input id="bukti_kepemilikan" 
                            name="bukti_kepemilikan" 
                            type="file" 
                            accept="application/pdf" 
                            class="hidden" 
                            required 
                            onchange="handlePdfUpload(this)">

                        <div id="pdf-placeholder" 
                            onclick="document.getElementById('bukti_kepemilikan').click()"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center text-center hover:bg-gray-50 cursor-pointer transition h-48">
                            
                            <div class="p-4 bg-green-50 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Klik untuk upload Bukti Kepemilikan</p>
                            <p class="text-xs text-gray-500 mt-1">Format PDF (Maks. 2MB)</p>
                        </div>

                        <div id="pdf-preview-container" class="hidden border-2 border-green-500 border-dashed rounded-lg p-6 flex-col items-center justify-center text-center bg-green-50 h-48 relative">
                            
                            <svg class="w-12 h-12 text-red-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>

                            <a id="pdf-preview-link" href="#" target="_blank" class="text-lg font-bold text-blue-700 hover:text-blue-900 hover:underline mb-1 truncate max-w-xs z-10 relative">
                                nama_file.pdf
                            </a>
                            
                            <p class="text-xs text-gray-500 mb-4">(Klik nama file untuk melihat isi)</p>

                            <div class="flex gap-2 z-10 relative">
                                <button type="button" onclick="document.getElementById('bukti_kepemilikan').click()" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-100 shadow-sm">
                                    Ganti File
                                </button>
                                <button type="button" onclick="resetPdfUpload()" class="px-3 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200 shadow-sm">
                                    Hapus
                                </button>
                            </div>

                            <div class="absolute inset-0 opacity-10 bg-green-200 rounded-lg pointer-events-none"></div>
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
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        const container = document.getElementById('file-name-container');
        
        if (input.files && input.files.length > 0) {
            // Ambil nama file
            display.textContent = input.files[0].name;
            // Tampilkan container
            container.classList.remove('hidden');
        } else {
            // Sembunyikan jika batal pilih
            container.classList.add('hidden');
        }
    }

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

    function handlePdfUpload(input) {
        const placeholder = document.getElementById('pdf-placeholder');
        const previewContainer = document.getElementById('pdf-preview-container');
        const previewLink = document.getElementById('pdf-preview-link');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validasi tipe file harus PDF
            if (file.type !== 'application/pdf') {
                alert('Mohon upload file dengan format PDF.');
                input.value = ''; // Reset input
                return;
            }

            // 1. Buat Object URL untuk preview tanpa upload
            const fileURL = URL.createObjectURL(file);

            // 2. Set href link ke URL tersebut
            previewLink.href = fileURL;
            
            // 3. Set teks nama file
            previewLink.textContent = file.name;

            // 4. Ubah tampilan UI (Sembunyikan placeholder, Tampilkan preview)
            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
        }
    }

    function resetPdfUpload() {
        const input = document.getElementById('bukti_kepemilikan');
        const placeholder = document.getElementById('pdf-placeholder');
        const previewContainer = document.getElementById('pdf-preview-container');

        // Reset value input
        input.value = '';

        // Kembalikan tampilan ke awal
        placeholder.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        previewContainer.classList.remove('flex');
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

    // --- 4. LOGIKA FORMAT RUPIAH ---
    const hargaDisplay = document.getElementById('harga_display');
    const hargaActual = document.getElementById('harga_actual');

    // Fungsi format Rupiah
    const formatRupiah = (angka, prefix) => {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // Event Listener saat mengetik
    hargaDisplay.addEventListener('keyup', function(e) {
        // Format tampilan ke user
        this.value = formatRupiah(this.value, 'Rp');
        
        // Simpan angka murni ke input hidden (hapus Rp dan titik)
        // Ini yang akan dikirim ke database
        hargaActual.value = this.value.replace(/[^0-9]/g, '');
    });

    // Inisialisasi nilai awal (jika ada error validasi / old value)
    document.addEventListener("DOMContentLoaded", function() {
        if (hargaActual.value) {
            hargaDisplay.value = formatRupiah(hargaActual.value, 'Rp');
        }
    });

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