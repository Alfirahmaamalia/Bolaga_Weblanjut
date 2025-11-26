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

                <form action="{{ route('penyewa.booking.konfirmasi', ['lapangan_id' => $lapangan->lapangan_id]) }}" method="GET">

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
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Pilih Jam</label>

                        <!-- Dropdown -->
                        <select id="jamDropdown" class="w-full p-2 rounded-lg border">
                            <option value="">-- Pilih Jam --</option>
                            <option value="08:00">08.00</option>
                            <option value="09:00">09.00</option>
                            <option value="10:00">10.00</option>
                            <option value="11:00">11.00</option>
                            <option value="12:00">12.00</option>
                            <option value="13:00">13.00</option>
                            <option value="14:00">14.00</option>
                            <option value="15:00">15.00</option>
                            <option value="16:00">16.00</option>
                            <option value="17:00">17.00</option>
                        </select>

                        <!-- Tempat munculnya pilihan jam -->
                        <div id="jamTerpilih" class="mt-3 flex flex-wrap gap-2"></div>
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
    const dropdown = document.getElementById("jamDropdown");
    const container = document.getElementById("jamTerpilih");
    const jamInputs = document.getElementById("jamInputs");
    // const submitBtn = document.querySelector('form button[type="submit"]');
    let listJam = [];
    let submitBtn;

    function cekSlot() {
        const tanggalInput = document.querySelector('input[name="tanggal"]');
        const statusBox = document.getElementById("status-lapangan");

        const tanggal = tanggalInput.value;
        const lapanganId = "{{ $lapangan->lapangan_id }}";
        
        if (!tanggal || listJam.length === 0) {
            statusBox.className = "bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold";
            statusBox.textContent = "Lapangan Tersedia";

            // tombol aktif
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            return;
        }
        
        const queryString = new URLSearchParams({
            lapangan_id: lapanganId,
            tanggal: tanggal,
            // kirim jam[] sebagai array
        });

        listJam.forEach(j => queryString.append('jam[]', j));

        fetch(`/penyewa/cek-slot?${queryString.toString()}`)
        .then(res => res.json())
        .then(data => {
            if (data.available) {
                statusBox.className = "bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold";
                statusBox.textContent = "Lapangan Tersedia";
            
                // tombol aktif
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                statusBox.className = "bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold";
                statusBox.textContent = "Lapangan Tidak Tersedia";
            
                // tombol nonaktif
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

    function updateTotal() {
        const totalJam = listJam.length > 0 ? listJam.length : 1; // default 1 jika kosong
        const hargaPerJam = {{ $lapangan->harga_perjam }};
        const adminFee = 5000;

        document.getElementById("totalJam").textContent = totalJam;
        const totalHarga = hargaPerJam * totalJam + adminFee;
        document.getElementById("totalHarga").textContent = 'Rp' + totalHarga.toLocaleString('id-ID');

        cekSlot();
    }

    dropdown.addEventListener("change", function () {
        const jam = this.value;
        if (!jam || listJam.includes(jam)) return this.value = "";

        listJam.push(jam);

        // Tambahkan tampilan badge/tag
        const tag = document.createElement("div");
        tag.className = "px-3 py-1 bg-blue-200 rounded-lg flex items-center gap-2";
        tag.innerHTML = `
            ${jam}
            <button type="button" class="text-red-600 font-bold" onclick="removeJam('${jam}', this)">×</button>
        `;
        container.appendChild(tag);

        // hidden input form
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "jam[]";
        input.value = jam;
        input.id = "jam-" + jam;
        jamInputs.appendChild(input);

        updateTotal();

        this.value = "";
    });

    function removeJam(jam, btn) {
        listJam = listJam.filter(j => j !== jam);
        btn.parentElement.remove();
        document.getElementById("jam-" + jam)?.remove();
    
        updateTotal();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const tanggalInput = document.querySelector('input[name="tanggal"]');
        const jamInput = document.getElementById('jamDropdown');
        const statusBox = document.getElementById("status-lapangan");
        submitBtn = document.querySelector('form button[type="submit"]');

        tanggalInput.addEventListener("change", cekSlot);
        jamInput.addEventListener("change", cekSlot);

        updateTotal();
    });
</script>
@endsection
