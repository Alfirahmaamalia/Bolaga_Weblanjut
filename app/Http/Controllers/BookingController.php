<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Lapangan;
use App\Models\Booking;

class BookingController extends Controller
{
    public function konfirmasi(Request $r)
    {
        // GUARD CLAUSE: Pastikan parameter esensial ada
        if (!$r->filled(['lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai'])) {
            return redirect()->route('penyewa.dashboard')
            ->withErrors('Booking dibatalkan. Harap pilih lapangan, tanggal, jam mulai, dan jam selesai yang valid.');
        }

        // Ambil data lapangan dari database
        $lapangan = Lapangan::findOrFail($r->lapangan_id);

        // Hitung durasi penyewaan dari jam mulai dan selesai
        $availableTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $startIndex = array_search($r->jam_mulai, $availableTimes);
        $endIndex = array_search($r->jam_selesai, $availableTimes);
        
        if ($startIndex === false || $endIndex === false) {
            return redirect()->route('penyewa.dashboard')
            ->withErrors('Jam mulai atau jam selesai tidak valid.');
        }
        
        $durasi_jam = $endIndex - $startIndex;

        if ($durasi_jam <= 0) {
            return redirect()->route('penyewa.dashboard')
            ->withErrors('Jam selesai harus setelah jam mulai.');
        }

        // Hitung total (harga per jam × durasi + admin)
        $admin = 5000;
        $harga_total = $lapangan->harga_perjam * $durasi_jam;
        $total = $harga_total + $admin;

        return view('penyewa.konfirmasi', [
            'lapangan' => $lapangan,
            'tanggal'  => $r->tanggal,
            'jam_mulai' => $r->jam_mulai,
            'jam_selesai' => $r->jam_selesai,
            'durasi' => $durasi_jam,
            'admin' => $admin,
            'total' => $total
        ]);
    }

    public function simpanBooking(Request $request)
    {
        $booking = new Booking();
        $booking->lapangan_id = $request->lapangan_id;
        $booking->penyewa_id = Auth::id(); // user yang login
        $booking->tanggal = $request->tanggal;
        $booking->jam_mulai = $request->jam_mulai;
        $booking->jam_selesai = $request->jam_selesai;
        $booking->total_harga = $request->total;
        $booking->metode_pembayaran = $request->metode_pembayaran;
        $booking->status = 'belum bayar';
        $booking->save();
        // dd($booking->jam);
        return redirect()->route('penyewa.booking.pembayaran', ['booking' => $booking->booking_id])
            ->with('success', 'Booking berhasil dibuat. Silakan lanjut ke pembayaran.');
    }

    public function pembayaran(Booking $booking)
    {
        $lapangan = Lapangan::findOrFail($booking->lapangan_id);
        $availableTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $jamMulai = date('H:i', strtotime($booking->jam_mulai));
        $jamSelesai = date('H:i', strtotime($booking->jam_selesai));
        $startIndex = array_search($jamMulai, $availableTimes);
        $endIndex = array_search($jamSelesai, $availableTimes);
        $durasi = $endIndex - $startIndex;
        $admin = 5000;
        $harga_total = $lapangan->harga_perjam * $durasi;
        $total = $harga_total + $admin;

        return view('penyewa.pembayaran', [
            'booking' => $booking,
            'lapangan' => $lapangan,
            'tanggal' => $booking->tanggal,
            'jam_mulai' => $booking->jam_mulai,
            'jam_selesai' => $booking->jam_selesai,
            'durasi' => $durasi,
            'total' => $total,
            'admin' => $admin,
        ]);
    }

    public function konfirmasiPembayaran(Request $request, Booking $booking)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran' => 'required',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {

            $file = $request->file('bukti_pembayaran');

            // Mendapatkan extension file (jpg/png)
            $ext = $file->getClientOriginalExtension();

            // Buat nama unik agar tidak tabrakan
            $namaFile = Str::uuid() . '.' . $ext;

            // Simpan ke storage/app/public/bukti/
            $file->storeAs('bukti', $namaFile, 'public');

            // Simpan path ke database (akses via /storage)
            $booking->bukti_pembayaran = 'storage/bukti/' . $namaFile;
        }

        $booking->status = 'berhasil';
        $booking->metode_pembayaran = $request->metode_pembayaran;
        $booking->save();

        return redirect()->route('penyewa.dashboard')->with('success', 'Pembayaran berhasil. Booking Anda telah dikonfirmasi.');
    }

    public function riwayat(Request $request, Booking $booking, Lapangan $lapangan)
    {
        $penyewaId = Auth::user()->user_id;
        $riwayat = Booking::with('lapangan')
                ->where('penyewa_id', $penyewaId)
                ->orderBy('booking_id', 'desc')
                ->get();
        return view('penyewa.riwayat', compact('riwayat'));
    }
}
