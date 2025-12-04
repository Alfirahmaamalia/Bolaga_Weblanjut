@extends('layouts.navbaradmin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Tombol Kembali -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-black mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Validasi Lapangan</h1>
                <p class="text-slate-600">Verifikasi lapangan baru dari penyedia</p>
            </div>
            
            <form method="GET" action="{{ route('admin.validasilapangan') }}">
                <select name="status" onchange="this.form.submit()" class="px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="menunggu validasi" {{ request('status') == 'menunggu validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non aktif" {{ request('status') == 'non aktif' ? 'selected' : '' }}>Non Aktif</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Info Lapangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Penyedia</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga/Jam</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Dokumen</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($lapangan as $item)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 bg-slate-200 rounded-lg overflow-hidden mr-3 border border-slate-200 relative group">
                                        @if($item->foto)
                                            <img src="{{ asset($item->foto) }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-400 bg-slate-100">...</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $item->nama_lapangan }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->jenis_olahraga }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 font-medium">{{ optional($item->user)->nama ?? 'User Tidak Ditemukan' }}</div>
                                <div class="text-xs text-slate-500">{{ optional($item->user)->email ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600 font-medium font-mono">Rp {{ number_format($item->harga_perjam, 0, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($item->bukti_kepemilikan)
                                    <a href="{{ asset($item->bukti_kepemilikan) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 border border-blue-200">
                                        Lihat PDF
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic bg-slate-50 px-2 py-1 rounded">Tidak ada</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($item->status === 'menunggu validasi')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span> Menunggu
                                    </span>
                                @elseif($item->status === 'aktif')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span> Aktif
                                    </span>
                                @elseif($item->status === 'non aktif')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span> Non Aktif
                                    </span>
                                @elseif($item->status === 'ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span> Ditolak
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    {{-- TOMBOL BARU: Detail (Ikon Mata) --}}
                                    <a href="{{ route('admin.lapangan.show', $item->lapangan_id) }}" 
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-blue-50 transition-all shadow-sm"
                                        title="Lihat Detail Lengkap">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    {{-- 1. Tombol Approve (Hijau) --}}
                                    @if($item->status !== 'aktif' && $item->status !== 'non aktif')
                                    <form id="form-approve-{{ $item->lapangan_id }}" action="{{ route('admin.lapangan.approve', $item->lapangan_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="confirmAction('form-approve-{{ $item->lapangan_id }}', 'approve')" 
                                                class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all duration-200 shadow-sm"
                                                title="Setujui & Aktifkan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- 2. Tombol Reject (Merah) --}}
                                    @if($item->status !== 'ditolak')
                                    <form id="form-reject-{{ $item->lapangan_id }}" action="{{ route('admin.lapangan.reject', $item->lapangan_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="confirmAction('form-reject-{{ $item->lapangan_id }}', 'reject')" 
                                                class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-200 shadow-sm"
                                                title="Tolak Pengajuan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- 3. Tombol Hapus Permanen (Abu-abu) --}}
                                    <form id="form-delete-{{ $item->lapangan_id }}" action="{{ route('admin.lapangan.destroy', $item->lapangan_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmAction('form-delete-{{ $item->lapangan_id }}', 'delete')" 
                                                class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-600 hover:text-white transition-all duration-200 shadow-sm"
                                                title="Hapus Permanen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-lg font-medium text-slate-900">Belum ada pengajuan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 text-xs text-slate-500 flex justify-between">
                <span>Total: {{ count($lapangan) }} Lapangan</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi Modular untuk Menampilkan Konfirmasi
    function confirmAction(formId, actionType) {
        let title, text, icon, confirmButtonText, confirmButtonColor;

        // Kustomisasi pesan berdasarkan tipe aksi
        if (actionType === 'approve') {
            title = 'Validasi Lapangan?';
            text = "Lapangan akan diaktifkan dan dapat dilihat oleh penyewa.";
            icon = 'question';
            confirmButtonText = 'Ya, Aktifkan!';
            confirmButtonColor = '#10b981'; // Emerald-500
        } else if (actionType === 'reject') {
            title = 'Tolak Pengajuan?';
            text = "Status lapangan akan diubah menjadi Ditolak.";
            icon = 'warning';
            confirmButtonText = 'Ya, Tolak!';
            confirmButtonColor = '#ef4444'; // Red-500
        } else if (actionType === 'delete') {
            title = 'Hapus Permanen?';
            text = "Data lapangan, foto, dan dokumen akan dihapus selamanya. Tindakan ini tidak bisa dibatalkan!";
            icon = 'warning';
            confirmButtonText = 'Ya, Hapus Sekarang!';
            confirmButtonColor = '#d33';
        }

        // Tampilkan SweetAlert
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal',
            reverseButtons: true, // Tombol konfirmasi di kanan
            backdrop: `rgba(0,0,0,0.4)` // Latar belakang gelap transparan
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form secara manual jika user klik Ya
                document.getElementById(formId).submit();
            }
        });
    }

    // Menampilkan Notifikasi Sukses dari Session (Controller)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif
</script>

@endsection