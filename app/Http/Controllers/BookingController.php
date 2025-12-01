<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Lapangan;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function konfirmasi(Request $r)
    {
        // 1. GUARD CLAUSE: Pastikan parameter esensial ada
        if (!$r->filled(['lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai'])) {
            return redirect()->route('penyewa.dashboard')
                ->withErrors('Booking dibatalkan. Data tidak lengkap.');
        }

        // 2. Ambil data lapangan
        $lapangan = Lapangan::findOrFail($r->lapangan_id);

        // 3. LOGIKA BARU: Hitung durasi menggunakan Carbon (Bukan Array Manual)
        try {
            $start = Carbon::parse($r->jam_mulai);
            $end = Carbon::parse($r->jam_selesai);

            // Validasi: Jam selesai harus setelah jam mulai
            if ($end->lessThanOrEqualTo($start)) {
                return redirect()->route('penyewa.dashboard')
                    ->withErrors('Jam selesai harus lebih besar dari jam mulai.');
            }

            // Hitung selisih jam
            $durasi_jam = $start->diffInHours($end);

        } catch (\Exception $e) {
            return redirect()->route('penyewa.dashboard')
                ->withErrors('Format jam tidak valid.');
        }

        // 4. Validasi Durasi (Mencegah durasi 0 atau minus)
        if ($durasi_jam <= 0) {
            return redirect()->route('penyewa.dashboard')
                ->withErrors('Durasi sewa minimal 1 jam.');
        }

        // 5. Hitung total (harga per jam × durasi + admin)
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
        $booking->status = 'belum bayar';
        $booking->save();
        // dd($booking->jam);
        return redirect()->route('penyewa.booking.pembayaran', ['booking' => $booking->booking_id])
            ->with('success', 'Booking berhasil dibuat. Silakan lanjut ke pembayaran.');
    }

    public function pembayaran(Booking $booking)
    {
        $lapangan = Lapangan::findOrFail($booking->lapangan_id);
        $start = Carbon::parse($booking->jam_mulai);
        $end = Carbon::parse($booking->jam_selesai);
        $durasi = $start->diffInHours($end);
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

        $booking->status = 'menunggu konfirmasi';
        $booking->save();

        return redirect()->route('penyewa.dashboard')->with('success', 'Pembayaran berhasil. Booking Anda telah dikonfirmasi.');
    }

    public function batalkanBooking($id)
    {
        // Cari data booking berdasarkan ID
        $booking = \App\Models\Booking::findOrFail($id);

        // Validasi keamanan: Pastikan hanya booking milik user yang login yang bisa dibatalkan
        if ($booking->penyewa_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak membatalkan booking ini.');
        }

        // Validasi status: Hanya yang 'belum bayar' yang boleh dibatalkan
        if ($booking->status !== 'belum bayar') {
            return back()->with('error', 'Booking tidak dapat dibatalkan karena status sudah berubah.');
        }

        // Update status menjadi 'gagal' (agar slot terbuka kembali sesuai logika cekSlot kamu sebelumnya)
        // Atau bisa gunakan 'dibatalkan' jika kamu ingin membedakan statusnya.
        // Disini saya pakai 'gagal' supaya konsisten dengan logika cekSlot kamu tadi.
        $booking->update([
            'status' => 'gagal' 
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
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
