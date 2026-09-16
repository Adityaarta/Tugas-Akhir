<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KlasifikasiKendaraan;
use App\Models\Servis;

class LaporanController extends Controller
{
    public function kendaraan()
    {
        $title = 'Laporan Kendaraan';
        $headers = ['No', 'No Polisi', 'Jenis', 'Merk', 'Tipe', 'Tahun', 'Kilometer', 'Status'];
        $rows = Kendaraan::latest()->get()->map(fn ($item, $index) => [
            $index + 1,
            $item->no_polisi,
            ucfirst($item->jenis ?? 'truk'),
            $item->merk ?? '-',
            $item->tipe ?? '-',
            $item->tahun ?? '-',
            number_format($item->kilometer),
            ucfirst(str_replace('_', ' ', $item->status)),
        ]);

        return view('laporan.table', compact('title', 'headers', 'rows'));
    }

    public function servis()
    {
        $title = 'Laporan Servis';
        $headers = ['No', 'Tanggal', 'No Servis', 'Kendaraan', 'Jenis', 'Mekanik', 'Biaya', 'Status'];
        $rows = Servis::with('kendaraan')->latest('tanggal_servis')->get()->map(fn ($item, $index) => [
            $index + 1,
            optional($item->tanggal_servis)->format('d-m-Y'),
            $item->no_servis,
            $item->kendaraan->no_polisi ?? '-',
            $item->jenis_servis,
            $item->mekanik,
            'Rp '.number_format($item->biaya, 0, ',', '.'),
            $item->status,
        ]);

        return view('laporan.table', compact('title', 'headers', 'rows'));
    }

    public function prediksi()
    {
        $title = 'Laporan Prediksi';
        $headers = ['No', 'Tanggal', 'Kendaraan', 'Naive Bayes', 'Decision Tree', 'Terbaik', 'Rekomendasi'];
        $rows = KlasifikasiKendaraan::with('kendaraan')->latest('tanggal_klasifikasi')->get()->map(fn ($item, $index) => [
            $index + 1,
            optional($item->tanggal_klasifikasi)->format('d-m-Y'),
            $item->kendaraan->no_polisi ?? '-',
            $item->hasil_naive_bayes.' (Akurasi '.$item->probabilitas_nb.'%)',
            $item->hasil_decision_tree.' (Akurasi '.$item->probabilitas_dt.'%)',
            $item->algoritma_terbaik,
            $item->rekomendasi,
        ]);

        return view('laporan.table', compact('title', 'headers', 'rows'));
    }
}
