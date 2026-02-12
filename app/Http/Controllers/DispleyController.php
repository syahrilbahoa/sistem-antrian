<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class DispleyController extends Controller
{
    public function index()
    {
        $aktif = Antrian::where('status', 'dipanggil')
            ->latest('waktu_panggil')
            ->first();
        return view('display.display', compact('aktif'));
    }
}
