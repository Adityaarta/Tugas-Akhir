<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Services\KendaraanStatusService;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function __construct(private KendaraanStatusService $kendaraanStatusService)
    {
    }

    public function index()
    {
        $this->kendaraanStatusService->refreshAll();

        $kendaraan = Kendaraan::latest()->get();

        $total = Kendaraan::count();
        $aktif = Kendaraan::where('status','aktif')->count();
        $dipakai = Kendaraan::where('status','dipakai')->count();
        $servis = Kendaraan::where('status','dalam_servis')->count();
        $tidakAktif = Kendaraan::where('status','tidak_aktif')->count();

        return view('kendaraan.index', compact(
            'kendaraan','total','aktif','dipakai','servis','tidakAktif'
        ));
    }

    public function create()
    {
        return view('kendaraan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required',
            'jenis' => ['required', Rule::in(['truk', 'mobil'])],
            'merk' => 'required',
            'tipe' => 'nullable',
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.now()->year],
            'kilometer' => ['required', 'integer', 'min:0'],
            'jarak_tempuh_tahun' => ['required', 'integer', 'min:0'],
            'frekuensi_servis_tahun' => ['required', 'integer', 'min:0'],
            'km_oli' => ['required', 'integer', 'min:0'],
            'km_rem' => ['required', 'integer', 'min:0'],
            'km_busi' => ['required', 'integer', 'min:0'],
            'km_ban' => ['required', 'integer', 'min:0'],
            'riwayat_perawatan' => ['required', Rule::in(['Baik', 'Cukup', 'Buruk'])],
            'jumlah_keluhan' => ['required', 'integer', 'min:0'],
            'riwayat_kecelakaan' => ['required', 'integer', 'min:0'],
            'kondisi_rem' => ['required', Rule::in(['Baik', 'Aus'])],
            'kondisi_ban' => ['required', Rule::in(['Baik', 'Aus'])],
            'kondisi_aki' => ['required', Rule::in(['Baik', 'Lemah'])],
        ]);

        Kendaraan::create([
            'no_polisi' => $request->no_polisi,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
            'kilometer' => $request->kilometer,
            'jarak_tempuh_tahun' => $request->jarak_tempuh_tahun,
            'frekuensi_servis_tahun' => $request->frekuensi_servis_tahun,
            'km_oli' => $request->km_oli,
            'km_rem' => $request->km_rem,
            'km_busi' => $request->km_busi,
            'km_ban' => $request->km_ban,
            'riwayat_perawatan' => $request->riwayat_perawatan,
            'jumlah_keluhan' => $request->jumlah_keluhan,
            'riwayat_kecelakaan' => $request->riwayat_kecelakaan,
            'kondisi_rem' => $request->kondisi_rem,
            'kondisi_ban' => $request->kondisi_ban,
            'kondisi_aki' => $request->kondisi_aki,
            'status' => 'aktif',
        ]);

        return redirect('/kendaraan')->with('success','Data berhasil ditambah');
    }

    // DETAIL (VIEW PAGE)
    public function show($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('kendaraan.show', compact('kendaraan'));
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'no_polisi' => 'required',
            'jenis' => ['required', Rule::in(['truk', 'mobil'])],
            'merk' => 'required',
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.now()->year],
            'kilometer' => ['required', 'integer', 'min:0'],
            'jarak_tempuh_tahun' => ['required', 'integer', 'min:0'],
            'frekuensi_servis_tahun' => ['required', 'integer', 'min:0'],
            'km_oli' => ['required', 'integer', 'min:0'],
            'km_rem' => ['required', 'integer', 'min:0'],
            'km_busi' => ['required', 'integer', 'min:0'],
            'km_ban' => ['required', 'integer', 'min:0'],
            'riwayat_perawatan' => ['required', Rule::in(['Baik', 'Cukup', 'Buruk'])],
            'jumlah_keluhan' => ['required', 'integer', 'min:0'],
            'riwayat_kecelakaan' => ['required', 'integer', 'min:0'],
            'kondisi_rem' => ['required', Rule::in(['Baik', 'Aus'])],
            'kondisi_ban' => ['required', Rule::in(['Baik', 'Aus'])],
            'kondisi_aki' => ['required', Rule::in(['Baik', 'Lemah'])],
            'status' => ['required', Rule::in(['aktif', 'dipakai', 'dalam_servis', 'tidak_aktif'])],
        ]);

        $kendaraan->update([
            'no_polisi' => $request->no_polisi,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
            'kilometer' => $request->kilometer,
            'jarak_tempuh_tahun' => $request->jarak_tempuh_tahun,
            'frekuensi_servis_tahun' => $request->frekuensi_servis_tahun,
            'km_oli' => $request->km_oli,
            'km_rem' => $request->km_rem,
            'km_busi' => $request->km_busi,
            'km_ban' => $request->km_ban,
            'riwayat_perawatan' => $request->riwayat_perawatan,
            'jumlah_keluhan' => $request->jumlah_keluhan,
            'riwayat_kecelakaan' => $request->riwayat_kecelakaan,
            'kondisi_rem' => $request->kondisi_rem,
            'kondisi_ban' => $request->kondisi_ban,
            'kondisi_aki' => $request->kondisi_aki,
            'status' => $request->status,
        ]);

        if ($kendaraan->status !== Kendaraan::STATUS_TIDAK_AKTIF) {
            $this->kendaraanStatusService->refresh($kendaraan->id);
        }

        return redirect('/kendaraan')->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return redirect('/kendaraan')->with('success','Data berhasil dihapus');
    }

    // AJAX DETAIL (optional)
    public function get($id)
    {
        return response()->json(Kendaraan::findOrFail($id));
    }
}
