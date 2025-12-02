<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';
    protected $primaryKey = 'lapangan_id'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'penyedia_id',
        'nama_lapangan',
        'jenis_olahraga',
        'harga_perjam',
        'lokasi',
        'deskripsi',
        'fasilitas',
        'foto',
        'qrcode_qris',
        'nama_qris',
        'nmid',
        'bukti_kepemilikan',
        'status',
    ];

    protected $casts = [
        'fasilitas' => 'array',
    ];

    public function jam_operasional()
    {
        return $this->hasMany(JamOperasional::class, 'lapangan_id');
    }

    /**
     * Helper untuk mengambil jadwal hari ini/spesifik
     * @param int $hari (0-6)
     */
    public function getJadwal($hari)
    {
        return $this->jam_operasional()->where('hari', $hari)->first();
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}