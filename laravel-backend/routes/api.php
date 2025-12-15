<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    MahasiswaController,
    DosenController,
    JadwalController,
    KelasKuliahController,
    MataKuliahController,
    KeuanganController,
    SemesterAkademikController,
    KHSController
};

// AUTH
Route::post('/login', [MahasiswaController::class, 'login']);
Route::post('/register', [MahasiswaController::class, 'store']);

// MASTER / ADMIN
Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);
Route::apiResource('/dosen', DosenController::class);
Route::apiResource('/kelas-kuliah', KelasKuliahController::class);
Route::apiResource('/mata-kuliah', MataKuliahController::class);


// JADWAL & PRESENSI
Route::apiResource('/jadwal', JadwalController::class)->except(['index', 'destroy']);
Route::put('/presensi', [JadwalController::class, 'presensi']);


// KEUANGAN
Route::prefix('keuangan')->group(function () {
    Route::get('jenis-pembayaran', [KeuanganController::class, 'jenisPembayaran']);
    Route::post('jenis-pembayaran', [KeuanganController::class, 'storeJenisPembayaran']);

    Route::get('tagihan', [KeuanganController::class, 'tagihan']);
    Route::post('tagihan', [KeuanganController::class, 'storeTagihan']);

    Route::get('pembayaran', [KeuanganController::class, 'pembayaran']);
    Route::post('pembayaran', [KeuanganController::class, 'storePembayaran']);
});

//SEMESTER AKADEMIK
Route::get('/semester', [SemesterAkademikController::class, 'index']);
Route::get('/semester/aktif', [SemesterAkademikController::class, 'aktif']);
Route::get('/semester/{id}', [SemesterAkademikController::class, 'show']);

// KHS (READ ONLY)
Route::prefix('mahasiswa/{mahasiswa}')->group(function () {
    Route::get('/khs', [KhsController::class, 'index']);
    Route::get('/khs/semester/{semester}', [KhsController::class, 'show']);
});
