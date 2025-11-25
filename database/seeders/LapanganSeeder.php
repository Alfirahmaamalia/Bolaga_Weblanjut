<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lapangan::create([
            'penyedia_id' => 1,
            'nama_lapangan' => 'Arena Futsal Nusantara',
            'lokasi' => 'Jl. Merpati No. 10, Jakarta Selatan',
            'jenis_olahraga' => 'Futsal',
            'harga_perjam' => 150000,
            'deskripsi' => 'Lapangan futsal indoor dengan fasilitas lengkap.',
            'fasilitas' => json_encode(['AC', 'Parkir', 'Toilet', 'Kantin']),
            'foto' => 'images/futsal.jpg',
            'aktif' => true,
        ]);

        Lapangan::create([
            'penyedia_id' => 2,
            'nama_lapangan' => 'Badminton Hall Prima',
            'lokasi' => 'Jl. Kenari No. 22, Bandung',
            'jenis_olahraga' => 'Badminton',
            'harga_perjam' => 80000,
            'deskripsi' => 'Lapangan badminton indoor dengan lantai vinyl profesional.',
            'fasilitas' => json_encode(['Parkir', 'Toilet', 'Ruang Tunggu']),
            'foto' => 'images/badminton.jpg',
            'aktif' => true,
        ]);

        Lapangan::create([
            'penyedia_id' => 3,
            'nama_lapangan' => 'Basket Court Galaxy',
            'lokasi' => 'Jl. Melati No. 5, Surabaya',
            'jenis_olahraga' => 'Basket',
            'harga_perjam' => 120000,
            'deskripsi' => 'Lapangan basket outdoor dengan permukaan aspal yang terawat.',
            'fasilitas' => json_encode(['Lampu Malam', 'Parkir', 'Kamar Ganti']),
            'foto' => 'images/basket.jpg',
            'aktif' => true,
        ]);

        Lapangan::create([
            'penyedia_id' => 4,
            'nama_lapangan' => 'Voli Arena Mandiri',
            'lokasi' => 'Jl. Cendrawasih No. 8, Yogyakarta',
            'jenis_olahraga' => 'Voli',
            'harga_perjam' => 100000,
            'deskripsi' => 'Lapangan voli indoor dengan jaring dan net lengkap.',
            'fasilitas' => json_encode(['Toilet', 'Kantin', 'Parkir Luas']),
            'foto' => 'images/volly.jpg',
            'aktif' => true,
        ]);

        Lapangan::create([
            'penyedia_id' => 5,
            'nama_lapangan' => 'Stadion Mini Garuda',
            'lokasi' => 'Jl. Rajawali No. 30, Semarang',
            'jenis_olahraga' => 'Sepak Bola',
            'harga_perjam' => 300000,
            'deskripsi' => 'Lapangan sepak bola rumput sintetis ukuran standar.',
            'fasilitas' => json_encode(['Tribun Penonton', 'Parkir', 'Lampu Malam']),
            'foto' => 'images/lapangan.jpg',
            'aktif' => true,
        ]);

        Lapangan::create([
            'penyedia_id' => 6,
            'nama_lapangan' => 'Tennis Court Harmoni',
            'lokasi' => 'Jl. Anggrek No. 18, Bogor',
            'jenis_olahraga' => 'Tenis',
            'harga_perjam' => 90000,
            'deskripsi' => 'Lapangan tenis outdoor dengan permukaan hard court.',
            'fasilitas' => json_encode(['Toilet', 'Parkir', 'Kantin']),
            'foto' => 'images/tenis.jpg',
            'aktif' => true,
        ]);
    }
}
