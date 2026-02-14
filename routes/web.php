<?php

use Illuminate\Support\Facades\Route;
use App\Events\PanggilAntrian;
use App\Http\Controllers\{AdminController, AnjunganController, DispleyController, LoginController, PetugasController};


Route::get('/', [LoginController::class, 'index'])->name('home');
// Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::get('/petugas', [PetugasController::class, 'index']);

    // admin 
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashbord');
    Route::get('/admin/petugas', [AdminController::class, 'petugas'])->name('admin.petugas');
    Route::get('/admin/antrian', [AdminController::class, 'antrian'])->name('admin.antrian');
    Route::post('/admin/simpan_petugas', [AdminController::class, 'simpan_petugas'])->name('admin.simpan.petugas');
    Route::put('/admin/petugas/edit/{id}', [AdminController::class, 'update_pegawai'])
        ->name('pegawai.update');
    Route::delete('/admin/petugas/hapus/{id}', [AdminController::class, 'hapus_pegawai'])
        ->name('pegawai.hapus');

    //end admin

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/display', [DispleyController::class, 'index']);
Route::get('/anjungan', [AnjunganController::class, 'index']);
Route::post('/cetak-antrian', [AnjunganController::class, 'cetak'])->name('antrian.cetak');


// Route untuk memanggil antrian berdasarkan nomor antrian (misalnya A-001)
Route::post('/panggil/{nomor_antrian}', [PetugasController::class, 'panggil']);
