<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LapanganController extends Controller
{
    // ==========================
    // DASHBOARD PENYEWA (Dummy)
    // ==========================
    public function dashboard()
    {
        // Ambil semua lapangan yang aktif
        $items = Lapangan::where('aktif', true)->get();
        
        // Jika kolom fasilitas berupa string, ubah jadi array
        foreach ($items as $i) {
            if (is_string($i->fasilitas)) {
                $i->fasilitas = json_decode($i->fasilitas, true);
            }
        }

        return view('penyewa.dashboard', compact('items'));
    }

    // ---
    
    // ==========================
    // DETAIL LAPANGAN
    // ==========================
    public function detail($id)
    {
        // Ambil dari database
        $lapangan = Lapangan::where('lapangan_id', $id)->firstOrFail();

        // Jika fasilitas disimpan sebagai string JSON → decode
        if (is_string($lapangan->fasilitas)) {
            $decoded = json_decode($lapangan->fasilitas, true);

            // Kalau JSON valid → pakai JSON
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $lapangan->fasilitas = $decoded;
            } else {
                // fallback kalau ternyata dipisah koma
                $lapangan->fasilitas = array_map('trim', explode(',', $lapangan->fasilitas));
            }
        }

        return view('penyewa.detail', compact('lapangan'));
    }

    // ---

    // ==========================
    // FUNGSI PENYEDIA
    // ==========================

    public function kelolalapangan()
    {
        $data = Lapangan::where('penyedia_id', auth()->id())->paginate(10);
        return view('penyedia.kelolalapangan', compact('data'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis_olahraga' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_perjam' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
            'aktif' => 'sometimes|boolean',
        ]);

        $v['penyedia_id'] = auth()->id();
        $v['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            $v['foto'] = 'storage/' . $request->file('foto')->store('lapangan', 'public');
        } else {
            $v['foto'] = 'images/lapangan.jpg';
        }

        Lapangan::create($v);
        return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Mencari berdasarkan kolom 'id' (Primary Key)
        $lap = Lapangan::where('lapangan_id', $id)->where('penyedia_id', auth()->id())->firstOrFail();

        $v = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis_olahraga' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_perjam' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
            'aktif' => 'sometimes|boolean',
        ]);

        $v['penyedia_id'] = auth()->id();
        $v['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            if ($lap->foto && Storage::disk('public')->exists($lap->foto)) {
                Storage::disk('public')->delete($lap->foto);
            }
            $v['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        $lap->update($v);
        return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan diperbarui.');
    }

    public function destroy($id)
    {
        // Mencari berdasarkan kolom 'id' (Primary Key)
        $lap = Lapangan::where('lapangan_id', $id)->where('penyedia_id', auth()->id())->firstOrFail();

        if ($lap->foto && Storage::disk('public')->exists($lap->foto)) {
            Storage::disk('public')->delete($lap->foto);
        }

        $lap->delete();
        return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan berhasil dihapus.');
    }

    // ---

    // =====================================================
    // FITUR BARU — CEK KETERSEDIAAN SLOT BOOKING
    // =====================================================
    public function cekSlot(Request $r)
    {
        if (!$r->tanggal || !$r->jam) {
            return response()->json(['available' => true]); // belum memilih lengkap
        }

        $jam = is_array($r->jam) ? $r->jam : [$r->jam];

        $ada = DB::table('booking')
            ->where('lapangan_id', $r->lapangan_id)
            ->where('tanggal', $r->tanggal)
            ->whereJsonContains('jam', $jam)
            // ->where(function ($q) use ($r) {
            //     $q->whereBetween('jam_mulai', [$r->jam_mulai, $r->jam_selesai])
            //       ->orWhereBetween('jam_selesai', [$r->jam_mulai, $r->jam_selesai]);
            // })
            ->exists();

        return response()->json([
            'available' => !$ada
        ]);
    }

    // ---

    // =====================================================
    // 🔥 METHOD KONFIRMASI (ROBUST)
    // =====================================================
    public function konfirmasi(Request $r)
    {
        // GUARD CLAUSE: Pastikan parameter esensial ada
        if (!$r->filled(['lapangan_id', 'tanggal', 'jam'])) {
            return redirect()->route('penyewa.dashboard')->withErrors('Booking dibatalkan. Harap pilih lapangan, tanggal, dan jam yang valid.');
        }

        $dummy = [
            1 => [
                'lapangan_id' => 1,
                'nama_lapangan' => 'Arena Futsal Nusantara',
                'jenis_olahraga' => 'Futsal',
                'lokasi' => 'Jakarta Selatan',
                'deskripsi' => 'Lapangan futsal berkualitas tinggi dengan fasilitas lengkap.',
                'harga_perjam' => 150000,
                'foto' => 'images/futsal.jpg',
                'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
                'aktif' => true,
            ],
            2 => [
                'lapangan_id' => 2,
                'nama_lapangan' => 'Satria Nugraha Badminton',
                'jenis_olahraga' => 'Badminton',
                'lokasi' => 'Tanjung Karang Pusat',
                'deskripsi' => 'Lapangan badminton indoor dengan raket pinjaman.',
                'harga_perjam' => 250000,
                'foto' => 'images/badminton.jpg',
                'fasilitas' => ['AC', 'Raket Pinjaman', 'Toilet', 'Kantin'],
                'aktif' => true,
            ],
            4 => [
                'lapangan_id' => 4,
                'nama_lapangan' => 'Samudra Volley Court',
                'jenis_olahraga' => 'Voli',
                'lokasi' => 'Bandung',
                'deskripsi' => 'Lapangan voli dengan pemandangan laut.',
                'harga_perjam' => 200000,
                'foto' => 'images/volly.jpg',
                'fasilitas' => ['Parkir', 'Toilet', 'Kantin'],
                'aktif' => true,
            ],
        ];

        if (array_key_exists($r->lapangan_id, $dummy)) {
            $lapangan = (object) $dummy[$r->lapangan_id];
        } else {
            // Menggunakan ID yang dikirimkan
            $lapangan = Lapangan::findOrFail($r->lapangan_id); 
        }

        // Parse jam (format: "08:00 - 10:00")
        $jamParts = explode(' - ', $r->jam);
        $jam_mulai = $jamParts[0] ?? '08:00';
        $jam_selesai = $jamParts[1] ?? '10:00';

        // Hitung durasi jam
        $start = Carbon::createFromFormat('H:i', $jam_mulai);
        $end = Carbon::createFromFormat('H:i', $jam_selesai);
        $durasi_jam = $end->diffInHours($start);
        if ($durasi_jam <= 0) $durasi_jam = 1;

        // Hitung total (harga per jam × durasi + admin)
        $admin = 5000;
        $harga_total = $lapangan->harga_perjam * $durasi_jam;
        $total = $harga_total + $admin;

        return view('penyewa.konfirmasi', [
            'lapangan' => $lapangan,
            'tanggal'  => $r->tanggal,
            'jam' => $durasi_jam,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'admin' => $admin,
            'total' => $total
        ]);
    }

    // ---

    // =====================================================
    // 🔥 METHOD PEMBAYARAN (SUDAH BENAR)
    // =====================================================
    public function pembayaran(Request $request)
    {
        // 1. Ambil ID Lapangan dari request (nilai 'id' di database)
        $lapangan_id = $request->input('lapangan_id');

        // 2. Ambil objek Lapangan dari database
        $lapangan = Lapangan::findOrFail($lapangan_id);
        
        // 3. Ambil data lain yang dikirimkan (dari hidden input)
        $admin = 5000; 
        
        // 4. Kirim semua data yang dibutuhkan ke view
        return view('penyewa.pembayaran', [
            'lapangan' => $lapangan, 
            'tanggal' => $request->input('tanggal'),
            'jam' => $request->input('jam'),
            'jam_mulai' => $request->input('jam_mulai'),
            'jam_selesai' => $request->input('jam_selesai'),
            'total' => $request->input('total'),
            'admin' => $admin,
        ]);
    }
}