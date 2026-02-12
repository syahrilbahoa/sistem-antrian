<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Carbon\Carbon;
use COM;
use Illuminate\Support\Facades\Log;

class AnjunganController extends Controller
{
    public function index()
    {
        return view('anjungan.anjungan');
    }
    public function cetak(Request $request)
    {
        Log::info('➡️ Request cetak antrian masuk');

        try {
            $tanggal = now()->toDateString();

            $last = Antrian::where('tanggal', $tanggal)
                ->orderBy('id_antrian', 'desc')
                ->first();

            $nomor = $last
                ? intval(substr($last->nomor_antrian, 2)) + 1
                : 1;

            $nomorFormatted = 'A-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
            $nama_dokter = $request->nama_dokter;
            $antrian = Antrian::create([
                'nomor_antrian' => $nomorFormatted,
                'nama_dokter' => $request->nama_dokter,
                'tanggal'       => now()->toDateString(),
                'status'        => 'menunggu',
                'waktu_ambil'   => now(),
                'waktu_panggil' => null,
                'id_loket'      => null, // Default loket 1, bisa disesuaikan dengan kebutuhan
                'id_user'       => null
            ]);


            Log::info('✅ Antrian dibuat', $antrian->toArray());

            return response()->json([
                'success' => true,
                'nomor_antrian' => $antrian->nomor_antrian,
                'tanggal' => $tanggal,
                'nama_dokter' => $nama_dokter,
                'waktu' => now()->format('H:i')
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Gagal cetak antrian', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
}
