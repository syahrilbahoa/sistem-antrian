<?php

use Illuminate\Support\Facades\Route;
use App\Events\PanggilAntrian;
use App\Http\Controllers\{AdminController, AnjunganController, DispleyController, LoginController, PetugasController};


Route::get('/', [LoginController::class, 'index'])->name('home');
// Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

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
