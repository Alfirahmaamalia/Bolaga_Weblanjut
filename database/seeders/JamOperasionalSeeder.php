<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JamOperasional;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu agar tidak duplikat saat dijalankan ulang
        // DB::statement('TRUNCATE TABLE jam_operasional RESTART IDENTITY CASCADE'); // Untuk PostgreSQL
        // Atau cara umum:
        JamOperasional::truncate();

        // -----------------------------------------------------------
        // 1. Stadion Gelora Bung Karno (Jakarta)
        // Logika: Senin Tutup (Perawatan Rumput), Hari lain buka standar sore
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 1, 
            bukaWeekday: '08:00', tutupWeekday: '17:00', 
            bukaWeekend: '07:00', tutupWeekend: '18:00', 
            liburDays: [1] // 1 = Senin
        );

        // -----------------------------------------------------------
        // 2. Badminton Hall Prima (Bandung)
        // Logika: Buka setiap hari sampai malam (Olahraga indoor populer)
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 2, 
            bukaWeekday: '09:00', tutupWeekday: '22:00', 
            bukaWeekend: '08:00', tutupWeekend: '23:00'
        );

        // -----------------------------------------------------------
        // 3. Basket Court Galaxy (Surabaya) - Outdoor
        // Logika: Buka sore sampai malam (karena outdoor panas siang hari), Jumat Libur
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 3, 
            bukaWeekday: '15:00', tutupWeekday: '22:00', // Buka sore aja
            bukaWeekend: '07:00', tutupWeekend: '22:00', // Weekend pagi bisa
            liburDays: [5] // 5 = Jumat
        );

        // -----------------------------------------------------------
        // 4. Voli Arena Mandiri (Yogyakarta)
        // Logika: Jam kerja standar, Minggu libur (hari keluarga pemilik)
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 4, 
            bukaWeekday: '08:00', tutupWeekday: '20:00', 
            bukaWeekend: '08:00', tutupWeekend: '21:00',
            liburDays: [0] // 0 = Minggu
        );

        // -----------------------------------------------------------
        // 5. Arena Futsal Nusantara (Semarang)
        // Logika: Buka Full Day sampai tengah malam (bisnis keras)
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 5, 
            bukaWeekday: '08:00', tutupWeekday: '23:59', 
            bukaWeekend: '08:00', tutupWeekend: '23:59'
        );

        // -----------------------------------------------------------
        // 6. Tennis Court Harmoni (Bogor)
        // Logika: Pagi sekali sudah buka (udara Bogor segar), tutup sore
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 6, 
            bukaWeekday: '06:00', tutupWeekday: '18:00', 
            bukaWeekend: '06:00', tutupWeekend: '18:00'
        );

        // -----------------------------------------------------------
        // 7. Futsal Harmoni (Kalimantan)
        // Logika: Standar Futsal
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 7, 
            bukaWeekday: '10:00', tutupWeekday: '22:00', 
            bukaWeekend: '08:00', tutupWeekend: '23:00'
        );

        // -----------------------------------------------------------
        // 8. Basketball Nugrah (Lampung)
        // Logika: Buka standar
        // -----------------------------------------------------------
        $this->buatJadwal(
            lapanganId: 8, 
            bukaWeekday: '09:00', tutupWeekday: '21:00', 
            bukaWeekend: '08:00', tutupWeekend: '22:00'
        );
    }

    /**
     * Helper function untuk membuat jadwal seminggu penuh
     */
    private function buatJadwal($lapanganId, $bukaWeekday, $tutupWeekday, $bukaWeekend, $tutupWeekend, $liburDays = [])
    {
        // 0 = Minggu, 1 = Senin, ... 6 = Sabtu
        for ($hari = 0; $hari <= 6; $hari++) {
            
            // Cek apakah hari ini libur
            if (in_array($hari, $liburDays)) {
                JamOperasional::create([
                    'lapangan_id' => $lapanganId,
                    'hari' => $hari,
                    'jam_buka' => null,
                    'jam_tutup' => null,
                    'is_libur' => true
                ]);
                continue; // Lanjut ke hari berikutnya
            }

            // Cek Weekend (Minggu=0 atau Sabtu=6)
            $isWeekend = ($hari === 0 || $hari === 6);

            JamOperasional::create([
                'lapangan_id' => $lapanganId,
                'hari' => $hari,
                'jam_buka' => $isWeekend ? $bukaWeekend : $bukaWeekday,
                'jam_tutup' => $isWeekend ? $tutupWeekend : $tutupWeekday,
                'is_libur' => false
            ]);
        }
    }
}