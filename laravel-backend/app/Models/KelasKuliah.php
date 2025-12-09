<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    protected $table = 'kelas_kuliah';

    protected $fillable = [
        'mata_kuliah_id',
        'semester_akademik_id',
        'kode_kelas',
        'kapasitas_max',
        'dosen_id',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function semesterAkademik()
    {
        return $this->belongsTo(SemesterAkademik::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function krsDetail()
    {
        return $this->hasMany(KRSDetail::class);
    }

    public function khsDetail()
    {
        return $this->hasMany(KHSDetail::class);
    }
}