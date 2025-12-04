@extends('layouts.navbaradmin')

@section('content')
<div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.validasilapangan') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Lapangan</h1>
                <p class="text-sm text-slate-500">ID: {{ $lapangan->lapangan_id }} • Dibuat pada: {{ $lapangan->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2 text-green-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: Foto, Penyedia, QRIS --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- 1. FOTO UTAMA --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="relative h-64 w-full bg-slate-100 group">
                        @if($lapangan->foto)
                            <img src="{{ asset($lapangan->foto) }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-slate-400 flex-col">
                                <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm">Tidak ada foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. INFO PENYEDIA --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2">Data Penyedia</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-lg ring-4 ring-indigo-50/50">
                            {{ substr(optional($lapangan->user)->nama ?? 'U', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-slate-900 font-semibold truncate" title="{{ optional($lapangan->user)->nama }}">
                                {{ optional($lapangan->user)->nama ?? 'User Terhapus' }}
                            </p>
                            <p class="text-slate-500 text-sm truncate">{{ optional($lapangan->user)->email ?? '-' }}</p>
                            <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ optional($lapangan->user)->role ?? 'Penyedia' }}
                            </span>
                        </div>
                    </div>
                </div>

                 {{-- 3. QRIS (Jika Ada) --}}
                 @if($lapangan->qrcode_qris)
                 <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2">Pembayaran QRIS</h3>
                     <div class="flex flex-col items-center text-center">
                         <div class="p-2 border border-slate-200 rounded-lg bg-white shadow-sm mb-3">
                            <img src="{{ asset($lapangan->qrcode_qris) }}" class="w-32 h-32 object-contain">
                         </div>
                         <p class="text-sm font-bold text-slate-900">{{ $lapangan->nama_qris }}</p>
                         <p class="text-xs font-mono text-slate-500 mt-1 bg-slate-100 px-2 py-1 rounded">NMID: {{ $lapangan->nmid }}</p>
                     </div>
                 </div>
                 @endif
            </div>

            {{-- KOLOM KANAN: Detail Info --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- KARTU INFORMASI UTAMA --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 relative">
                    
                    {{-- Judul & Lokasi --}}
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6 border-b border-slate-100 pb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 leading-tight">{{ $lapangan->nama_lapangan }}</h2>
                            <p class="text-slate-500 flex items-center gap-1.5 mt-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $lapangan->lokasi }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500 mb-1">Harga Sewa</p>
                            <p class="text-2xl font-bold text-emerald-600 font-mono">
                                Rp {{ number_format($lapangan->harga_perjam, 0, ',', '.') }}<span class="text-sm text-slate-400 font-normal">/jam</span>
                            </p>
                        </div>
                    </div>

                    {{-- Grid Informasi Detail --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- STATUS LAPANGAN (Ditambahkan Disini) --}}
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-2">Status Saat Ini</h3>
                            @if($lapangan->status === 'menunggu validasi')
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700 text-sm font-bold">
                                    <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                    Menunggu Validasi
                                </div>
                            @elseif($lapangan->status === 'aktif')
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm font-bold">
                                    <span class="w-2.5 h-2.5 bg-green-600 rounded-full"></span>
                                    Aktif
                                </div>
                            @elseif($lapangan->status === 'non aktif')
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-600 text-sm font-bold">
                                    <span class="w-2.5 h-2.5 bg-slate-400 rounded-full"></span>
                                    Non Aktif
                                </div>
                            @elseif($lapangan->status === 'ditolak')
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm font-bold">
                                    <span class="w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                                    Ditolak
                                </div>
                            @endif
                        </div>

                        {{-- Jenis Olahraga --}}
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-2">Jenis Olahraga</h3>
                            <div class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-100 rounded-lg text-blue-700 text-sm font-medium">
                                {{ $lapangan->jenis_olahraga }}
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Deskripsi Lengkap</h3>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $lapangan->deskripsi }}</p>
                        </div>
                    </div>

                    {{-- FASILITAS (Handling JSON Array) --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Fasilitas Tersedia</h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                // Logic: Jika string, decode. Jika array, gunakan langsung.
                                $fasilitasData = is_string($lapangan->fasilitas) ? json_decode($lapangan->fasilitas, true) : $lapangan->fasilitas;
                            @endphp

                            @if(is_array($fasilitasData) && count($fasilitasData) > 0)
                                @foreach($fasilitasData as $fasilitas)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 shadow-sm">
                                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $fasilitas }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-sm text-slate-400 italic bg-slate-50 px-3 py-2 rounded-lg w-full">
                                    Tidak ada data fasilitas
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Dokumen --}}
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Dokumen Legalitas</h3>
                        @if($lapangan->bukti_kepemilikan)
                            <a href="{{ asset($lapangan->bukti_kepemilikan) }}" target="_blank" class="flex items-center gap-3 p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition border border-blue-200 group">
                                <div class="p-2 bg-white rounded-lg text-blue-600 shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Bukti Kepemilikan.pdf</p>
                                    <p class="text-xs text-blue-500">Klik untuk melihat dokumen</p>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-400 text-sm italic">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                File tidak tersedia
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KARTU JAM OPERASIONAL --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Jam Operasional</h3>
                        <span class="text-xs text-slate-500 bg-white border px-2 py-1 rounded">WIB</span>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-3">Hari</th>
                                    <th class="px-6 py-3">Jam Buka</th>
                                    <th class="px-6 py-3">Jam Tutup</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $namaHari = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
                                    $urutanHari = [1, 2, 3, 4, 5, 6, 0];
                                @endphp

                                @foreach($urutanHari as $h)
                                    @php
                                        $jadwal = $lapangan->jam_operasional->where('hari', $h)->first();
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3 font-medium text-slate-700">{{ $namaHari[$h] }}</td>
                                        <td class="px-6 py-3 text-slate-600 font-mono">
                                            {{ $jadwal && !$jadwal->is_libur ? \Carbon\Carbon::parse($jadwal->jam_buka)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-3 text-slate-600 font-mono">
                                            {{ $jadwal && !$jadwal->is_libur ? \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            @if($jadwal && $jadwal->is_libur)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Libur
                                                </span>
                                            @elseif($jadwal)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Buka
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs italic">Tutup</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ACTION BAR (Sticky Bottom Style) --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky bottom-6 z-10 flex flex-col sm:flex-row gap-3 justify-end items-center">
                    <span class="text-sm text-slate-500 font-medium mr-auto sm:block hidden">
                        Tindakan Admin:
                    </span>

                    {{-- Tombol Hapus --}}
                    <form id="form-delete-{{ $lapangan->lapangan_id }}" action="{{ route('admin.lapangan.destroy', $lapangan->lapangan_id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmAction('form-delete-{{ $lapangan->lapangan_id }}', 'delete')" 
                            class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>

                    {{-- Tombol Reject --}}
                    @if($lapangan->status !== 'ditolak')
                    <form id="form-reject-{{ $lapangan->lapangan_id }}" action="{{ route('admin.lapangan.reject', $lapangan->lapangan_id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="button" onclick="confirmAction('form-reject-{{ $lapangan->lapangan_id }}', 'reject')" 
                            class="w-full sm:w-auto px-4 py-2.5 bg-red-600 text-white hover:bg-red-700 rounded-lg font-semibold transition flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak
                        </button>
                    </form>
                    @endif

                    {{-- Tombol Approve (Hanya jika belum aktif & belum non-aktif) --}}
                    @if($lapangan->status !== 'aktif' && $lapangan->status !== 'non aktif')
                    <form id="form-approve-{{ $lapangan->lapangan_id }}" action="{{ route('admin.lapangan.approve', $lapangan->lapangan_id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="button" onclick="confirmAction('form-approve-{{ $lapangan->lapangan_id }}', 'approve')" 
                            class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg font-semibold transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Validasi & Aktifkan
                        </button>
                    </form>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(formId, actionType) {
        let title, text, icon, confirmButtonText, confirmButtonColor;

        if (actionType === 'approve') {
            title = 'Validasi Lapangan?';
            text = "Lapangan akan diaktifkan dan dapat dilihat oleh penyewa.";
            icon = 'question';
            confirmButtonText = 'Ya, Aktifkan!';
            confirmButtonColor = '#10b981';
        } else if (actionType === 'reject') {
            title = 'Tolak Pengajuan?';
            text = "Status lapangan akan diubah menjadi Ditolak.";
            icon = 'warning';
            confirmButtonText = 'Ya, Tolak!';
            confirmButtonColor = '#ef4444';
        } else if (actionType === 'delete') {
            title = 'Hapus Permanen?';
            text = "Data lapangan, foto, dan dokumen akan dihapus selamanya!";
            icon = 'warning';
            confirmButtonText = 'Ya, Hapus!';
            confirmButtonColor = '#d33';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection