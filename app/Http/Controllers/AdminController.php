<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Lapangan; // Pastikan model Lapangan ada

class AdminController extends Controller
{
    public function dashboard()
    {
        // Menggunakan count() jauh lebih ringan daripada all() untuk statistik
        $totalUser = User::count();
        
        // Asumsi kamu memiliki model Lapangan. Jika belum, buat modelnya dulu.
        // Jika tabelnya belum ada, hapus baris ini dan set $totalLapangan = 0;
        $totalLapangan = Lapangan::count(); 

        return view('admin.dashboard', compact('totalUser', 'totalLapangan'));
    }

    // 2. Halaman Manajemen User (Sesuai link di tombol View)
    // Route: Route::get('/admin/users', [AdminController::class, 'userManajemen'])->name('admin.usermanajemen');
    public function userManajemen()
    {
        // Gunakan paginate() agar halaman tidak berat jika user sudah ribuan
        $users = User::paginate(10); 
        return view('admin.usermanajemen', compact('users')); // Pastikan view ini ada
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:penyewa,penyedia,admin',
            'password' => 'required|min:8', // Password wajib saat create
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'role' => 'required|in:penyewa,penyedia,admin',
            'password' => 'nullable|min:8',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil diubah');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return view('admin.delete', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus');
    }


    public function validasiLapangan(Request $request)
    {
        // 1. Panggil relasi 'user' yang sudah diperbaiki di Model
        $query = Lapangan::with('user'); 

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Sorting (Sama seperti sebelumnya)
        $lapangan = $query->orderByRaw("
            CASE 
                WHEN status = 'menunggu validasi' THEN 1
                WHEN status = 'aktif' THEN 2
                WHEN status = 'non aktif' THEN 3
                WHEN status = 'ditolak' THEN 4
                ELSE 5
            END
        ")
        ->latest()
        ->get();

        return view('admin.validasilapangan', compact('lapangan'));
    }

    /**
     * Menyetujui Lapangan
     * Route: admin.lapangan.approve
     */
    public function approve($id)
    {
        // Menggunakan findOrFail agar error 404 jika ID tidak ada
        // Laravel otomatis mendeteksi primaryKey 'lapangan_id' dari Model
        $lapangan = Lapangan::findOrFail($id);
        
        $lapangan->status = 'aktif';
        $lapangan->save();

        return redirect()->route('admin.validasilapangan')
            ->with('success', 'Lapangan berhasil divalidasi dan diaktifkan.');
    }

    /**
     * Menolak Lapangan
     * Route: admin.lapangan.reject
     */
    public function reject($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $lapangan->status = 'ditolak';
        $lapangan->save();

        return redirect()->route('admin.validasilapangan')
            ->with('success', 'Pengajuan lapangan telah ditolak.');
    }

    public function show($id)
    {
        // Ambil data lapangan beserta relasi user (penyedia) dan jam operasional
        $lapangan = Lapangan::with(['user', 'jam_operasional'])->findOrFail($id);

        return view('admin.show', compact('lapangan'));
    }
}
