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
            $jenis = $request->jenis;
            $namaDokter = $request->nama_dokter;

            // ================= VALIDASI =================
            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis antrian wajib diisi'
                ], 400);
            }

            if ($jenis == 'DOKTER' && !$namaDokter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama dokter wajib diisi'
                ], 400);
            }

            // ================= PREFIX =================
            if ($jenis == 'REGISTRASI') {
                $prefix = 'R';
            } else if ($jenis == 'DOKTER') {

                $mappingDokter = [
                    'Dr. Nelyan Mokoginta' => 'N',
                    'Dr. Akbar Patuti'     => 'A',
                ];

                if (isset($mappingDokter[$namaDokter])) {
                    $prefix = $mappingDokter[$namaDokter];
                } else {
                    // fallback kalau dokter baru
                    $nama = preg_replace('/^dr\.?\s*/i', '', $namaDokter);
                    $prefix = strtoupper(substr($nama, 0, 1));
                }
            }

            // ================= QUERY =================
            $query = Antrian::where('tanggal', $tanggal)
                ->where('jenis', $jenis);

            if ($jenis == 'DOKTER') {
                $query->where('nama_dokter', $namaDokter);
            }

            $last = $query->orderBy('id_antrian', 'desc')->first();

            // ================= NOMOR =================
            if ($last) {
                $lastNumber = intval(explode('-', $last->nomor_antrian)[1]);
                $nomor = $lastNumber + 1;
            } else {
                $nomor = 1;
            }

            $nomorFormatted = $prefix . '-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

            // ================= SIMPAN =================
            $antrian = Antrian::create([
                'nomor_antrian' => $nomorFormatted,
                'jenis'         => $jenis,
                'nama_dokter'   => $namaDokter,
                'tanggal'       => $tanggal,
                'status'        => 'menunggu',
                'waktu_ambil'   => now(),
                'waktu_panggil' => null,
                'id_loket'      => null,
                'id_user'       => null
            ]);

            // ================= RESPONSE =================
            return response()->json([
                'success' => true,
                'data' => [
                    'nomor_antrian' => $antrian->nomor_antrian,
                    'jenis' => $jenis,
                    'nama_dokter' => $namaDokter,
                    'tanggal' => $tanggal,
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
