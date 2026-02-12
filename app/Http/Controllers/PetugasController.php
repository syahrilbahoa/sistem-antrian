<?php

namespace App\Http\Controllers;

use App\Events\PanggilAntrian;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function index()
    {
        // Ambil antrian hari ini yang belum dipanggil
        $antrian_hari_ini = Antrian::where('tanggal', Carbon::today())
            ->whereIn('status', ['menunggu', 'diproses']) // Sesuaikan dengan status yang relevan
            ->orderBy('created_at', 'asc')
            ->get();

        return view('petugas.petugas', compact('antrian_hari_ini'));
    }

    public function panggil($nomor_antrian)
    {
        // Karena id_antrian adalah nomor antrian (misalnya A-001), bukan ID numerik
        $antrian = Antrian::where('nomor_antrian', $nomor_antrian)->firstOrFail();

        // update database
        $antrian->update([
            'status' => 'dipanggil',
            'waktu_panggil' => Carbon::now()
        ]);

        // broadcast ke display - pastikan id_loket tidak NULL
        $loket = $antrian->id_loket ?? 1; // Gunakan loket 1 sebagai default jika NULL

        broadcast(new PanggilAntrian(
            $antrian->nomor_antrian,
            $loket
        ));

        return response()->json([
            'message' => 'Antrian dipanggil'
        ]);
    }
}
