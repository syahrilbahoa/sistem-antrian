<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Admin';
        return view('admin.admin', [
            'title' => $title
        ]);
    }
    public function petugas()
    {
        $title = 'Data Petugas';
        $data = DB::select('select * from users');
        return view('admin.petugas', [
            'title' => $title,
            'data' => $data
        ]);
    }
    public function simpan_petugas(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.petugas')
            ->with('success', 'Data berhasil ditambahkan');
    }
    public function update_pegawai(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Jika password diisi, baru update
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.petugas')
            ->with('success', 'Data berhasil diupdate');
    }
    public function hapus_pegawai($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('admin.petugas')
            ->with('success', 'Data berhasil dihapus');
    }

    public function antrian()
    {
        $title = 'Data Antrian';
        $data = DB::select('select * from antrian left join loket on antrian.id_loket = loket.id_loket');
        return view('admin.antrian', [
            'title' => $title,
            'data' => $data
        ]);
    }
}
