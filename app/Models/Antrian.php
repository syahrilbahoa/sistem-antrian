<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
     protected $table = 'antrian';
    protected $primaryKey = 'id_antrian';
    protected $keyType = 'int';

   protected $fillable = [
    'nomor_antrian',
    'tanggal',
    'status',
    'waktu_ambil',
    'waktu_panggil',
    'id_loket',
    'id_user'

   ];
}
