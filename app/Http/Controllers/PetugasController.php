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

    public function getData()
    {
        $today = Carbon::today();

        // Antrian yang sedang dipanggil (status = 'dipanggil')
        $currentCalled = Antrian::where('tanggal', $today)
            ->where('status', 'dipanggil')
            ->orderBy('waktu_panggil', 'desc')
            ->first();

        // Antrian yang menunggu (status = 'menunggu')
        $waitingQueues = Antrian::where('tanggal', $today)
            ->where('status', 'menunggu')
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        // Antrian yang sudah terlayani (status = 'selesai')
        $servedCount = Antrian::where('tanggal', $today)
            ->where('status', 'selesai')
            ->count();

        // Antrian yang terlewat (status = 'terlewat')
        $skippedCount = Antrian::where('tanggal', $today)
            ->where('status', 'terlewat')
            ->get();

        // Total antrian hari ini
        $totalCount = Antrian::where('tanggal', $today)->count();

        // Riwayat panggilan (dipanggil, selesai, terlewat)
        $callHistory = Antrian::where('tanggal', $today)
            ->whereIn('status', ['dipanggil', 'selesai', 'terlewat'])
            ->orderBy('waktu_panggil', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'current' => $currentCalled ? [
                'nomor_antrian' => $currentCalled->nomor_antrian,
                'waktu_panggil' => $currentCalled->waktu_panggil ? Carbon::parse($currentCalled->waktu_panggil)->format('H:i') : null,
                'loket' => $currentCalled->id_loket ?? 1
            ] : null,
            'next' => $waitingQueues->count() > 0 ? $waitingQueues->first() : null,
            'totalToday' => $totalCount,
            'servedToday' => $servedCount,
            'skippedToday' => $skippedCount->count(),
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
                    'time' => $q->waktu_panggil ? Carbon::parse($q->waktu_panggil)->format('H:i') : '--',
                    'status' => $q->status === 'dipanggil' ? 'called' : ($q->status === 'selesai' ? 'served' : 'skipped')
                ];
            }),
            'skippedList' => $skippedCount->map(function ($q) {
                return [
                    'number' => $q->nomor_antrian,
                    'time' => $q->waktu_panggil ? Carbon::parse($q->waktu_panggil)->format('H:i') : '--',
                    'officer' => 'Petugas',
                    'note' => 'Tidak hadir setelah panggilan'
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
}
