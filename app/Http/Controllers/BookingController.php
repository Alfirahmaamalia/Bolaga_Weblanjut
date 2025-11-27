<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Lapangan;
use App\Models\Booking;

class BookingController extends Controller
{
    public function konfirmasi(Request $r)
    {
        // GUARD CLAUSE: Pastikan parameter esensial ada
        if (!$r->filled(['lapangan_id', 'tanggal', 'jam'])) {
            return redirect()->route('penyewa.dashboard')
            ->withErrors('Booking dibatalkan. Harap pilih lapangan, tanggal, dan jam yang valid.');
        }

        // Ambil data lapangan dari database
        $lapangan = Lapangan::findOrFail($r->lapangan_id);

        // Pastikan jam diterima sebagai array
        $jam_array = (array) $r->jam;

        // Hitung durasi penyewaan dari jumlah jam
        $durasi_jam = count($jam_array);
        if ($durasi_jam <= 0) $durasi_jam = 1; // default minimal 1 jam

        // Hitung total (harga per jam × durasi + admin)
        $admin = 5000;
        $harga_total = $lapangan->harga_perjam * $durasi_jam;
        $total = $harga_total + $admin;

        return view('penyewa.konfirmasi', [
            'lapangan' => $lapangan,
            'tanggal'  => $r->tanggal,
            'jam' => $jam_array,
            'durasi' => $durasi_jam,
            'admin' => $admin,
            'total' => $total
        ]);
    }

    public function simpanBooking(Request $request)
    {
        $booking = new Booking();
        $booking->lapangan_id = $request->lapangan_id;
        $booking->penyewa_id = auth()->id(); // user yang login
        $booking->tanggal = $request->tanggal;
        $booking->jam = $request->jam; // array time
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
        $jam = (array) $booking->jam;
        $durasi = count($jam);
        $admin = 5000;
        $harga_total = $lapangan->harga_perjam * $durasi;
        $total = $harga_total + $admin;
        
        return view('penyewa.pembayaran', [
            'booking' => $booking,
            'lapangan' => $lapangan,
            'tanggal' => $booking->tanggal,
            'jam' => $jam,
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
            $file->storeAs('public/bukti', $namaFile);

            // Simpan path ke database (akses via /storage)
            $booking->bukti_pembayaran = 'storage/bukti/' . $namaFile;
        }

        $booking->status = 'berhasil';
        $booking->metode_pembayaran = $request->metode_pembayaran;
        $booking->save();

        return redirect()->route('penyewa.dashboard')->with('success', 'Pembayaran berhasil. Booking Anda telah dikonfirmasi.');
    }
}
