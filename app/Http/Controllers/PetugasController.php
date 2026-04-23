<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PanggilAntrian;
use App\Models\{Antrian, Loket};
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;

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

    public function panggil($nomor_antrian, Request $request)
    {
        $loket_id = $request->input('loket_id');
        $id_user = auth()->user()->id;
        $antrian = Antrian::where('nomor_antrian', $nomor_antrian)->firstOrFail();

        // 🚨 VALIDASI LOKET
        if ($loket_id == 1) {
            // Loket pendaftaran hanya REGISTRASI
            if ($antrian->jenis !== 'REGISTRASI') {
                return response()->json([
                    'message' => 'Loket ini hanya untuk registrasi!'
                ], 403);
            }
        } elseif ($loket_id == 2) {
            // Dokter Nelyan
            if ($antrian->jenis !== 'DOKTER' || stripos($antrian->nama_dokter, 'Nelyan') === false) {
                return response()->json([
                    'message' => 'Ini bukan antrian Dokter Nelyan!'
                ], 403);
            }
        } elseif ($loket_id == 3) {
            // Dokter Akbar
            if ($antrian->jenis !== 'DOKTER' || stripos($antrian->nama_dokter, 'Akbar') === false) {
                return response()->json([
                    'message' => 'Ini bukan antrian Dokter Akbar!'
                ], 403);
            }
        }

        // ✅ Update jika valid
        $antrian->update([
            'status' => 'dipanggil',
            'waktu_panggil' => Carbon::now(),
            'id_loket' => $loket_id,
            'id_user' => $id_user
        ]);

        broadcast(new PanggilAntrian(
            $antrian->nomor_antrian,
            $loket_id
        ));

        return response()->json([
            'message' => 'Antrian dipanggil'
        ]);
    }

    public function getData(Request $request)
    {
        $today = Carbon::today();
        $loket_id = $request->input('loket_id');

        // Base query
        $query = Antrian::where('tanggal', $today);

        // FILTER BERDASARKAN LOKET
        if ($loket_id == 1) {
            $query->where('jenis', 'REGISTRASI');
        } elseif ($loket_id == 2) {
            $query->where('jenis', 'DOKTER')
                ->where('nama_dokter', 'LIKE', '%Nelyan%');
        } elseif ($loket_id == 3) {
            $query->where('jenis', 'DOKTER')
                ->where('nama_dokter', 'LIKE', '%Akbar%');
        }

        // Clone query biar tidak bentrok
        $waitingQueues = (clone $query)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        $currentCalled = (clone $query)
            ->where('status', 'dipanggil')
            ->orderBy('waktu_panggil', 'desc')
            ->first();

        $servedCount = (clone $query)
            ->where('status', 'selesai')
            ->count();

        $skippedCount = (clone $query)
            ->where('status', 'terlewat')
            ->count();

        $totalCount = (clone $query)->count();

        $callHistory = (clone $query)
            ->whereIn('status', ['dipanggil', 'selesai', 'terlewat'])
            ->orderBy('waktu_panggil', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'current' => $currentCalled ? [
                'nomor_antrian' => $currentCalled->nomor_antrian,
                'waktu_panggil' => $currentCalled->waktu_panggil
                    ? Carbon::parse($currentCalled->waktu_panggil)->format('H:i')
                    : null,
                'loket' => $currentCalled->id_loket ?? $loket_id
            ] : null,

            'next' => $waitingQueues->first(),

            'totalToday' => $totalCount,
            'servedToday' => $servedCount,
            'skippedToday' => $skippedCount,

            'queueList' => $waitingQueues->map(function ($q) {
                return [
                    'id' => $q->nomor_antrian,
                    'number' => $q->nomor_antrian,
                    'time' => Carbon::parse($q->waktu_ambil)->format('H:i'),
                    'status' => $q->status
                ];
            }),

            'callHistory' => $callHistory->map(function ($q) {
                return [
                    'number' => $q->nomor_antrian,
                    'time' => $q->waktu_panggil
                        ? Carbon::parse($q->waktu_panggil)->format('H:i')
                        : '--',
                    'status' => $q->status === 'dipanggil'
                        ? 'called'
                        : ($q->status === 'selesai' ? 'served' : 'skipped')
                ];
            })
        ]);
    }

    public function skip($nomor_antrian)
    {
        $antrian = Antrian::where('nomor_antrian', $nomor_antrian)->firstOrFail();

        $antrian->update([
            'status' => 'terlewat',
            'waktu_panggil' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Antrian ditandai terlewat'
        ]);
    }

    public function getLoketList()
    {
        $lokets = Loket::all();

        return response()->json([
            'loketList' => $lokets->map(function ($loket) {
                return [
                    'id_loket' => $loket->id_loket,
                    'nama_loket' => $loket->nama_loket
                ];
            })
        ]);
    }
}
