<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';

    protected $fillable = [
        'kelas_kuliah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang',
    ];

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class);
    }
}