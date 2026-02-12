<?php

use App\Events\PanggilAntrian;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnjunganController;
use App\Http\Controllers\DispleyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/petugas', [PetugasController::class, 'index']);
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/display', [DispleyController::class, 'index']);
Route::get('/anjungan', [AnjunganController::class, 'index']);
Route::post('/cetak-antrian', [AnjunganController::class, 'cetak'])->name('antrian.cetak');


// Route untuk memanggil antrian berdasarkan nomor antrian (misalnya A-001)
Route::post('/panggil/{nomor_antrian}', [PetugasController::class, 'panggil']);
