<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KlasifikasiKendaraan;
use App\Services\PrediksiKendaraanApiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class KlasifikasiController extends Controller
{
    public function naiveBayes()
    {
        return $this->index('naive-bayes');
    }

    public function decisionTree()
    {
        return $this->index('decision-tree');
    }

    public function perbandingan()
    {
        $klasifikasi = KlasifikasiKendaraan::with('kendaraan')->latest()->get();
        $total = $klasifikasi->count();
        $nbPerlu = $klasifikasi->where('hasil_naive_bayes', 'Perlu Servis')->count();
        $dtPerlu = $klasifikasi->where('hasil_decision_tree', 'Perlu Servis')->count();
        $sama = $klasifikasi->filter(fn ($item) => $item->hasil_naive_bayes === $item->hasil_decision_tree)->count();

        return view('klasifikasi.perbandingan', compact('klasifikasi', 'total', 'nbPerlu', 'dtPerlu', 'sama'));
    }

    public function store(Request $request, PrediksiKendaraanApiService $service)
    {
        $validated = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'source' => ['required', Rule::in(['prediksi-naive-bayes', 'prediksi-decision-tree'])],
        ]);

        $source = $validated['source'];
        unset($validated['source']);
        $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);
        $dataKlasifikasi = array_merge($validated, [
            'umur_kendaraan' => $kendaraan->umur,
            'jarak_tempuh_tahun' => (int) $kendaraan->jarak_tempuh_tahun,
            'frekuensi_servis_tahun' => (int) $kendaraan->frekuensi_servis_tahun,
            'km_oli' => (int) $kendaraan->km_oli,
            'km_rem' => (int) $kendaraan->km_rem,
            'km_busi' => (int) $kendaraan->km_busi,
            'km_ban' => (int) $kendaraan->km_ban,
        ]);

        try {
            $result = $service->prediksi($kendaraan);
        } catch (RuntimeException $exception) {
            return back()
                ->withErrors(['prediksi_kendaraan' => $exception->getMessage()])
                ->withInput();
        }

        KlasifikasiKendaraan::create(array_merge($dataKlasifikasi, $result['klasifikasi'], [
            'tanggal_klasifikasi' => now()->toDateString(),
        ]));

        return redirect('/'.$source)->with('success', 'Klasifikasi kendaraan berhasil diproses');
    }

    public function destroy($id)
    {
        KlasifikasiKendaraan::findOrFail($id)->delete();

        return back()->with('success', 'Data klasifikasi berhasil dihapus');
    }

    private function index(string $mode)
    {
        $klasifikasi = KlasifikasiKendaraan::with('kendaraan')->latest()->get();
        $kendaraan = Kendaraan::orderBy('no_polisi')->get();
        $title = $mode === 'naive-bayes' ? 'Prediksi Naive Bayes' : 'Prediksi Decision Tree';
        $source = $mode === 'naive-bayes' ? 'prediksi-naive-bayes' : 'prediksi-decision-tree';

        return view('klasifikasi.index', compact('klasifikasi', 'kendaraan', 'title', 'mode', 'source'));
    }
}
