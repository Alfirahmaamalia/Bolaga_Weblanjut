<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'lapangan_id' => 1,
            'penyewa_id' => 7,
            'tanggal' => '2025-11-27',
            'jam' => ['10:00', '11:00'],
            'total_harga' => 305000,
            'status' => 'belum bayar',
        ]);

        Booking::create([
            'lapangan_id' => 2,
            'penyewa_id' => 7,
            'tanggal' => '2025-11-30',
            'jam' => ['08:00', '12:00', '15:00'],
            'total_harga' => 245000,
            'status' => 'berhasil',
        ]);

        Booking::create([
            'lapangan_id' => 3,
            'penyewa_id' => 7,
            'tanggal' => '2025-11-29',
            'jam' => ['11:00'],
            'total_harga' => 125000,
            'status' => 'gagal',
        ]);
    }
}
