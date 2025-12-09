<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'nama_dosen',
        'email',
        'no_hp',
    ];

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class);
    }
}