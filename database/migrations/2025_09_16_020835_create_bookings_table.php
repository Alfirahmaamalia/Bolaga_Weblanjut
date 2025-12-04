<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('booking', function (Blueprint $table) {
        $table->id('booking_id');
        $table->foreignId('lapangan_id')->constrained('lapangan', 'lapangan_id')->cascadeOnDelete();
        $table->foreignId('penyewa_id')->constrained('users', 'user_id')->cascadeOnDelete();
        $table->date('tanggal');
        $table->bigInteger('total_harga');
        $table->string('bukti_pembayaran')->nullable();
        $table->string('status');
        $table->timestamps();
        });

        // Tambah kolom jam
        // DB::statement('ALTER TABLE booking ADD COLUMN jam TIME[];');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
