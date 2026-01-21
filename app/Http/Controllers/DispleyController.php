<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispleyController extends Controller
{
    public function index()
    {
        return view('display.display');
    }
}
