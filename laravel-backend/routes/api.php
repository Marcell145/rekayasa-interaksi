<?php

use App\Http\Controllers\Api\JadwalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::apiResource('/Jadwal', JadwalController::class)->except(['index','stream', 'destroy']);
Route::get('/stream/jadwal', [JadwalController::class, 'stream']);
Route::apiResource('/user', UserController::class)->except(['show']);

// buat route khusus login & register
Route::post('/login', [UserController::class, 'login']);     // login
Route::post('/register', [UserController::class, 'store']);    // register