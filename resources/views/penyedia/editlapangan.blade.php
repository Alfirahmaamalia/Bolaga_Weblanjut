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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Lapangan</label>

                            {{-- KONDISI 1: Menunggu Validasi (Kuning) --}}
                            @if($lapangan->status === 'menunggu validasi')
                                <div class="flex items-center gap-3 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-sm">
                                    <svg class="w-6 h-6 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-yellow-800">Menunggu Validasi Admin</p>
                                        <p class="text-xs text-yellow-700 mt-1">
                                            Lapangan ini sedang ditinjau. Anda tidak dapat mengubah status aktif sebelum disetujui.
                                        </p>
                                    </div>
                                </div>
                                {{-- Input dinonaktifkan --}}
                                <input type="checkbox" disabled class="hidden">

                            {{-- KONDISI 2: Ditolak (Merah) - PERUBAHAN DISINI --}}
                            @elseif($lapangan->status === 'ditolak')
                                <div class="flex items-center gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-red-800">Pengajuan Ditolak</p>
                                        <p class="text-xs text-red-700 mt-1">
                                            Lapangan ini ditolak oleh admin. Silakan perbaiki data lalu simpan. Anda tidak dapat mengubah status aktif saat ini.
                                        </p>
                                    </div>
                                </div>
                                {{-- Input dinonaktifkan --}}
                                <input type="checkbox" disabled class="hidden">

                            {{-- KONDISI 3: Aktif / Non-Aktif (Bisa Diubah) --}}
                            @else
                                <div class="flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer" 
                                            {{ $lapangan->status === 'aktif' ? 'checked' : '' }}>
                                        
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            {{ $lapangan->status === 'aktif' ? 'Aktif (Lapangan Ditampilkan)' : 'Non-Aktif (Lapangan Disembunyikan)' }}
                                        </span>
                                    </label>
                                </div>
                            @endif
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

                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Data Kepemilikan</h3>
                    
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti Kepemilikan (PDF)</label>

                    <div class="relative w-full">
                        
                        <input id="bukti_kepemilikan" 
                            name="bukti_kepemilikan" 
                            type="file" 
                            accept="application/pdf" 
                            class="hidden" 
                            onchange="handlePdfUpload(this)">

                        <div id="pdf-placeholder" 
                            onclick="document.getElementById('bukti_kepemilikan').click()"
                            class="{{ $lapangan->bukti_kepemilikan ? 'hidden' : 'flex' }} border-2 border-dashed border-gray-300 rounded-lg p-6 flex-col items-center justify-center text-center hover:bg-gray-50 cursor-pointer transition h-48">
                            
                            <div class="p-4 bg-green-50 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Klik untuk upload Bukti Kepemilikan Baru</p>
                            <p class="text-xs text-gray-500 mt-1">Format PDF (Maks. 2MB)</p>
                        </div>

                        <div id="pdf-preview-container" 
                            class="{{ $lapangan->bukti_kepemilikan ? 'flex' : 'hidden' }} border-2 border-green-500 border-dashed rounded-lg p-6 flex-col items-center justify-center text-center bg-green-50 h-48 relative">
                            
                            <svg class="w-12 h-12 text-red-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>

                            <a id="pdf-preview-link" 
                            href="{{ $lapangan->bukti_kepemilikan ? asset($lapangan->bukti_kepemilikan) : '#' }}" 
                            target="_blank" 
                            class="text-lg font-bold text-blue-700 hover:text-blue-900 hover:underline mb-1 truncate max-w-xs z-10 relative">
                            {{ $lapangan->bukti_kepemilikan ? 'Lihat File Saat Ini' : 'nama_file.pdf' }}
                            </a>
                            
                            <p class="text-xs text-gray-500 mb-4" id="pdf-status-text">
                                {{ $lapangan->bukti_kepemilikan ? '(File tersimpan di server)' : '(Klik nama file untuk preview)' }}
                            </p>

                            <div class="flex gap-2 z-10 relative">
                                <button type="button" onclick="document.getElementById('bukti_kepemilikan').click()" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-100 shadow-sm">
                                    Ganti File
                                </button>
                                <button type="button" onclick="resetPdfUpload()" class="px-3 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200 shadow-sm">
                                    Batal / Reset
                                </button>
                            </div>

                            <div class="absolute inset-0 opacity-10 bg-green-200 rounded-lg pointer-events-none"></div>
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

    // --- VARIABEL UNTUK MENYIMPAN STATUS FILE LAMA (Server Side) ---
    // Jika ada file di database, kita simpan URL-nya di sini
    const originalPdfUrl = "{{ $lapangan->bukti_kepemilikan ? asset($lapangan->bukti_kepemilikan) : '' }}";
    const hasOriginalFile = "{{ $lapangan->bukti_kepemilikan ? 'true' : 'false' }}" === 'true';

    function handlePdfUpload(input) {
        const placeholder = document.getElementById('pdf-placeholder');
        const previewContainer = document.getElementById('pdf-preview-container');
        const previewLink = document.getElementById('pdf-preview-link');
        const statusText = document.getElementById('pdf-status-text');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.type !== 'application/pdf') {
                alert('Mohon upload file dengan format PDF.');
                input.value = ''; 
                return;
            }

            // 1. Buat URL Blob sementara
            const fileURL = URL.createObjectURL(file);

            // 2. Update Tampilan Preview
            previewLink.href = fileURL;
            previewLink.textContent = file.name; // Tampilkan nama file baru
            statusText.textContent = "(File baru dipilih - Belum disimpan)";
            statusText.classList.add('text-green-600', 'font-bold');

            // 3. Toggle Visibility
            placeholder.classList.add('hidden');
            placeholder.classList.remove('flex');
            
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
        }
    }

    function resetPdfUpload() {
        const input = document.getElementById('bukti_kepemilikan');
        const placeholder = document.getElementById('pdf-placeholder');
        const previewContainer = document.getElementById('pdf-preview-container');
        const previewLink = document.getElementById('pdf-preview-link');
        const statusText = document.getElementById('pdf-status-text');

        // 1. Reset Input File
        input.value = '';

        // 2. Cek apakah ada file lama dari database?
        if (hasOriginalFile) {
            // JIKA ADA FILE LAMA: Kembali ke tampilan file lama
            previewLink.href = originalPdfUrl;
            previewLink.textContent = "Lihat File Saat Ini";
            statusText.textContent = "(File tersimpan di server)";
            statusText.classList.remove('text-green-600', 'font-bold');

            // Pastikan container preview tetap muncul
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
            placeholder.classList.add('hidden');
        } else {
            // JIKA TIDAK ADA FILE LAMA: Kembali ke Placeholder kosong
            previewContainer.classList.add('hidden');
            previewContainer.classList.remove('flex');
            
            placeholder.classList.remove('hidden');
            placeholder.classList.add('flex');
        }
    }
</script>

@endsection