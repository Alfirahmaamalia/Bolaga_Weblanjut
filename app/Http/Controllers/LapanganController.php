<?php

namespace App\Http\Controllers;

use App\Models\JamOperasional;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LapanganController extends Controller
{
    public function dashboard(Request $r)
    {
        // Hanya tampilkan yang statusnya 'aktif'
        $query = Lapangan::where('status', 'aktif');

        // Filter search
        if ($r->search) {
            $query->where('nama_lapangan', 'ILIKE', "%{$r->search}%");
        }
        // Filter jenis
        if ($r->jenis) {
            $query->where('jenis_olahraga', $r->jenis);
        }
        // Filter lokasi
        if ($r->lokasi) {
            $query->where('lokasi', 'LIKE', '%' . $r->lokasi . '%');
        }
        // Filter harga
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

    // ==========================
    // DETAIL LAPANGAN
    // ==========================
    public function detail($id)
    {
        $lapangan = Lapangan::with(['jam_operasional' => function($query) {
            $query->orderBy('hari', 'asc');
        }])->where('lapangan_id', $id)->firstOrFail();

        // Logika JSON fasilitas
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

    // ==========================
    // FUNGSI PENYEDIA
    // ==========================

    public function create()
    {
        return view('penyedia.tambah_lapangan');
    }

    public function edit($id)
    {
        $lapangan = Lapangan::with('jam_operasional')
            ->where('lapangan_id', $id)
            ->where('penyedia_id', Auth::id())
            ->firstOrFail();

        if (is_string($lapangan->fasilitas)) {
            $decoded = json_decode($lapangan->fasilitas, true);
            $lapangan->fasilitas = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
                ? $decoded : [];
        }

        return view('penyedia.editlapangan', compact('lapangan'));
    }

    // --- PROSES SIMPAN (STORE) ---
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_lapangan'     => 'required|string|max:255',
            'jenis_olahraga'    => 'required|string',
            'harga_perjam'      => 'required|numeric|min:0',
            'lokasi'            => 'required|string',
            'deskripsi'         => 'required|string',
            'foto'              => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'fasilitas'         => 'nullable|array',
            'jadwal'            => 'required|array',
            'nama_qris'         => 'required|string',
            'nmid'              => 'required|string',
            'qrcode_qris'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // TAMBAHAN VALIDASI BUKTI KEPEMILIKAN
            'bukti_kepemilikan' => 'required|file|mimes:pdf|max:2048', 
        ]);

        DB::beginTransaction();

        // Inisialisasi variabel path agar bisa dihapus di catch jika error
        $fotoPath = null;
        $qrisPath = null;
        $dokumenPath = null;

        try {
            // 2. Upload Foto Lapangan
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $ext = $file->getClientOriginalExtension();
                $namaFileFoto = Str::uuid() . '.' . $ext;
                $file->storeAs('lapangan', $namaFileFoto, 'public');
                $fotoPath = 'storage/lapangan/' . $namaFileFoto;
            }

            // 3. Upload QRIS
            if ($request->hasFile('qrcode_qris')) {
                $fileQris = $request->file('qrcode_qris');
                $extQris = $fileQris->getClientOriginalExtension();
                $namaFileQris = Str::uuid() . '.' . $extQris;
                $fileQris->storeAs('qris', $namaFileQris, 'public');
                $qrisPath = 'storage/qris/' . $namaFileQris;
            }

            // 4. Upload Bukti Kepemilikan (PDF)
            if ($request->hasFile('bukti_kepemilikan')) {
                $fileDokumen = $request->file('bukti_kepemilikan');
                $extDokumen = $fileDokumen->getClientOriginalExtension(); // pdf
                $namaFileDokumen = Str::uuid() . '.' . $extDokumen;
                
                // Simpan di folder storage/app/public/dokumen
                $fileDokumen->storeAs('dokumen', $namaFileDokumen, 'public');
                
                // Path untuk database
                $dokumenPath = 'storage/dokumen/' . $namaFileDokumen;
            }

            // 5. Simpan Data Lapangan
            $lapangan = Lapangan::create([
                'penyedia_id'       => Auth::id(),
                'nama_lapangan'     => $request->nama_lapangan,
                'jenis_olahraga'    => $request->jenis_olahraga,
                'harga_perjam'      => $request->harga_perjam,
                'lokasi'            => $request->lokasi,
                'deskripsi'         => $request->deskripsi,
                
                'foto'              => $fotoPath,
                'fasilitas'         => $request->fasilitas,
                'nama_qris'         => $request->nama_qris,
                'nmid'              => $request->nmid,
                'qrcode_qris'       => $qrisPath,
                'bukti_kepemilikan' => $dokumenPath, // Simpan Path PDF
                
                'status'            => 'menunggu validasi',
            ]);

            // 6. Simpan Jam Operasional
            foreach ($request->jadwal as $index => $jadwalData) {
                $isLibur = isset($jadwalData['libur']);
                $jamBuka = $jadwalData['buka'] ?? null;
                $jamTutup = $jadwalData['tutup'] ?? null;

                JamOperasional::create([
                    'lapangan_id' => $lapangan->lapangan_id, 
                    'hari'        => $index,
                    'jam_buka'    => $isLibur ? null : $jamBuka,
                    'jam_tutup'   => $isLibur ? null : $jamTutup,
                    'is_libur'    => $isLibur,
                ]);
            }

            DB::commit();

            return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan berhasil ditambahkan dan menunggu validasi admin!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file fisik jika gagal insert DB
            if ($fotoPath) {
                Storage::disk('public')->delete(str_replace('storage/', '', $fotoPath));
            }
            if ($qrisPath) {
                Storage::disk('public')->delete(str_replace('storage/', '', $qrisPath));
            }
            if ($dokumenPath) {
                Storage::disk('public')->delete(str_replace('storage/', '', $dokumenPath));
            }

            return back()->withInput()->withErrors(['msg' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    // --- PROSES UPDATE (UPDATE) ---
    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::where('lapangan_id', $id)->where('penyedia_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama_lapangan'     => 'required|string|max:255',
            'jenis_olahraga'    => 'required|string',
            'harga_perjam'      => 'required|numeric|min:0',
            'lokasi'            => 'required|string',
            'deskripsi'         => 'required|string',
            'fasilitas'         => 'nullable|array',
            'foto'              => 'nullable|image|max:2048',
            'qrcode_qris'       => 'nullable|image|max:2048',
            'nama_qris'         => 'required|string|max:255',
            'nmid'              => 'required|string|max:20',
            'jadwal'            => 'required|array',
            // Validasi Update PDF (Nullable karena tidak wajib ganti)
            'bukti_kepemilikan' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Logic Update Foto
            if ($request->hasFile('foto')) {
                if ($lapangan->foto && Storage::disk('public')->exists(str_replace('storage/', '', $lapangan->foto))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $lapangan->foto));
                }
                $file = $request->file('foto');
                $filename = 'lapangan_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $filename, 'public'); 
                $lapangan->foto = 'storage/images/' . $filename;
            }

            // Logic Update QRIS
            if ($request->hasFile('qrcode_qris')) {
                if ($lapangan->qrcode_qris && Storage::disk('public')->exists(str_replace('storage/', '', $lapangan->qrcode_qris))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $lapangan->qrcode_qris));
                }
                $file = $request->file('qrcode_qris');
                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $filename, 'public');
                $lapangan->qrcode_qris = 'storage/images/' . $filename;
            }

            // Logic Update Bukti Kepemilikan (PDF)
            if ($request->hasFile('bukti_kepemilikan')) {
                // Hapus file lama jika ada
                if ($lapangan->bukti_kepemilikan && Storage::disk('public')->exists(str_replace('storage/', '', $lapangan->bukti_kepemilikan))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $lapangan->bukti_kepemilikan));
                }
                
                // Upload file baru
                $fileDokumen = $request->file('bukti_kepemilikan');
                $namaFileDokumen = 'dokumen_' . time() . '.' . $fileDokumen->getClientOriginalExtension();
                $fileDokumen->storeAs('dokumen', $namaFileDokumen, 'public');
                
                // Update path di model instance (belum di save ke DB di sini, nanti di method update)
                $lapangan->bukti_kepemilikan = 'storage/dokumen/' . $namaFileDokumen;
            }

            // Logika Status
            $statusToSave = $lapangan->status;
            if ($lapangan->status !== 'menunggu validasi') {
                $statusToSave = $request->has('status') ? 'aktif' : 'non aktif';
            }

            $fasilitasClean = array_filter($request->fasilitas ?? [], fn($v) => !is_null($v) && $v !== '');
            
            $lapangan->update([
                'nama_lapangan'     => $request->nama_lapangan,
                'jenis_olahraga'    => $request->jenis_olahraga,
                'harga_perjam'      => $request->harga_perjam,
                'lokasi'            => $request->lokasi,
                'deskripsi'         => $request->deskripsi,
                'fasilitas'         => json_encode(array_values($fasilitasClean)),
                'nama_qris'         => $request->nama_qris,
                'nmid'              => $request->nmid,
                'status'            => $statusToSave,
                // Kolom foto, qrcode_qris, dan bukti_kepemilikan sudah diupdate di instance $lapangan di atas
                // Tapi perlu di-pass ke array update ini atau biarkan $lapangan->save().
                // Karena kita pakai mass update, amannya kita masukkan lagi variabelnya jika berubah, 
                // tapi karena instance $lapangan sudah di-set property-nya, kita gunakan save() saja untuk properti file, 
                // ATAU masukkan ke array update secara eksplisit:
                'foto'              => $lapangan->foto, 
                'qrcode_qris'       => $lapangan->qrcode_qris,
                'bukti_kepemilikan' => $lapangan->bukti_kepemilikan,
            ]);

            // Update Jadwal
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
        $lap = Lapangan::where('lapangan_id', $id)
            ->where('penyedia_id', Auth::id())
            ->firstOrFail();

        // 1. Hapus Foto
        if ($lap->foto && Storage::disk('public')->exists(str_replace('storage/', '', $lap->foto))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lap->foto));
        }

        // 2. Hapus QRIS
        if ($lap->qrcode_qris && Storage::disk('public')->exists(str_replace('storage/', '', $lap->qrcode_qris))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lap->qrcode_qris));
        }

        // 3. Hapus Bukti Kepemilikan (PDF)
        if ($lap->bukti_kepemilikan && Storage::disk('public')->exists(str_replace('storage/', '', $lap->bukti_kepemilikan))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lap->bukti_kepemilikan));
        }

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

        $hariKe = \Carbon\Carbon::parse($request->tanggal)->dayOfWeek;

        $lapangan = Lapangan::find($request->lapangan_id);
        $jadwal = $lapangan->jam_operasional()->where('hari', $hariKe)->first();

        if (!$jadwal || $jadwal->is_libur) {
            return response()->json([
                'status' => 'libur',
                'message' => 'Maaf, lapangan libur pada tanggal tersebut.'
            ]);
        }

        return response()->json([
            'status' => 'buka',
            'jam_buka' => \Carbon\Carbon::parse($jadwal->jam_buka)->format('H:i'),
            'jam_tutup' => \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H:i'),
        ]);
    }

    public function cekSlot(Request $r)
    {
        if (!$r->tanggal || !$r->jam_mulai) {
            return response()->json(['available' => true]); 
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