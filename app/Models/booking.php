<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class booking extends Model
{

    protected $table = 'booking';
    protected $primaryKey = 'booking_id'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'lapangan_id',
        'penyewa_id',
        'tanggal',
        'jam',
        'total_harga',
        'metode_pembayaran',
        'status',
    ];

    protected $casts = [
        // 'jam' => 'array',
        'tanggal' => 'date',
    ];

    protected function setJamAttribute($value)
    {
        // Jika array PHP → ubah ke format Postgres ARRAY
        if (is_array($value)) {
            $this->attributes['jam'] = '{' . implode(',', $value) . '}';
        } else {
            $this->attributes['jam'] = $value;
        }
    }

    public function getJamAttribute($value)
    {
        if ($value === null) return null;

        // Mengubah "{08:00,09:00}" menjadi ["08:00", "09:00"]
        return str_getcsv(trim($value, '{}'));
    }

    // Relasi ke lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id', 'lapangan_id');
    }

    // Relasi ke tabel users sebagai penyewa
    public function penyewa()
    {
        return $this->belongsTo(User::class, 'penyewa_id', 'user_id');
    }
}
