<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lapangan;

class LapanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Arena Futsal Nusantara',
                'jenis_olahraga' => 'Futsal',
                'lokasi' => 'Jakarta Selatan',
                'deskripsi' => 'Lapangan futsal berkualitas tinggi dengan fasilitas lengkap.',
                'harga_perjam' => 150000,
                'foto' => 'images/futsal.jpg',
                'fasilitas' => json_encode(['AC', 'Parkir', 'Toilet', 'Kantin']),
                'aktif' => true,
            ],
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Satria Nugraha Badminton',
                'jenis_olahraga' => 'Badminton',
                'lokasi' => 'Tanjung Karang Pusat',
                'deskripsi' => 'Lapangan badminton indoor dengan raket pinjaman.',
                'harga_perjam' => 250000,
                'foto' => 'images/badminton.jpg',
                'fasilitas' => json_encode(['AC', 'Raket Pinjaman', 'Toilet', 'Kantin']),
                'aktif' => true,
            ],
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Satria Nugraha Basket',
                'jenis_olahraga' => 'Basket',
                'lokasi' => 'Tanjung Karang Pusat',
                'deskripsi' => 'Lapangan basket outdoor dengan tribun.',
                'harga_perjam' => 250000,
                'foto' => 'images/baskett.jpg',
                'fasilitas' => json_encode(['AC', 'Parkir', 'Toilet', 'Kantin']),
                'aktif' => true,
            ],
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Samudra Volley Court',
                'jenis_olahraga' => 'Voli',
                'lokasi' => 'Bandung',
                'deskripsi' => 'Lapangan voli dengan pemandangan laut.',
                'harga_perjam' => 200000,
                'foto' => 'images/volly.jpg',
                'fasilitas' => json_encode(['Parkir', 'Toilet', 'Kantin']),
                'aktif' => true,
            ],
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Garuda Soccer Field',
                'jenis_olahraga' => 'Sepak Bola',
                'lokasi' => 'Depok',
                'deskripsi' => 'Lapangan sepak bola dengan rumput sintetis.',
                'harga_perjam' => 350000,
                'foto' => 'images/lapangan.jpg',
                'fasilitas' => json_encode(['Rumput Sintetis', 'Parkir Luas', 'Tribun']),
                'aktif' => true,
            ],
            [
                'penyedia_id' => 1,
                'nama_lapangan' => 'Galaxy Tennis Court',
                'jenis_olahraga' => 'Tenis',
                'lokasi' => 'Bekasi',
                'deskripsi' => 'Lapangan tenis dengan fasilitas premium.',
                'harga_perjam' => 180000,
                'foto' => 'images/tenis.jpg',
                'fasilitas' => json_encode(['Rumput Sintetis', 'Toilet', 'Kantin']),
                'aktif' => true,
            ],
        ];

        Lapangan::insert($data);
    }
}
