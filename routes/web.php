<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnjunganController;
use App\Http\Controllers\DispleyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [LoginController::class, 'index']);
Route::get('/', [LoginController::class, 'index']);

Route::get('/display', [DispleyController::class, 'index']);

Route::get('/petugas', [PetugasController::class, 'index']);

Route::get('/anjungan', [AnjunganController::class, 'index']);

Route::get('/admin', [AdminController::class, 'index']);
