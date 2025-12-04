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
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id('lapangan_id');
            $table->foreignId('penyedia_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nama_lapangan');
            $table->string('lokasi');
            $table->string('jenis_olahraga');
            $table->integer('harga_perjam');
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->string('foto')->nullable();
            $table->string('qrcode_qris')->nullable();
            $table->string('nama_qris')->nullable();
            $table->string('nmid')->nullable();
            $table->string('bukti_kepemilikan')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};
