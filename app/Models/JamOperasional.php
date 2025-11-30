<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    protected $table = 'jam_operasional';
    protected $primaryKey = 'jam_operasional_id';
    protected $fillable = ['lapangan_id', 'hari', 'jam_buka', 'jam_tutup', 'is_libur'];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
