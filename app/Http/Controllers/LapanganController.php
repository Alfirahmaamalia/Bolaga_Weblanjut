<?php

namespace App\Http\Controllers;

use App\Models\JamOperasional;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    // --- 1. FORM CREATE ---
    public function create()
    {
        return view('penyedia.tambah_lapangan');
    }

    // --- 2. FORM EDIT ---
    public function edit($id)
    {
        // Ambil data lapangan & jadwalnya
        $lapangan = Lapangan::with('jam_operasional')
            ->where('lapangan_id', $id)
            ->where('penyedia_id', Auth::id())
            ->firstOrFail();

        // Decode fasilitas agar tidak error di view
        if (is_string($lapangan->fasilitas)) {
            $decoded = json_decode($lapangan->fasilitas, true);
            $lapangan->fasilitas = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
                ? $decoded : [];
        }

        return view('penyedia.editlapangan', compact('lapangan'));
    }

    // --- 3. PROSES SIMPAN (STORE) ---
    public function store(Request $request)
    {
        // Copy-paste validasi & logic store dari kodemu yang terakhir
        // (Kode yang kamu kirim sudah benar untuk bagian ini)
        // ...
        // PASTIKAN kamu menggunakan DB::beginTransaction()
    }

    // --- 4. PROSES UPDATE (UPDATE) ---
    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::where('lapangan_id', $id)->where('penyedia_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama_lapangan'  => 'required|string|max:255',
            'jenis_olahraga' => 'required|string',
            'harga_perjam'   => 'required|numeric|min:0',
            'lokasi'         => 'required|string',
            'deskripsi'      => 'required|string',
            'fasilitas'      => 'nullable|array',
            'foto'           => 'nullable|image|max:2048',       // Nullable untuk update
            'qrcode_qris'    => 'nullable|image|max:2048',       // Nullable untuk update
            'nama_qris'      => 'required|string|max:255',
            'nmid'           => 'required|string|max:20',
            'jadwal'         => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // Logic Foto (Hapus lama jika ada baru)
            if ($request->hasFile('foto')) {
                if ($lapangan->foto && Storage::disk('public')->exists($lapangan->foto)) {
                    Storage::disk('public')->delete($lapangan->foto); // Hapus file lama
                }
                // Simpan baru (gunakan storeAs agar nama file bersih)
                $file = $request->file('foto');
                $filename = 'lapangan_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $filename, 'public'); 
                $lapangan->foto = 'storage/images/' . $filename;
            }

            // Logic QRIS (Hapus lama jika ada baru)
            if ($request->hasFile('qrcode_qris')) {
                if ($lapangan->qrcode_qris && Storage::disk('public')->exists($lapangan->qrcode_qris)) {
                    Storage::disk('public')->delete($lapangan->qrcode_qris);
                }
                $file = $request->file('qrcode_qris');
                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $filename, 'public');
                $lapangan->qrcode_qris = 'storage/images/' . $filename;
            }

            // Update Data Utama
            $fasilitasClean = array_filter($request->fasilitas ?? [], fn($v) => !is_null($v) && $v !== '');
            
            $lapangan->update([
                'nama_lapangan'  => $request->nama_lapangan,
                'jenis_olahraga' => $request->jenis_olahraga,
                'harga_perjam'   => $request->harga_perjam,
                'lokasi'         => $request->lokasi,
                'deskripsi'      => $request->deskripsi,
                'fasilitas'      => json_encode(array_values($fasilitasClean)),
                'nama_qris'      => $request->nama_qris,
                'nmid'           => $request->nmid,
                'aktif'          => $request->has('aktif'), // Handle checkbox
            ]);

            // Update Jadwal (Hapus Lama -> Buat Baru)
            JamOperasional::where('lapangan_id', $lapangan->lapangan_id)->delete();

            foreach ($request->jadwal as $hari => $data) {
                $isLibur = isset($data['libur']);
                JamOperasional::create([
                    'lapangan_id' => $lapangan->lapangan_id,
                    'hari'        => $hari,
                    'jam_buka'    => $isLibur ? null : ($data['buka'] ?? null),
                    'jam_tutup'   => $isLibur ? null : ($data['tutup'] ?? null),
                    'is_libur'    => $isLibur,
                ]);
            }

            DB::commit();
            return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal update: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        // 1. Cari data
        $lap = Lapangan::where('lapangan_id', $id)
            ->where('penyedia_id', Auth::id())
            ->firstOrFail();

        // 2. Hapus File Foto (Jika ada)
        if ($lap->foto && Storage::disk('public')->exists(str_replace('storage/', '', $lap->foto))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lap->foto));
        }

        // 3. Hapus File QRIS (Jika ada) - Tambahan agar bersih
        if ($lap->qrcode_qris && Storage::disk('public')->exists(str_replace('storage/', '', $lap->qrcode_qris))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lap->qrcode_qris));
        }

        // 4. Hapus Data dari Database (Permanen)
        // Karena kita sudah set 'onDelete cascade' di migration jam_operasional,
        // maka jadwal terkait otomatis ikut terhapus di database.
        $lap->delete();

        return redirect()->route('penyedia.kelolalapangan')
            ->with('success', 'Lapangan berhasil dihapus secara permanen.');
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