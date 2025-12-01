<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Lapangan;
use Carbon\Carbon;

class PenyediaController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id(); 
        $lapanganIds = Lapangan::where('penyedia_id', $userId)->pluck('lapangan_id');
        $bookingAktif = Booking::whereIn('lapangan_id', $lapanganIds)
            ->count();
        $globalBookings = Booking::whereIn('lapangan_id', $lapanganIds)->get();
        $pendapatan = $globalBookings->where('status', 'berhasil')->sum(function ($item) {
            // Logika perhitungan harga (Total - Biaya admin 5000)
            return ($item->total_harga ?? $item->total) - 5000;
        });
        $totalLapangan = Lapangan::where('penyedia_id', $userId)->count();
        return view('penyedia.dashboard', compact('bookingAktif', 'pendapatan', 'totalLapangan'));
    }

    public function kelolalapangan(Request $request)
    {
        // Mulai query dasar (hanya lapangan milik penyedia yang login)
        // Asumsi: 'penyedia_id' di tabel lapangan merujuk ke Auth::id()
        $query = Lapangan::where('penyedia_id', auth()->id());

        // 1. Logika Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lapangan', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Kategori
        if ($request->filled('kategori') && $request->kategori !== 'Semua Jenis') {
            $query->where('jenis_olahraga', $request->kategori);
        }

        // Eksekusi query
        $lapangan = $query->orderBy('created_at', 'desc')->get();

        // Pastikan fasilitas di-decode jadi array (jika Model belum handle casting)
        foreach ($lapangan as $l) {
            if (is_string($l->fasilitas)) {
                $decoded = json_decode($l->fasilitas, true);
                $l->fasilitas = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
                                ? $decoded 
                                : []; // Fallback empty array
            }
        }

        return view('penyedia.kelolalapangan', compact('lapangan'));
    }
    
    public function manajemenBooking(Request $request)
    {
        $userId = Auth::id();

        // 1. Ambil daftar lapangan milik penyedia (Untuk Dropdown Filter & Scope Query)
        $daftar_lapangan = Lapangan::where('penyedia_id', $userId)->get();
        
        // Ambil array ID lapangan untuk query booking
        $lapanganIds = $daftar_lapangan->pluck('lapangan_id'); // Asumsi 'id' adalah primary key di tabel lapangan

        // 2. HITUNG STATISTIK (Global / Tidak Terpengaruh Filter)
        // Kita ambil semua data booking milik penyedia ini untuk mengisi kartu di atas
        $globalBookings = Booking::whereIn('lapangan_id', $lapanganIds)->get();

        $pendapatan = $globalBookings->where('status', 'berhasil')->sum(function ($item) {
            // Logika perhitungan harga (Total - Biaya admin 5000)
            return ($item->total_harga ?? $item->total) - 5000;
        });

        $statistik = [
            'total' => $globalBookings->count(),
            'pendapatanBulanIni' => $pendapatan, // Catatan: Logika ini menghitung TOTAL pendapatan (bukan cuma bulan ini), sesuai kode lama Anda.
            'menunggu_konfirmasi' => $globalBookings->where('status', 'menunggu konfirmasi')->count(),
            'belum_bayar' => $globalBookings->where('status', 'belum bayar')->count(),
            'berhasil' => $globalBookings->where('status', 'berhasil')->count(),
            'gagal' => $globalBookings->where('status', 'gagal')->count(),
        ];

        // 3. QUERY DATA TABEL (Terpengaruh Filter)
        $query = Booking::with(['penyewa', 'lapangan'])
            ->whereIn('lapangan_id', $lapanganIds);

        // --- LOGIKA FILTER MULAI ---
        
        // Filter A: Lapangan
        if ($request->has('lapangan_id') && $request->lapangan_id != '') {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        // Filter B: Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter C: Urutan (Default Terbaru)
        if ($request->get('urutan') == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // --- LOGIKA FILTER SELESAI ---

        // Eksekusi Query dengan Pagination
        // withQueryString() berguna agar saat klik halaman 2, filter tidak hilang
        $bookings = $query->paginate(10)->withQueryString(); 

        // 4. Hitung Durasi untuk data yang tampil di tabel saja
        foreach ($bookings as $b) {
            $start = Carbon::parse($b->jam_mulai);
            $end = Carbon::parse($b->jam_selesai);

            if ($end->lessThanOrEqualTo($start)) {
                $end->addDay();
            }

            $b->durasi = $start->diffInHours($end);
        }

        // 5. Return View
        return view('penyedia.manajemenbooking', array_merge($statistik, [
            'bookings' => $bookings,
            'daftar_lapangan' => $daftar_lapangan, // Dikirim untuk isi dropdown
        ]));
    }

    public function konfirmasiBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // Hanya boleh konfirmasi jika status 'menunggu konfirmasi'
        if ($booking->status !== 'menunggu konfirmasi') {
            return back()->with('error', 'Booking tidak dapat dikonfirmasi.');
        }

        // Ubah status menjadi berhasil
        $booking->status = 'berhasil';
        $booking->save();

        return back()->with('success', 'Booking berhasil dikonfirmasi.');
    }


    public function batalkanBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // Tidak boleh batalkan jika sudah selesai atau gagal atau berhasil
        if (in_array($booking->status, ['selesai', 'gagal', 'berhasil'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        // Ubah status menjadi gagal
        $booking->status = 'gagal';
        $booking->save();

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}