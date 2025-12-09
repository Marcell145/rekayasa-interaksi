<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KRS extends Model
{
    protected $table = 'k_r_s';
    protected $primaryKey = 'id_matkul';
    public $incrementing = false;

      protected $fillable = [
    'id_matkul', 
    'kelas',
    'jadwal',
    'nilai',
    'kuota',
    'terisi'
    ];

     public function jadwal_lama()
    {
        return $this->hasMany(Jadwal::class, 'id_matkul_lama', 'id_matkul');
    }

     public function jadwal_baru()
    {
        return $this->hasMany(Jadwal::class, 'id_matkul_baru', 'id_matkul');
    }
}
