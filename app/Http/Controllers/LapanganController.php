<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
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
        // pastikan selalu ada nilai boolean (0/1)
        $v['aktif'] = $request->boolean('aktif'); // akan menghasilkan true/false (cast ke 1/0 oleh DB)

        if ($request->hasFile('foto')) {
            $v['foto'] = 'storage/'.$request->file('foto')->store('lapangan','public');
        } else {
            $v['foto'] = 'images/lapangan.jpg';
        }

        Lapangan::create($v);

        return redirect()->route('penyedia.kelolalapangan')->with('success','Lapangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
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

        // Hapus foto lama jika ada foto baru
        if ($request->hasFile('foto')) {
            if ($lap->foto && Storage::disk('public')->exists($lap->foto)) {
                Storage::disk('public')->delete($lap->foto);
            }
            $v['foto'] = $request->file('foto')->store('lapangan','public');
        }

        $lap->update($v);
        return redirect()->route('penyedia.kelolalapangan')->with('success','Lapangan diperbarui.');
    }

    public function destroy($id)
    {
        $lap = Lapangan::where('lapangan_id', $id)->where('penyedia_id', auth()->id())->firstOrFail();
        
        // Hapus foto saat lapangan dihapus
        if ($lap->foto && Storage::disk('public')->exists($lap->foto)) {
            Storage::disk('public')->delete($lap->foto);
        }
        
        $lap->delete();
        return redirect()->route('penyedia.kelolalapangan')->with('success','Lapangan berhasil dihapus.');
    }
}
