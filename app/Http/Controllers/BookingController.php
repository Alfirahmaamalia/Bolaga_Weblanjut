<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Booking;

class BookingController extends Controller
{
    public function cekSlot(Request $request, $id)
    {
        $tanggal = $request->tanggal;    
        $jam = $request->jam;             

        $lapangan = Lapangan::findOrFail($id);

        // cek apakah jam & tanggal bentrok booking
        $isBooked = Booking::where('lapangan_id', $id)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['belum bayar', 'berhasil']) // dianggap memblokir lapangan
            ->whereJsonContains('jam', $jam) // cek array jam
            ->exists();

        return view('penyewa.detail', compact('lapangan', 'isBooked'));
    }
}
