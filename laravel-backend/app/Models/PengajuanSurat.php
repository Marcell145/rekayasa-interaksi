<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'mahasiswa_id',
        'jenis_surat_id',
        'tgl_pengajuan',
        'keterangan_mhs',
        'status',
        'tgl_disetujui',
        'catatan_admin',
        'file_surat_path',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }
}