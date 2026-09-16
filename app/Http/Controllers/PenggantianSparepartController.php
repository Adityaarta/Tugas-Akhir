<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\PenggantianSparepart;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggantianSparepartController extends Controller
{
    private array $kondisi = ['baik', 'aus', 'rusak', 'diganti'];

    public function index()
    {
        $penggantian = PenggantianSparepart::with(['kendaraan', 'sparepart'])->latest()->get();
        $kendaraan = Kendaraan::where('status', '!=', 'tidak_aktif')->orderBy('no_polisi')->get();
        $sparepart = Sparepart::orderBy('nama_sparepart')->get();
        $kondisi = $this->kondisi;

        $total = PenggantianSparepart::count();
        $bulanIni = PenggantianSparepart::whereMonth('tanggal_penggantian', now()->month)
            ->whereYear('tanggal_penggantian', now()->year)
            ->count();
        $totalBiaya = PenggantianSparepart::sum('biaya');

        return view('penggantian-sparepart.index', compact(
            'penggantian',
            'kendaraan',
            'sparepart',
            'kondisi',
            'total',
            'bulanIni',
            'totalBiaya'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePenggantian($request);
        $validated = $this->attachKmGantiBerikutnya($validated);
        $penggantian = PenggantianSparepart::create($validated);

        $this->updateKilometerKendaraan($penggantian);

        return redirect('/penggantian-sparepart')->with('success', 'Data penggantian sparepart berhasil ditambah');
    }

    public function edit($id)
    {
        $penggantian = PenggantianSparepart::findOrFail($id);
        $kendaraan = Kendaraan::where('status', '!=', 'tidak_aktif')->orderBy('no_polisi')->get();
        $sparepart = Sparepart::orderBy('nama_sparepart')->get();
        $kondisi = $this->kondisi;

        return view('penggantian-sparepart.edit', compact('penggantian', 'kendaraan', 'sparepart', 'kondisi'));
    }

    public function update(Request $request, $id)
    {
        $penggantian = PenggantianSparepart::findOrFail($id);
        $validated = $this->attachKmGantiBerikutnya($this->validatePenggantian($request));
        $penggantian->update($validated);

        $this->updateKilometerKendaraan($penggantian);

        return redirect('/penggantian-sparepart')->with('success', 'Data penggantian sparepart berhasil diupdate');
    }

    public function destroy($id)
    {
        PenggantianSparepart::findOrFail($id)->delete();

        return redirect('/penggantian-sparepart')->with('success', 'Data penggantian sparepart berhasil dihapus');
    }

    private function validatePenggantian(Request $request): array
    {
        return $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'sparepart_id' => ['required', 'exists:sparepart,id'],
            'tanggal_penggantian' => ['required', 'date'],
            'kilometer_penggantian' => ['nullable', 'integer', 'min:0'],
            'umur_sparepart' => ['nullable', 'integer', 'min:0'],
            'kondisi_sparepart' => ['nullable', Rule::in($this->kondisi)],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }

    private function attachKmGantiBerikutnya(array $validated): array
    {
        $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);
        $sparepart = Sparepart::findOrFail($validated['sparepart_id']);
        $kilometer = $validated['kilometer_penggantian'] ?? $kendaraan->kilometer ?? 0;

        $validated['kilometer_penggantian'] = $kilometer;
        $validated['umur_sparepart'] = $validated['umur_sparepart'] ?? 0;
        $validated['kondisi_sparepart'] = $validated['kondisi_sparepart'] ?? 'diganti';
        $validated['km_ganti_berikutnya'] = $kilometer + (int) ($sparepart->batas_km ?? 0);
        $validated['biaya'] = ((float) ($validated['biaya'] ?? 0)) > 0
            ? $validated['biaya']
            : ($sparepart->harga_estimasi ?? 0);

        return $validated;
    }

    private function updateKilometerKendaraan(PenggantianSparepart $penggantian): void
    {
        $kendaraan = Kendaraan::find($penggantian->kendaraan_id);

        if ($kendaraan && $penggantian->kilometer_penggantian > (int) $kendaraan->kilometer) {
            $kendaraan->update(['kilometer' => $penggantian->kilometer_penggantian]);
        }
    }
}
