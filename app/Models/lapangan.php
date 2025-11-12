<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
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
        'foto',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];
}
