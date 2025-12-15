<?php

namespace App\Http\Controllers;

use App\Models\KHS;
use App\Http\Resources\KHSResource;

class KHSController extends Controller
{

    public function index($mahasiswaId)
    {
        $khs = Khs::with([
                'semesterAkademik',
                'detail.kelasKuliah.mataKuliah',
            ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('semester_akademik_id')
            ->get();

        return KhsResource::collection($khs);
    }

    public function show($mahasiswaId, $semesterId)
    {
        $khs = Khs::with([
                'semesterAkademik',
                'detail.kelasKuliah.mataKuliah',
            ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('semester_akademik_id', $semesterId)
            ->firstOrFail();

        return new KhsResource($khs);
    }
}
