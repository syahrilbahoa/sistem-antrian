<?php

use App\Http\Controllers\Api\AntrianController;
use Illuminate\Support\Facades\Route;

Route::post('/cetak-antrian', [AntrianController::class, 'cetak']);