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
    // public function dashboard()
    // {
    //     // Ambil semua lapangan yang aktif
    //     $items = Lapangan::where('aktif', true)->get();
        
    //     // Jika kolom fasilitas berupa string, ubah jadi array
    //     foreach ($items as $i) {
    //         if (is_string($i->fasilitas)) {
    //             $i->fasilitas = json_decode($i->fasilitas, true);
    //         }
    //     }

    //     return view('penyewa.dashboard', compact('items'));
    // }

    public function dashboard(Request $r)
    {
        $query = Lapangan::where('aktif', true);

        // filter (jika ada) -- contoh singkat
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

        // pastikan fasilitas jadi array (aman jika kolom berformat string JSON)
        foreach ($items as $i) {
            // jika kosong set array kosong
            if (is_null($i->fasilitas)) {
                $i->fasilitas = [];
                continue;
            }

            // jika sudah array/collection biarkan
            if (is_array($i->fasilitas) || $i->fasilitas instanceof \Illuminate\Support\Collection) {
                continue;
            }

            // jika string, coba decode JSON
            if (is_string($i->fasilitas)) {
                $decoded = json_decode($i->fasilitas, true);
                $i->fasilitas = is_array($decoded) ? $decoded : [];
            } else {
                // fallback
                $i->fasilitas = [];
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
        // echo "<script>alert('cek slot controller terpanggil');</script>";
        if (!$r->tanggal || !$r->jam) {
            return response()->json(['available' => true]); // belum memilih lengkap
        }

        $jam = is_array($r->jam) ? $r->jam : [$r->jam];

        $ada = DB::table('booking')
            ->where('lapangan_id', $r->lapangan_id)
            ->where('tanggal', $r->tanggal)
            // ->whereJsonContains('jam', $jam)
            ->where(function ($q) use ($jam) {
                foreach ($jam as $j) {
                    $q->orWhereRaw('? = ANY(jam)', [$j]);
                }
            })
            ->exists();

        return response()->json([
            'available' => !$ada
        ]);
    }
}