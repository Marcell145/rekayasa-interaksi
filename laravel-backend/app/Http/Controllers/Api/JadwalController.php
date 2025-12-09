<?php

namespace App\Http\Controllers\Api;
use App\Models\Jadwal;
use App\Http\Controllers\Controller;
use App\Http\Resources\JadwalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function show($nim)
{
    $jadwal = Jadwal::with('krs_lama')->with('krs_baru')
        ->where('NIM', $nim)
        ->get();

    if ($jadwal->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => "Jadwal dengan NIM $nim tidak ditemukan"
        ], 404);
    }

    return new JadwalResource(true, "List jadwal NIM $nim", $jadwal);
}


    public function store(Request $request) //POST
{
//define validation rules
$validator = Validator::make($request->all(), [
    'NIM'    => [
            'required',
            'regex:/^[0-9]+$/',
            'min:15',
            'max:15',
    ],
    // Nama: wajib, minimal 3 huruf, hanya boleh huruf dan spasi
    'id_matkul_lama' => [
        'required'
    ],
    // Email: wajib, harus valid format email
    'id_matkul_baru' => [
        'required'
    ],'nama_dosen' => [
        'required'
    ],
    'ruang'  => [
        'required',
    ],
    // Nomor telepon: wajib, hanya angka, panjang 10–15 digit
    'jam'  => [
        'required'
    ],
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = Jadwal::create([
'NIM' => $request->NIM,
'id_matkul_lama' => $request->id_matkul_lama,
'id_matkul_baru' => $request->id_matkul_baru,
'nama_dosen' => $request->nama_dosen,
'ruang' => $request->ruang,
'jam' => $request->jam,
]);

//return response
return new JadwalResource(true, "Jadwal NIM $request->NIM Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $id) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
    'NIM'    => [
            'required',
            'regex:/^[0-9]+$/',
            'min:15',
            'max:15',
    ],
   // Nama: wajib, minimal 3 huruf, hanya boleh huruf dan spasi
    'id_matkul_lama' => [
        'required'
    ],
    // Email: wajib, harus valid format email
    'id_matkul_baru' => [
        'required'
    ],'nama_dosen' => [
        'required'
    ],
    'ruang'  => [
        'required',
    ],
    // Nomor telepon: wajib, hanya angka, panjang 10–15 digit
    'jam'  => [
        'required'
    ],
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Cari user berdasarkan email (primary key)
    $user =Jadwal::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    // Update user
    $user->update([
        'NIM' => $request->NIM,
        'id_matkul_lama' => $request->id_matkul_lama,
        'id_matkul_baru' => $request->id_matkul_baru,
        'nama_dosen' => $request->nama_dosen,
        'ruang' => $request->ruang,
        'jam' => $request->jam,
    ]);

    return new JadwalResource(true, 'Akun Berhasil Diubah!', $user);
}

    public function stream(Request $request)
    {
        ignore_user_abort(false);
        set_time_limit(0); // izinkan loop panjang
        $nim = $request->query('NIM'); // ambil nim dari query parameter

        return response()->stream(function () use ($nim) {
            while (!connection_aborted()) {
                $update = Jadwal::where('NIM', $nim)
                    ->orderBy('updated_at', 'DESC')
                    ->get();
                $lastUpdate = optional($update->first())->id_matkul_lama;
                $latestUpdate = optional($update->first())->id_matkul_baru;

                // Kirim data hanya jika ada perubahan baru
                if ($latestUpdate != $lastUpdate) {
                    $jadwal = Jadwal::with('krs_lama')->with('krs_baru')
                    ->where('NIM', $nim)
                    ->get();

                    echo "Pembaruan Jadwal\n";
                    echo "data: " . json_encode($jadwal) . "\n\n";

                    ob_flush();
                    flush();
                }

                usleep(500000); // cek setiap 10 detik
            }
        }, 200, [
            "Content-Type" => "text/event-stream",
        "Cache-Control" => "no-cache",
        "Connection" => "keep-alive",
        "Access-Control-Allow-Origin" => "*",
        "X-Accel-Buffering" => "no"
        ]);
    }
}
