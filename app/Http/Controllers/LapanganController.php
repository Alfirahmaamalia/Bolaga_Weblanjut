<?php

namespace App\Http\Controllers;

use App\Models\JamOperasional;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LapanganController extends Controller
{
    public function dashboard(Request $r)
    {
        $query = Lapangan::where('aktif', true);

        // filter (jika ada) -- contoh singkat
        if ($r->search) {
            $query->where('nama_lapangan', 'ILIKE', "%{$r->search}%");
        }
        if ($r->jenis) {
            $query->where('jenis_olahraga', $r->jenis);
        }
        if ($r->lokasi) {
            $query->where('lokasi', 'LIKE', '%' . $r->lokasi . '%');
        }
        if ($r->harga) {
            if ($r->harga == '<=100') {
                $query->where('harga_perjam', '<=', 100000);
            } elseif ($r->harga == '100-250') {
                $query->whereBetween('harga_perjam', [100000, 250000]);
            } elseif ($r->harga == '>=250') {
                $query->where('harga_perjam', '>=', 250000);
            }
        }

        $items = $query->get();

        $items->map(function($i){
            $i->fasilitas = is_string($i->fasilitas) ? json_decode($i->fasilitas, true) ?? [] : ($i->fasilitas ?? []);
            return $i;
        });

        return view('penyewa.dashboard', compact('items'));
    }

    // ---
    
    // ==========================
    // DETAIL LAPANGAN
    // ==========================
    public function detail($id)
    {
        // UPDATE DI SINI:
        // Gunakan 'with' untuk mengambil relasi jam_operasional
        // Kita urutkan berdasarkan 'hari' (0 = Minggu, 1 = Senin, dst)
        $lapangan = Lapangan::with(['jam_operasional' => function($query) {
            $query->orderBy('hari', 'asc');
        }])->where('lapangan_id', $id)->firstOrFail();

        // Logika JSON fasilitas (tetap sama seperti kodemu)
        if (is_string($lapangan->fasilitas)) {
            $decoded = json_decode($lapangan->fasilitas, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $lapangan->fasilitas = $decoded;
            } else {
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
        $lapangan = Lapangan::where('user_id_penyedia', auth()->id())->get();
        return view('penyedia.kelolalapangan', compact('lapangan'));
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

    public function create()
{
    return view('penyedia.tambah_lapangan');
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

    public function cekJadwal(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        // 1. Cari tahu hari apa (0=Minggu, 1=Senin, dst)
        $hariKe = \Carbon\Carbon::parse($request->tanggal)->dayOfWeek;

        // 2. Ambil jadwal dari database
        $lapangan = Lapangan::find($request->lapangan_id);
        $jadwal = $lapangan->jam_operasional()->where('hari', $hariKe)->first();

        // 3. Cek Libur
        if (!$jadwal || $jadwal->is_libur) {
            return response()->json([
                'status' => 'libur',
                'message' => 'Maaf, lapangan libur pada tanggal tersebut.'
            ]);
        }

        // 4. Jika Buka, kirim jam operasionalnya
        return response()->json([
            'status' => 'buka',
            'jam_buka' => \Carbon\Carbon::parse($jadwal->jam_buka)->format('H:i'),
            'jam_tutup' => \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H:i'),
        ]);
    }

    // ---

    // =====================================================
    // FITUR BARU — CEK KETERSEDIAAN SLOT BOOKING
    // =====================================================
    public function cekSlot(Request $r)
    {
        // echo "<script>alert('cek slot controller terpanggil');</script>";
        if (!$r->tanggal || !$r->jam_mulai) {
            return response()->json(['available' => true]); // belum memilih lengkap
        }

        $jamMulai = $r->jam_mulai;
        $jamSelesai = $r->jam_selesai;

        if (!$jamSelesai) {
            $jamSelesai = \Carbon\Carbon::createFromFormat('H:i', $jamMulai)
                        ->addHour()
                        ->format('H:i');
        }

        $ada = DB::table('booking')
        ->where('lapangan_id', $r->lapangan_id)
        ->where('tanggal', $r->tanggal)
        ->where('status', '!=', 'gagal')
        ->where(function($q) use ($jamMulai, $jamSelesai) {
            $q->where('jam_mulai', '<', $jamSelesai)
              ->where('jam_selesai', '>', $jamMulai);
        })
        ->exists();

        return response()->json([
            'available' => !$ada
        ]);
    }
}