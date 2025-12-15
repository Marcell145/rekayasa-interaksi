<?php

use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\KelasKuliahController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MataKuliahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/Jadwal', JadwalController::class)->except(['index', 'destroy']);
Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);
Route::apiResource('/dosen', DosenController::class);
Route::apiResource('/kelasKuliah', KelasKuliahController::class);
Route::apiResource('/matkul', MataKuliahController::class);

Route::post('/login', [MahasiswaController::class, 'login']);     // login
Route::post('/register', [MahasiswaController::class, 'store']);    // register
Route::put('/presensi', [JadwalController::class, 'presensi']);     //presensi