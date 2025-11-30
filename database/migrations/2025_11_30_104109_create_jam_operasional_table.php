<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jam_operasional', function (Blueprint $table) {
            $table->id('jam_operasional_id');
            
            // Relasi ke tabel lapangan
            $table->foreignId('lapangan_id')->constrained('lapangan', 'lapangan_id')->onDelete('cascade');
            
            // Hari: Kita pakai angka agar mudah dicocokkan dengan coding (Carbon)
            // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            $table->unsignedTinyInteger('hari'); 
            
            // Jam Buka & Tutup
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            
            // Penanda Libur (Opsional, tapi sangat berguna)
            $table->boolean('is_libur')->default(false);

            $table->timestamps();

            // Mencegah duplikasi hari untuk satu lapangan (Satu lapangan tidak boleh punya 2 baris untuk hari Senin)
            $table->unique(['lapangan_id', 'hari']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_operasional');
    }
};
