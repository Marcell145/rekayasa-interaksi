<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;
    /**
    * fillable
    *
    * @var array
    */
    protected $fillable = [
    'id',  
    'NIM', 
    'id_matkul_lama', //Foreign key dari tabel KRS
    'id_matkul_baru', //Foreign key dari tabel KRS
    'nama_dosen',
    'ruang',
    'jam',
    ];

      public function krs_lama()
    {
        return $this->hasMany(KRS::class, 'id_matkul', 'id_matkul_lama');
    }

      public function krs_baru()
    {
        return $this->hasMany(KRS::class, 'id_matkul', 'id_matkul_baru');
    }

}
