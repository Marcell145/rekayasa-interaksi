<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
   public function login(Request $request)// POST
{
    $request->validate([
        'NIM'    => [
            'required',
            'regex:/^[0-9]+$/',
            'min:15',
            'max:15',
        ],
         'password' => [
        'required',
        'min:8',
        
    ],
    ]);


    $user = User::where('NIM', $request->NIM)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Akun tidak ditemukan!',
        ], 404);
    }

    return new UserResource(true, 'Detail akun', $user);
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
    'nama' => [
        'required',
        'min:3',
        'max:25',
        'regex:/^[A-Za-z\s]+$/'
    ],
    // Email: wajib, harus valid format email
    'email_UMM' => [
        'email'
    ],'email_pribadi' => [
        'email'
    ],
    'no_ktp'  => [
        'regex:/^[0-9]+$/',
    ],
    // Nomor telepon: wajib, hanya angka, panjang 10–15 digit
    'no_telp'  => [
        'regex:/^[0-9]+$/',
        'min:10',
        'max:15'
    ],
    'fakultas'  => [
        'required'
    ],
    'program_studi'  => [
        'required'
    ],
    // Password: minimal 8 karakter, harus ada huruf besar, kecil, angka, dan spesial karakter
    'password' => [
        'required',
        'min:8',
        'regex:/^[0-9]+$/'
    ],
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

// Jika email sudah ada di database (tambahan untuk keamanan ekstra)
if (User::where('NIM', $request->NIM)->exists()) {
return response()->json([
'success' => false,
'message' => 'NIM sudah terdaftar!',
], 409); // Conflict
}


$post = User::create([
'NIM' => $request->NIM,
'nama' => $request->nama,
'email_UMM' => $request->email_UMM,
'email_pribadi' => $request->email_pribadi,
'no_ktp' => $request->no_ktp,
'no_telp' => $request->no_telp,
'alamat' => $request->alamat,
'fakultas' => $request->fakultas,
'program_studi' => $request->program_studi,
'password' => $request->password,
]);

//return response
return new UserResource(true, 'Akun Berhasil Ditambahkan!', $post);
}

public function update(Request $request, $NIM) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
    // Nama: wajib, minimal 3 huruf, hanya boleh huruf dan spasi
    'nama' => [
        'required',
        'min:3',
        'max:25',
        'regex:/^[A-Za-z\s]+$/'
    ],
    // Email: wajib, harus valid format email
    'email_UMM' => [
        'email'
    ],'email_pribadi' => [
        'email'
    ],
    'no_ktp'  => [
        'regex:/^[0-9]+$/',
    ],
    // Nomor telepon: wajib, hanya angka, panjang 10–15 digit
    'no_telp'  => [
        'regex:/^[0-9]+$/',
        'min:10',
        'max:15'
    ],
    'fakultas'  => [
        'required'
    ],
    'program_studi'  => [
        'required'
    ],
    // Password: minimal 8 karakter, harus ada huruf besar, kecil, angka, dan spesial karakter
    'password' => [
        'required',
        'min:8',
        'regex:/^[0-9]+$/'
    ],
]);


    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Cari user berdasarkan email (primary key)
    $user =User::find($NIM);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    // Update user
    $user->update([
        'nama' => $request->nama,
        'email_UMM' => $request->email_UMM,
        'email_pribadi' => $request->email_pribadi,
        'no_ktp' => $request->no_ktp,
        'no_telp' => $request->no_telp,
        'alamat' => $request->alamat,
        'fakultas' => $request->fakultas,
        'program_studi' => $request->program_studi,
        'password' => bcrypt($request->password), // hash password
    ]);

    return new UserResource(true, 'Akun Berhasil Diubah!', $user);
}

}


