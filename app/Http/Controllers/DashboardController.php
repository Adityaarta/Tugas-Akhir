<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\KlasifikasiKendaraan;
use App\Models\Sopir;
use App\Models\Servis;
use App\Services\KendaraanStatusService;

class DashboardController extends Controller
{
    public function __construct(private KendaraanStatusService $kendaraanStatusService)
    {
    }

    public function index()
    {
        $this->kendaraanStatusService->refreshAll();

        // ======================
        // KPI
        // ======================
        $totalKendaraan = Kendaraan::count();
        $totalSopir = Sopir::count();

        $servisBulanIni = Servis::whereMonth('tanggal_servis', now()->month)
            ->whereYear('tanggal_servis', now()->year)
            ->count();

        $latestKlasifikasiIds = KlasifikasiKendaraan::selectRaw('MAX(id)')
            ->groupBy('kendaraan_id');

        $kendaraanPerluServisIds = KlasifikasiKendaraan::query()
            ->whereIn('id', $latestKlasifikasiIds)
            ->where(function ($query) {
                $query->where('hasil_naive_bayes', 'Perlu Servis')
                    ->orWhere('hasil_decision_tree', 'Perlu Servis');
            })
            ->pluck('kendaraan_id');

        $perluServisQuery = Kendaraan::query()
            ->where(function ($query) use ($kendaraanPerluServisIds) {
                $query->where('status', 'dalam_servis')
                    ->orWhereIn('id', $kendaraanPerluServisIds);
            });

        $perluServis = (clone $perluServisQuery)->count();

        // ======================
        // TABLE: KENDARAAN PERLU SERVIS
        // ======================
        $kendaraanPerluServis = (clone $perluServisQuery)
            ->orderByDesc('kilometer')
            ->limit(8)
            ->get();

        // ======================
        // RIWAYAT SERVIS
        // ======================
        $riwayatServis = Servis::with('kendaraan')
            ->latest('tanggal_servis')
            ->limit(8)
            ->get();

        // ======================
        // CHART SERVIS PER BULAN
        // ======================
        $chartData = Servis::selectRaw('MONTH(tanggal_servis) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_servis', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $total = [];

        foreach (range(1, 12) as $month) {
            $total[] = (int) ($chartData[$month] ?? 0);
        }

        $totalKlasifikasi = KlasifikasiKendaraan::count();
        $nbAkurasi = (int) round(KlasifikasiKendaraan::avg('probabilitas_nb') ?? 0);
        $dtAkurasi = (int) round(KlasifikasiKendaraan::avg('probabilitas_dt') ?? 0);
        $nbPerluServis = KlasifikasiKendaraan::where('hasil_naive_bayes', 'Perlu Servis')->count();
        $dtPerluServis = KlasifikasiKendaraan::where('hasil_decision_tree', 'Perlu Servis')->count();
        $hasilSama = KlasifikasiKendaraan::whereColumn('hasil_naive_bayes', 'hasil_decision_tree')->count();

        return view('dashboard.index', compact(
            'totalKendaraan',
            'totalSopir',
            'servisBulanIni',
            'perluServis',
            'kendaraanPerluServis',
            'riwayatServis',
            'bulan',
            'total',
            'totalKlasifikasi',
            'nbAkurasi',
            'dtAkurasi',
            'nbPerluServis',
            'dtPerluServis',
            'hasilSama'
        ));
    }
}
