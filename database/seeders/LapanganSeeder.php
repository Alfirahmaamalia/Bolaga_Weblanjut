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
        // 1. Stadion GBK
        Lapangan::create([
            'penyedia_id' => 1,
            'nama_lapangan' => 'Stadion Gelora Bung Karno',
            'lokasi' => 'Jl. Senayan No. 1, Jakarta Pusat',
            'jenis_olahraga' => 'Sepak Bola',
            'harga_perjam' => 40000000,
            'deskripsi' => 'Stadion sepak bola standar internasional dengan kapasitas 78.000 penonton, dilengkapi rumput alam jenis Zoysia Matrella, sistem pencahayaan 3000 lux, dan fitur ramah difabel.',
            'fasilitas' => json_encode(['Tribun Penonton', 'Parkir', 'Lampu Malam', 'Rumput Alami Berkualitas']),
            'foto' => 'images/stadion.png',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Stadion Gelora Bung Karno Official',
            'nmid' => 'ID102938475619283',
            'aktif' => true,
        ]);
        
        // 2. Badminton Hall Prima
        Lapangan::create([
            'penyedia_id' => 2,
            'nama_lapangan' => 'Badminton Hall Prima',
            'lokasi' => 'Jl. Kenari No. 22, Bandung',
            'jenis_olahraga' => 'Badminton',
            'harga_perjam' => 80000,
            'deskripsi' => 'Lapangan badminton indoor dengan lantai vinyl profesional.',
            'fasilitas' => json_encode(['Parkir', 'Toilet', 'Ruang Tunggu']),
            'foto' => 'images/badminton.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Prima Badminton Hall',
            'nmid' => 'ID293847561029384',
            'aktif' => true,
        ]);
        
        // 3. Basket Court Galaxy
        Lapangan::create([
            'penyedia_id' => 3,
            'nama_lapangan' => 'Basket Court Galaxy',
            'lokasi' => 'Jl. Melati No. 5, Surabaya',
            'jenis_olahraga' => 'Basket',
            'harga_perjam' => 120000,
            'deskripsi' => 'Lapangan basket outdoor dengan permukaan aspal yang terawat.',
            'fasilitas' => json_encode(['Lampu Malam', 'Parkir', 'Kamar Ganti']),
            'foto' => 'images/basket.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Galaxy Basket Surabaya',
            'nmid' => 'ID384756102938475',
            'aktif' => true,
        ]);
        
        // 4. Voli Arena Mandiri
        Lapangan::create([
            'penyedia_id' => 4,
            'nama_lapangan' => 'Voli Arena Mandiri',
            'lokasi' => 'Jl. Cendrawasih No. 8, Yogyakarta',
            'jenis_olahraga' => 'Voli',
            'harga_perjam' => 100000,
            'deskripsi' => 'Lapangan voli indoor dengan jaring dan net lengkap.',
            'fasilitas' => json_encode(['Toilet', 'Kantin', 'Parkir Luas']),
            'foto' => 'images/volly.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Voli Mandiri Yogyakarta',
            'nmid' => 'ID475610293847561',
            'aktif' => true,
        ]);

        // 5. Arena Futsal Nusantara
        Lapangan::create([
            'penyedia_id' => 5,
            'nama_lapangan' => 'Arena Futsal Nusantara',
            'lokasi' => 'Jl. Rajawali No. 30, Semarang',
            'jenis_olahraga' => 'Futsal',
            'harga_perjam' => 150000,
            'deskripsi' => 'Lapangan futsal indoor dengan fasilitas lengkap.',
            'fasilitas' => json_encode(['AC', 'Parkir', 'Toilet', 'Kantin']),
            'foto' => 'images/futsal.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Nusantara Futsal Semarang',
            'nmid' => 'ID561029384756102',
            'aktif' => true,
        ]);

        // 6. Tennis Court Harmoni
        Lapangan::create([
            'penyedia_id' => 6,
            'nama_lapangan' => 'Tennis Court Harmoni',
            'lokasi' => 'Jl. Anggrek No. 18, Bogor',
            'jenis_olahraga' => 'Tenis',
            'harga_perjam' => 90000,
            'deskripsi' => 'Lapangan tenis outdoor dengan permukaan hard court.',
            'fasilitas' => json_encode(['Toilet', 'Parkir', 'Kantin']),
            'foto' => 'images/tenis.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Harmoni Tennis Bogor',
            'nmid' => 'ID610293847561029',
            'aktif' => true,
        ]);

        // 7. Futsal Harmoni
        Lapangan::create([
            'penyedia_id' => 7,
            'nama_lapangan' => 'Futsal Harmoni',
            'lokasi' => 'Jl. Anggrek No. 18, Kalimantan',
            'jenis_olahraga' => 'Futsal',
            'harga_perjam' => 100000,
            'deskripsi' => 'Lapangan Futsal outdoor dengan permukaan Lapangan Asli.',
            'fasilitas' => json_encode(['Toilet', 'Parkir', 'Kantin']),
            'foto' => 'images/futsal.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Harmoni Futsal Kalimantan',
            'nmid' => 'ID702938475610293',
            'aktif' => true,
        ]);

        // 8. Basketball Nugrah
        Lapangan::create([
            'penyedia_id' => 7,
            'nama_lapangan' => 'Basketball Nugrah',
            'lokasi' => 'Jl. Anggrek No. 18, Lampung',
            'jenis_olahraga' => 'Basket',
            'harga_perjam' => 150000,
            'deskripsi' => 'Lapangan Basket Indoor dengan permukaan hard court.',
            'fasilitas' => json_encode(['Toilet', 'Parkir', 'Kantin']),
            'foto' => 'images/baskett.jpg',
            'qrcode_qris' => 'images/logoPembayaran/kodeqris.png',
            'nama_qris' => 'Nugrah Basketball Lampung',
            'nmid' => 'ID829384756102938',
            'aktif' => true,
        ]);
    }
}