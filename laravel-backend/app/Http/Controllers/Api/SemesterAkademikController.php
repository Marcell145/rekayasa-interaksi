<?php

namespace App\Http\Controllers;

use App\Models\SemesterAkademik;
use App\Http\Resources\SemesterAkademikResource;

class SemesterAkademikController extends Controller
{
    public function index()
    {
        return SemesterAkademikResource::collection(
            SemesterAkademik::orderBy('kode_semester')->get()
        );
    }

    public function aktif()
    {
        $semester = SemesterAkademik::where('status', 'AKTIF')->firstOrFail();

        return new SemesterAkademikResource($semester);
    }

    public function show($id)
    {
        $semester = SemesterAkademik::findOrFail($id);

        return new SemesterAkademikResource($semester);
    }
}
