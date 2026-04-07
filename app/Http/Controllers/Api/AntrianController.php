<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Log;

class AntrianController extends Controller
{
    public function cetak(Request $request)
    {
        Log::info('➡️ API cetak antrian');

        try {
            $tanggal = now()->toDateString();

            $last = Antrian::where('tanggal', $tanggal)
                ->orderBy('id_antrian', 'desc')
                ->first();

            $nomor = $last
                ? intval(substr($last->nomor_antrian, 2)) + 1
                : 1;

            $nomorFormatted = 'A-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

            $antrian = Antrian::create([
                'nomor_antrian' => $nomorFormatted,
                'nama_dokter'   => $request->nama_dokter,
                'tanggal'       => $tanggal,
                'status'        => 'menunggu',
                'waktu_ambil'   => now(),
                'waktu_panggil' => null,
                'id_loket'      => null,
                'id_user'       => null
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'nomor_antrian' => $antrian->nomor_antrian,
                    'tanggal' => $tanggal,
                    'nama_dokter' => $antrian->nama_dokter,
                    'waktu' => now()->format('H:i')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('❌ API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
}