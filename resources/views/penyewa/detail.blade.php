@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('penyewa.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="grid md:grid-cols-2 gap-8">

        <!-- ======================== FOTO LAPANGAN ======================== -->
        <div>
            <img src="{{ asset($lapangan->foto) }}"
                 class="w-full h-[450px] rounded-xl shadow-md object-cover mb-6"
                 onerror="this.src='https://picsum.photos/600/400?random=1'">

            <div class="bg-white p-6 shadow-md rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jam Operasional
                </h3>
                
                <div class="space-y-2">
                    @php
                        // Mapping nama hari
                        $days = [0=>'Minggu', 1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu'];
                        
                        // Sort agar Senin (1) paling atas, Minggu (0) paling bawah
                        $sortedJadwal = $lapangan->jam_operasional->sortBy(function($query){
                            return $query->hari == 0 ? 7 : $query->hari;
                        });
                    @endphp

                    @foreach($sortedJadwal as $jadwal)
                        <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                            <span class="font-medium text-gray-600 w-24">{{ $days[$jadwal->hari] }}</span>
                            
                            @if($jadwal->is_libur)
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs font-bold">Tutup / Libur</span>
                            @else
                                <span class="text-gray-800 font-semibold">
                                    {{ \Carbon\Carbon::parse($jadwal->jam_buka)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H:i') }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ======================== FORM BOOKING ======================== -->
        <div>
            <div class="bg-white p-6 shadow-md rounded-xl">

                <h2 class="text-2xl font-bold mb-4">Form Booking</h2>

                <form action="{{ route('penyewa.booking.konfirmasi') }}" method="GET">


                    <!-- Hidden Lapangan ID -->
                    <input type="hidden" name="lapangan_id" id="lapanganId" value="{{ $lapangan->lapangan_id }}">

                    <!-- Nama Lapangan -->
                    <div class="mb-4">
                        <label class="font-semibold">Lapangan</label>
                        <p class="w-full p-2 rounded-lg bg-gray-100">
                            {{ $lapangan->nama_lapangan }}
                        </p>
                    </div>

                    <!-- Lokasi Lapangan -->
                    <div class="mb-4">
                        <label class="font-semibold">Lokasi</label>
                        <p class="w-full p-2 rounded-lg bg-gray-100">
                            📍{{ $lapangan->lokasi }}
                        </p>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <label class="font-semibold">Tanggal</label>
                        <input type="date"
                            id="tanggal"
                            name="tanggal"
                            class="w-full p-2 rounded-lg border"
                            min="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}"
                            required onchange="cekSlot()">
                    </div>

                    <!-- Jam Mulai -->
                    @php
                        // Jam sekarang + 1 jam (zona waktu Indonesia)
                        $jamMinimal = \Carbon\Carbon::now('Asia/Jakarta')->addHour()->format('H:i');

                        // Daftar jam yang tersedia
                        $daftarJam = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

                        // Hapus jam terakhir (misal 17:00)
                        $daftarJamMulai = array_slice($daftarJam, 0, -1);
                    @endphp
                    <!-- <div class="mb-4">
                        <label class="font-semibold">Jam Mulai</label>
                        <select name="jam_mulai" id="jamMulai" class="w-full p-2 rounded-lg border" required onchange="cekSlot()">
                            <option value="">Pilih Jam Mulai</option>
                            @foreach($daftarJamMulai as $jam)
                                @if($jam >= $jamMinimal)
                                    <option value="{{ $jam }}">{{ $jam }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div> -->
                    <div class="mb-4">
                        <label class="font-semibold">Jam Mulai</label>
                        <select name="jam_mulai" id="jamMulai" class="w-full p-2 rounded-lg border" required>
                            <option value="">Pilih Jam Mulai</option>
                            </select>
                    </div>

                    <!-- Jam Selesai -->
                    <div class="mb-4">
                        <label class="font-semibold">Jam Selesai</label>
                        <select name="jam_selesai" id="jamSelesai" class="w-full p-2 rounded-lg border" required disabled onchange="cekSlot()">
                            <option value="">Pilih Jam Selesai</option>
                        </select>
                    </div>

                    
                    <!-- Hidden input untuk dikirim -->
                    <div id="jamInputs"></div>

                    <!-- Status -->
                    @php $isBooked = $isBooked ?? false; @endphp
                    <div class="mb-4">
                        <span id="statusSlot"
                            class="{{ $isBooked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $isBooked ? 'Lapangan Tidak Tersedia' : 'Lapangan Tersedia' }}
                        </span>
                    </div>

                    <!-- Harga -->
                    <div class="bg-gray-50 p-4 rounded-lg border mb-5">
                        <div class="flex justify-between mb-1">
                            <span>Harga Per jam</span>
                            <span id="hargaPerJam">Rp{{ number_format($lapangan->harga_perjam,0,',','.') }}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Total Jam</span>
                            <span id="totalJam">0</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Admin</span>
                            <span id="adminFee">Rp5.000</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total</span>
                            <span id="totalHarga" class="text-green-600">
                                Rp{{ number_format($lapangan->harga_perjam + 5000,0,',','.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        id="btnSubmit"
                        class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg 
                        shadow-md hover:bg-green-700 transition duration-300 ease-in-out 
                        focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                         Lanjut ke Konfirmasi Booking
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // 1. DATA & VARIABEL GLOBAL
    // ==========================================
    const serverToday = "{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}";
    // Tambahan: Kita butuh jam sekarang untuk validasi booking hari ini
    const serverNowHour = "{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}"; 
    
    const jamMulaiSelect = document.getElementById("jamMulai");
    const jamSelesaiSelect = document.getElementById("jamSelesai");
    const tanggalInput = document.getElementById("tanggal");
    const statusBox = document.getElementById("statusSlot");
    const btn = document.getElementById("btnSubmit");
    const totalJamEl = document.getElementById("totalJam");
    const totalHargaEl = document.getElementById("totalHarga");
    const lapanganIdInput = document.getElementById("lapanganId");

    // Array dinamis untuk menyimpan jam operasional hasil dari database
    let operationalTimes = []; 

    // ==========================================
    // 2. FUNGSI UTAMA: AMBIL JADWAL DARI SERVER
    // ==========================================
    // Menggantikan fungsi 'populateJamMulai' yang lama
    async function fetchJadwal() {
        const tanggal = tanggalInput.value;
        const lapanganId = lapanganIdInput.value;

        if (!tanggal) return;

        // Reset UI saat loading
        jamMulaiSelect.innerHTML = '<option value="">Loading...</option>';
        jamMulaiSelect.disabled = true; // Disable dulu saat loading
        jamSelesaiSelect.innerHTML = '<option value="">Pilih Jam Selesai</option>';
        jamSelesaiSelect.disabled = true;
        
        btn.disabled = true;
        btn.classList.remove("bg-green-600", "hover:bg-green-700");
        btn.classList.add("bg-gray-400", "cursor-not-allowed");

        statusBox.textContent = "Mengecek jadwal...";
        statusBox.className = "bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-semibold";

        try {
            const response = await fetch(`{{ route('penyewa.cek.jadwal') }}?lapangan_id=${lapanganId}&tanggal=${tanggal}`);
            const data = await response.json();

            // Reset opsi jam mulai
            jamMulaiSelect.innerHTML = '<option value="">Pilih Jam Mulai</option>';

            // --- KONDISI 1: LIBUR ---
            if (data.status === 'libur') {
                // HAPUS SWEETALERT (POPUP), GANTI DENGAN STATUS MERAH
                statusBox.textContent = "Tutup / Libur pada tanggal ini";
                statusBox.className = "bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold";
                
                // Pastikan dropdown terkunci agar tidak bisa dipilih
                jamMulaiSelect.disabled = true; 
                return; 
            }

            // --- KONDISI 2: BUKA ---
            // Aktifkan kembali dropdown jam mulai
            jamMulaiSelect.disabled = false;

            // Generate opsi jam
            generateTimeOptions(data.jam_buka, data.jam_tutup, tanggal);
            
            // Ubah status jadi biru (menunggu user pilih jam)
            statusBox.textContent = "Silakan pilih jam main";
            statusBox.className = "bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold";

        } catch (error) {
            console.error("Error fetching schedule:", error);
            statusBox.textContent = "Gagal mengambil jadwal";
            statusBox.className = "bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold";
        }
    }

    // ==========================================
    // 3. GENERATE OPSI JAM (Logic Baru)
    // ==========================================
    function generateTimeOptions(openTime, closeTime, selectedDate) {
        operationalTimes = []; // Reset array
        
        // Ambil angkanya saja. Contoh: "08:00" -> 8
        let startHour = parseInt(openTime.split(':')[0]);
        let endHour = parseInt(closeTime.split(':')[0]);

        // Loop dari Jam Buka sampai Jam Tutup
        // Contoh: Buka 08:00, Tutup 20:00. Loop 8 s/d 19.
        // Kenapa sampai 19? Karena jam 20:00 itu tutup, tidak bisa MULAI main jam 20:00.
        for (let i = startHour; i < endHour; i++) {
            
            // Format angka jadi string "08:00", "09:00"
            let timeStr = i.toString().padStart(2, '0') + ":00";
            
            // --- VALIDASI HARI INI ---
            // Jika user pilih hari ini, cek apakah jam tersebut sudah lewat?
            if (selectedDate === serverToday) {
                let currentHour = parseInt(serverNowHour.split(':')[0]);
                // Jika jam loop <= jam sekarang, skip. (Buffer 1 jam)
                if (i <= currentHour) { 
                    continue; 
                }
            }

            // Masukkan ke array global & dropdown
            operationalTimes.push(timeStr);
            
            let option = document.createElement('option');
            option.value = timeStr;
            option.textContent = timeStr;
            jamMulaiSelect.appendChild(option);
        }
    }

    // ==========================================
    // 4. UPDATE JAM SELESAI
    // ==========================================
    function updateJamSelesai() {
        const jamMulai = jamMulaiSelect.value;
        jamSelesaiSelect.innerHTML = '<option value="">Pilih Jam Selesai</option>';

        if (jamMulai) {
            jamSelesaiSelect.disabled = false;
            
            // Cari posisi jam mulai di array operationalTimes
            const startIndex = operationalTimes.indexOf(jamMulai);
            
            // Loop untuk menampilkan jam selesai yang valid (setelah jam mulai)
            for (let i = startIndex + 1; i < operationalTimes.length; i++) {
                let option = document.createElement('option');
                option.value = operationalTimes[i];
                option.textContent = operationalTimes[i];
                jamSelesaiSelect.appendChild(option);
            }

            // TAMBAHAN: Masukkan Jam Tutup Asli sebagai opsi terakhir
            // Karena array operationalTimes cuma sampai (Jam Tutup - 1 jam)
            // Kita perlu opsi terakhir misal "20:00" agar user bisa main 19:00-20:00
            if (operationalTimes.length > 0) {
                let lastTime = operationalTimes[operationalTimes.length - 1]; // misal "19:00"
                let lastHourInt = parseInt(lastTime.split(':')[0]);
                let closingTime = (lastHourInt + 1).toString().padStart(2, '0') + ":00"; // jadi "20:00"
                
                let closingOption = document.createElement('option');
                closingOption.value = closingTime;
                closingOption.textContent = closingTime;
                jamSelesaiSelect.appendChild(closingOption);
            }

        } else {
            jamSelesaiSelect.disabled = true;
        }
        updateTotal();
    }

    // ==========================================
    // 5. HITUNG TOTAL & CEK SLOT
    // ==========================================
    function updateTotal() {
        const jamMulai = jamMulaiSelect.value;
        const jamSelesai = jamSelesaiSelect.value;
        let totalJam = 0;

        if (jamMulai && jamSelesai) {
            // Hitung selisih jam secara matematika (tanpa index array)
            let start = parseInt(jamMulai.split(':')[0]);
            let end = parseInt(jamSelesai.split(':')[0]);
            totalJam = end - start;
        }

        totalJamEl.textContent = totalJam;
        const hargaPerJam = {{ $lapangan->harga_perjam }};
        const adminFee = 5000;
        const totalHarga = (hargaPerJam * totalJam) + adminFee;
        totalHargaEl.textContent = 'Rp' + totalHarga.toLocaleString('id-ID');

        cekSlot(); // Panggil fungsi cek ketersediaan slot
    }

    async function cekSlot() {
        const lapangan = lapanganIdInput.value;
        const tanggal = tanggalInput.value;
        const jamMulai = jamMulaiSelect.value;
        const jamSelesai = jamSelesaiSelect.value;

        // Jangan cek jika data belum lengkap
        if (!tanggal || !jamMulai || !jamSelesai) {
             // Opsional: Reset status jika user menghapus jam
            return;
        }

        const baseUrl = "{{ url('/penyewa/cek-slot') }}";
        const params = new URLSearchParams({
            lapangan_id: lapangan,
            tanggal: tanggal,
            jam_mulai: jamMulai,
            jam_selesai: jamSelesai
        });

        try {
            const res = await fetch(`${baseUrl}?${params.toString()}`);
            const data = await res.json();

            if (data.available) {
                statusBox.textContent = "Lapangan Tersedia";
                statusBox.className = "bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold";
                
                btn.disabled = false;
                btn.classList.remove("bg-gray-400", "cursor-not-allowed");
                btn.classList.add("bg-green-600", "hover:bg-green-700");
            } else {
                statusBox.textContent = "Jadwal Bentrok / Tidak Tersedia";
                statusBox.className = "bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold";
                
                btn.disabled = true;
                btn.classList.remove("bg-green-600", "hover:bg-green-700");
                btn.classList.add("bg-gray-400", "cursor-not-allowed");
            }
        } catch (err) {
            console.error("Error cek slot:", err);
        }
    }

    // ==========================================
    // 6. EVENT LISTENERS
    // ==========================================
    document.addEventListener("DOMContentLoaded", function () {
        // Jika browser melakukan cache input tanggal, panggil fetchJadwal
        if(tanggalInput.value) {
            fetchJadwal();
        }

        // Saat tanggal berubah -> Ambil jadwal dari server
        tanggalInput.addEventListener("change", fetchJadwal);
        
        // Saat jam berubah -> Update UI
        jamMulaiSelect.addEventListener("change", updateJamSelesai);
        jamSelesaiSelect.addEventListener("change", updateTotal);
    });
</script>
@endsection
