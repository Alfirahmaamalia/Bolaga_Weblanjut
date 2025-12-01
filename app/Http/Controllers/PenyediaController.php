<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Lapangan;

class PenyediaController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id(); 
        $lapanganIds = Lapangan::where('penyedia_id', $userId)->pluck('lapangan_id');
        $bookingAktif = Booking::whereIn('lapangan_id', $lapanganIds)
            ->whereIn('status', ['menunggu konfirmasi', 'berhasil', 'belum bayar']) 
            ->count();
        $pendapatan = Booking::whereIn('lapangan_id', $lapanganIds)
            ->where('status', 'berhasil') 
            ->sum('total_harga');
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
    
    public function manajemenBooking()
    {
        $userId = Auth::id();

        // Cara mengambil booking milik penyedia:
        // Ambil booking dimana lapangan_id nya termasuk dalam daftar lapangan milik penyedia tersebut
        $lapanganIds = Lapangan::where('penyedia_id', $userId)->pluck('lapangan_id');

        $bookings = Booking::with(['penyewa', 'lapangan'])
            ->whereIn('lapangan_id', $lapanganIds) // Filter berdasarkan lapangan milik penyedia
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penyedia.manajemenbooking', [
            'bookings' => $bookings,
            'total' => $bookings->count(),
            // Sesuaikan string status ini dengan database kamu (case sensitive)
            'menunggu' => $bookings->where('status', 'menunggu konfirmasi')->count(), 
            'dikonfirmasi' => $bookings->where('status', 'berhasil')->count(),
            'selesai' => $bookings->where('status', 'selesai')->count(), // Pastikan ada status 'selesai' atau 'gagal'
        ]);
    }
}