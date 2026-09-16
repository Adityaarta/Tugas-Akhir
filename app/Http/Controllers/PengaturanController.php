<?php

namespace App\Http\Controllers;

use App\Models\PengaturanAplikasi;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = $this->pengaturan();

        return view('pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => ['required', 'string', 'max:255'],
            'nama_instansi' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $this->pengaturan()->update($validated);

        return redirect('/pengaturan')->with('success', 'Pengaturan aplikasi berhasil disimpan');
    }

    private function pengaturan(): PengaturanAplikasi
    {
        return PengaturanAplikasi::firstOrCreate([], [
            'nama_aplikasi' => 'UD Sentosa',
            'nama_instansi' => 'UD Sentosa',
            'email' => 'admin@udsentosa.test',
            'telepon' => '081234567800',
            'alamat' => 'Indonesia',
            'deskripsi' => 'Sistem Pengelolaan Kendaraan Operasional',
        ]);
    }
}
