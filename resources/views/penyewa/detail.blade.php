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
                 class="w-full h-[450px] rounded-xl shadow-md object-cover"
                 onerror="this.src='https://picsum.photos/600/400?random=1'">
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
                    <div class="mb-4">
                        <label class="font-semibold">Jam Mulai</label>
                        <select name="jam_mulai" id="jamMulai" class="w-full p-2 rounded-lg border" required onchange="cekSlot()">
                            <option value="">Pilih Jam Mulai</option>
                            @foreach($daftarJamMulai as $jam)
                                @if($jam >= $jamMinimal)
                                    <option value="{{ $jam }}">{{ $jam }}</option>
                                @endif
                            @endforeach
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
<script>
    const jamMulaiSelect = document.getElementById("jamMulai");
    const jamSelesaiSelect = document.getElementById("jamSelesai");
    const tanggalInput = document.getElementById("tanggal");
    const statusBox = document.getElementById("statusSlot");
    const btn = document.getElementById("btnSubmit");
    const totalJamEl = document.getElementById("totalJam");
    const totalHargaEl = document.getElementById("totalHarga");
    const lapanganIdInput = document.getElementById("lapanganId");

    const availableTimes = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

    function updateJamSelesai() {
        const jamMulai = jamMulaiSelect.value;
        jamSelesaiSelect.innerHTML = '<option value="">Pilih Jam Selesai</option>';

        if (jamMulai) {
            jamSelesaiSelect.disabled = false;
            const startIndex = availableTimes.indexOf(jamMulai);
            for (let i = startIndex + 1; i < availableTimes.length; i++) {
                const option = document.createElement("option");
                option.value = availableTimes[i];
                option.textContent = availableTimes[i];
                jamSelesaiSelect.appendChild(option);
            }
        } else {
            jamSelesaiSelect.disabled = true;
        }
        updateTotal();
    }

    function updateTotal() {
        const jamMulai = jamMulaiSelect.value;
        const jamSelesai = jamSelesaiSelect.value;
        let totalJam = 0;

        if (jamMulai && jamSelesai) {
            const startIndex = availableTimes.indexOf(jamMulai);
            const endIndex = availableTimes.indexOf(jamSelesai);
            totalJam = endIndex - startIndex;
        }

        totalJamEl.textContent = totalJam;
        const hargaPerJam = {{ $lapangan->harga_perjam }};
        const adminFee = 5000;
        const totalHarga = hargaPerJam * totalJam + adminFee;
        totalHargaEl.textContent = 'Rp' + totalHarga.toLocaleString('id-ID');

        // cek slot setiap update total (tetap aman)
        cekSlot();
    }

    async function cekSlot() {
        const lapangan = lapanganIdInput ? lapanganIdInput.value : '{{ $lapangan->lapangan_id }}';
        const tanggal = tanggalInput ? tanggalInput.value : '';
        const jamMulai = jamMulaiSelect.value;
        const jamSelesai = jamSelesaiSelect ? jamSelesaiSelect.value : '';

        // Jika belum ada tanggal atau jamMulai -> anggap tersedia
        if (!tanggal || !jamMulai) {
            // teks + warna hijau (konsisten)
            statusBox.textContent = "Lapangan Tersedia";
            statusBox.classList.remove("bg-red-100", "text-red-700", "bg-yellow-100", "text-yellow-700");
            statusBox.classList.add("bg-green-100", "text-green-700");

            btn.disabled = false;
            btn.classList.remove("bg-gray-400", "cursor-not-allowed");
            btn.classList.add("bg-green-600", "hover:bg-green-700");
            return;
        }

        // Gunakan URL yang tepat (sesuaikan jika route-mu beda)
        const baseUrl = "{{ url('/penyewa/cek-slot') }}";
        const params = new URLSearchParams({
            lapangan_id: lapangan,
            tanggal: tanggal,
            jam_mulai: jamMulai,
            jam_selesai: jamSelesai
        });

        try {
            const res = await fetch(`${baseUrl}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            // tangani status non-200
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}`);
            }

            const data = await res.json();

            if (data.available) {
                // teks + warna hijau
                statusBox.textContent = "Lapangan Tersedia";
                statusBox.classList.remove("bg-red-100", "text-red-700", "bg-yellow-100", "text-yellow-700");
                statusBox.classList.add("bg-green-100", "text-green-700");

                // Tombol aktif
                btn.disabled = false;
                btn.classList.remove("bg-gray-400", "cursor-not-allowed");
                btn.classList.add("bg-green-600", "hover:bg-green-700");
            } else {
                // teks + warna merah
                statusBox.textContent = "Lapangan Tidak Tersedia";
                statusBox.classList.remove("bg-green-100", "text-green-700", "bg-yellow-100", "text-yellow-700");
                statusBox.classList.add("bg-red-100", "text-red-700");

                // Tombol disabled
                btn.disabled = true;
                btn.classList.remove("bg-green-600", "hover:bg-green-700");
                btn.classList.add("bg-gray-400", "cursor-not-allowed");
            }
        } catch (err) {
            console.error("cekSlot fetch error:", err);
            // tampilkan pesan error + warna kuning
            statusBox.textContent = "Gagal mengecek slot (error)";
            statusBox.classList.remove("bg-green-100", "text-green-700", "bg-red-100", "text-red-700");
            statusBox.classList.add("bg-yellow-100", "text-yellow-700");

            // keputusan: biarkan tombol aktif agar user tetap bisa lanjut,
            // atau nonaktifkan bila kamu ingin lebih aman. Di sini saya biarkan aktif.
            btn.disabled = false;
            btn.classList.remove("bg-gray-400", "cursor-not-allowed");
            btn.classList.add("bg-green-600", "hover:bg-green-700");
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        jamMulaiSelect.addEventListener("change", function() {
            updateJamSelesai();
            // cekSlot dipanggil di updateTotal -> cukup
        });
        jamSelesaiSelect.addEventListener("change", updateTotal);
        tanggalInput.addEventListener("change", cekSlot);

        // panggil sekali agar status awal sesuai
        updateTotal();
    });
</script>
@endsection
