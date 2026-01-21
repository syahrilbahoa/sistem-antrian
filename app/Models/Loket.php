<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    protected $table = 'loket';
    protected $primaryKey = 'id_loket';
    protected $keyType = 'int';

   protected $fillable = [
    'nama_loket'
   ];

    

}
