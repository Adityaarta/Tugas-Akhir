<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
    private array $satuanUmur = ['km', 'bulan', 'tahun'];

    public function index()
    {
        $sparepart = Sparepart::latest()->get();
        $satuanUmur = $this->satuanUmur;

        return view('sparepart.index', compact('sparepart', 'satuanUmur'));
    }

    public function store(Request $request)
    {
        Sparepart::create($this->validateSparepart($request));

        return redirect('/sparepart')->with('success', 'Data sparepart berhasil ditambah');
    }

    public function edit($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $satuanUmur = $this->satuanUmur;

        return view('sparepart.edit', compact('sparepart', 'satuanUmur'));
    }

    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update($this->validateSparepart($request));

        return redirect('/sparepart')->with('success', 'Data sparepart berhasil diupdate');
    }

    public function destroy($id)
    {
        Sparepart::findOrFail($id)->delete();

        return redirect('/sparepart')->with('success', 'Data sparepart berhasil dihapus');
    }

    private function validateSparepart(Request $request): array
    {
        return $request->validate([
            'nama_sparepart' => ['required', 'string', 'max:255'],
            'jenis_sparepart' => ['nullable', 'string', 'max:255'],
            'umur_pakai' => ['required', 'integer', 'min:0'],
            'satuan_umur' => ['required', Rule::in($this->satuanUmur)],
            'batas_km' => ['nullable', 'integer', 'min:0'],
            'harga_estimasi' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }
}
