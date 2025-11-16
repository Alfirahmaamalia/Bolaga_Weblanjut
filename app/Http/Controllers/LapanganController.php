<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    // DASHBOARD PENYEWA — FINAL
    public function dashboard()
    {
        // Helper gambar
        $img = function (string $filename, string $fallback) {
            return file_exists(public_path('images/' . $filename))
                ? asset('images/' . $filename)
                : $fallback;
        };

        // Data dummy (sementara)
        $cards = [
            [
                'jenis' => 'Futsal',
                'nama' => 'Arena Futsal Nusantara',
                'harga' => 150000,
                'lokasi' => 'Jakarta Selatan',
                'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
                'gambar' => $img('futsal.jpg', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200'),
            ],
            [
                'jenis' => 'Badminton',
                'nama' => 'Satria Nugraha Badminton',
                'harga' => 250000,
                'lokasi' => 'Tanjung Karang Pusat',
                'fasilitas' => ['AC', 'Raket Pinjaman', 'Toilet', 'Kantin'],
                'gambar' => $img('badminton.jpg', asset('images/lapangan.jpg')),
            ],
            [
                'jenis' => 'Basket',
                'nama' => 'Satria Nugraha Basket',
                'harga' => 250000,
                'lokasi' => 'Tanjung Karang Pusat',
                'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
                'gambar' => $img('basket.jpg', 'https://images.unsplash.com/photo-1531312267124-2f7f7486f7b0?w=1200'),
            ],
            [
                'jenis' => 'Voli',
                'nama' => 'Samudra Volley Court',
                'harga' => 200000,
                'lokasi' => 'Bandung',
                'fasilitas' => ['Parkir', 'Toilet', 'Kantin'],
                'gambar' => $img('voli.jpg', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200'),
            ],
            [
                'jenis' => 'Sepak Bola',
                'nama' => 'Garuda Soccer Field',
                'harga' => 350000,
                'lokasi' => 'Depok',
                'fasilitas' => ['Rumput Sintetis', 'Parkir Luas', 'Tribun'],
                'gambar' => 'https://images.unsplash.com/photo-1509025673553-26b5c0c02f66?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'jenis' => 'Mini Soccer',
                'nama' => 'Galaxy Mini Soccer',
                'harga' => 180000,
                'lokasi' => 'Bekasi',
                'fasilitas' => ['Rumput Sintetis', 'Toilet', 'Kantin'],
                'gambar' => 'https://images.unsplash.com/photo-1521412644187-c49fa049e84d?q=80&w=1200&auto=format&fit=crop'
            ],
        ];

        $items = array_merge($cards, $cards);

        return view('penyewa.dashboard', compact('items'));
    }

    public function detail($id)
{
    $lapangan = Lapangan::findOrFail($id);

    return view('penyewa.detail', compact('lapangan'));
}



    // ============================
    // FUNGSI PENYEDIA (SUDAH ADA)
    // ============================

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
        $lap = Lapangan::where('lapangan_id', $id)->where('penyedia_id', auth()->id())->firstOrFail();

        if ($lap->foto && Storage::disk('public')->exists($lap->foto)) {
            Storage::disk('public')->delete($lap->foto);
        }

        $lap->delete();
        return redirect()->route('penyedia.kelolalapangan')->with('success', 'Lapangan berhasil dihapus.');
    }
}
