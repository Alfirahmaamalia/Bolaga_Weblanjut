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
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">

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
                            name="tanggal"
                            class="w-full p-2 rounded-lg border"
                            min="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <!-- Jam Mulai -->
                    <div class="mb-4">
                        <label class="font-semibold">Jam Mulai</label>
                        <select name="jam_mulai" id="jamMulai" class="w-full p-2 rounded-lg border" required>
                            <option value="">Pilih Jam Mulai</option>
                            @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'] as $jam)
                                <option value="{{ $jam }}">{{ $jam }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jam Selesai -->
                    <div class="mb-4">
                        <label class="font-semibold">Jam Selesai</label>
                        <select name="jam_selesai" id="jamSelesai" class="w-full p-2 rounded-lg border" required disabled>
                            <option value="">Pilih Jam Selesai</option>
                        </select>
                    </div>

                    
                    <!-- Hidden input untuk dikirim -->
                    <div id="jamInputs"></div>

                    <!-- Status -->
                    @php $isBooked = $isBooked ?? false; @endphp
                    <div class="mb-4">
                        <span id="status-lapangan"
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
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const statusBox = document.getElementById("status-lapangan");
    const submitBtn = document.querySelector('form button[type="submit"]');
    const totalJamEl = document.getElementById("totalJam");
    const totalHargaEl = document.getElementById("totalHarga");

    const availableTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

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

        cekSlot();
    }

    function cekSlot() {
        const tanggal = tanggalInput.value;
        const jamMulai = jamMulaiSelect.value;
        const jamSelesai = jamSelesaiSelect.value;
        const lapanganId = "{{ $lapangan->lapangan_id }}";

        if (!tanggal || !jamMulai || !jamSelesai) {
            statusBox.className = "bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold";
            statusBox.textContent = "Lapangan Tersedia";
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            return;
        }

        const queryString = new URLSearchParams({
            lapangan_id: lapanganId,
            tanggal: tanggal,
            jam_mulai: jamMulai,
            jam_selesai: jamSelesai
        });

        fetch(`/penyewa/cek-slot?${queryString.toString()}`)
        .then(res => res.json())
        .then(data => {
            if (data.available) {
                statusBox.className = "bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold";
                statusBox.textContent = "Lapangan Tersedia";
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                statusBox.className = "bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold";
                statusBox.textContent = "Lapangan Tidak Tersedia";
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            alert("Fetch error! Lihat console");
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        jamMulaiSelect.addEventListener("change", updateJamSelesai);
        jamSelesaiSelect.addEventListener("change", updateTotal);
        tanggalInput.addEventListener("change", cekSlot);
        updateTotal();
    });
</script>
@endsection
