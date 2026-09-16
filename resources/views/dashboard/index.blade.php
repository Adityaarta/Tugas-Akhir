@extends('layouts.admin')

@section('content')

<style>
    .dashboard-title{font-weight:700;margin-bottom:16px}
    .metric-card{min-height:92px;border:1px solid #e7ecf4;border-radius:8px;background:#fff;box-shadow:0 6px 18px rgba(15,23,42,.08);padding:18px;display:flex;align-items:center;justify-content:space-between;gap:14px}
    .metric-main{display:flex;align-items:center;gap:14px;min-width:0}
    .metric-icon{width:48px;height:48px;border-radius:8px;background:#eaf0ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:24px;flex:0 0 auto}
    .metric-label{font-size:14px;color:#334155;margin-bottom:2px}
    .metric-value{font-size:28px;line-height:1;font-weight:800;color:#0f172a}
    .metric-note{font-size:12px;color:#64748b;white-space:nowrap}
    .metric-note i{color:#059669}
    .metric-card.warning{background:#fff0ea;border-color:#ffd7c7}
    .metric-card.warning .metric-icon{background:#ffd8cd;color:#dc2626}
    .metric-card.warning .metric-link{font-weight:700;text-decoration:none;color:#2563eb;white-space:nowrap}
    .panel-card{border:1px solid #e7ecf4;border-radius:8px;background:#fff;box-shadow:0 6px 18px rgba(15,23,42,.08);padding:16px;height:100%}
    .panel-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px}
    .panel-head h6{font-size:17px;font-weight:700;margin:0}
    .panel-head a,.panel-head .btn{font-size:13px}
    .dashboard-table{font-size:14px}
    .dashboard-table thead th{color:#0f172a;font-weight:700;border-bottom-color:#dbe4f0}
    .dashboard-table tbody tr:nth-child(even){background:#f3f6fb}
    .chart-wrap{height:250px;position:relative}
    .analysis-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
    .analysis-tile{border:1px solid #dfe6ef;border-radius:8px;padding:14px;display:flex;gap:12px;align-items:center}
    .analysis-icon{width:46px;height:46px;border-radius:8px;background:#eaf0ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:22px;flex:0 0 auto}
    .analysis-title{font-weight:800;color:#0f172a}
    .analysis-sub{font-size:13px;color:#475569;margin-bottom:6px}
    .analysis-score{font-size:18px;font-weight:800;color:#0f172a}
    .analysis-bar{height:8px;background:#e2e8f0;border-radius:99px;overflow:hidden}
    .analysis-fill{height:100%;background:#2563eb;border-radius:99px}
    .analysis-tile.alert-soft .analysis-icon{background:#ffe3db;color:#dc2626}
    @media(max-width:991px){.analysis-grid{grid-template-columns:1fr}.chart-wrap{height:220px}.metric-note{display:none}}
</style>

<h4 class="dashboard-title">Dashboard</h4>

<div class="row g-3">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-main">
                <div class="metric-icon"><i class="fa-solid fa-car"></i></div>
                <div>
                    <div class="metric-label">Total Kendaraan</div>
                    <div class="metric-value">{{ $totalKendaraan }}</div>
                </div>
            </div>
            <div class="metric-note"><i class="fa-solid fa-arrow-trend-up"></i> vs last month</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-main">
                <div class="metric-icon"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="metric-label">Total Sopir</div>
                    <div class="metric-value">{{ $totalSopir }}</div>
                </div>
            </div>
            <div class="metric-note"><i class="fa-solid fa-arrow-trend-up"></i> vs last month</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-main">
                <div class="metric-icon"><i class="fa-solid fa-wrench"></i></div>
                <div>
                    <div class="metric-label">Servis Bulan Ini</div>
                    <div class="metric-value">{{ $servisBulanIni }}</div>
                </div>
            </div>
            <div class="metric-note"><i class="fa-solid fa-arrow-trend-up"></i> vs last month</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card warning">
            <div class="metric-main">
                <div class="metric-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div class="metric-label">Perlu Servis</div>
                    <div class="metric-value">{{ $perluServis }}</div>
                </div>
            </div>
            <a class="metric-link" href="{{ url('/prediksi-naive-bayes') }}">View List</a>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-xl-7">
        <div class="panel-card">
            <div class="panel-head">
                <h6>Kendaraan Perlu Servis</h6>
                <a href="{{ url('/prediksi-naive-bayes') }}">Lihat Semua</a>
            </div>

            <table class="table table-sm align-middle dashboard-table datatable">
                <thead>
                    <tr>
                        <th>No Polisi</th>
                        <th>Kendaraan</th>
                        <th>KM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kendaraanPerluServis as $k)
                    <tr>
                        <td>{{ $k->no_polisi }}</td>
                        <td>{{ ucfirst($k->jenis ?? 'truk') }} - {{ trim(($k->merk ?? '').' '.($k->tipe ?? '')) ?: '-' }}</td>
                        <td>{{ number_format($k->kilometer ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="panel-card">
            <div class="panel-head">
                <h6>Statistik Servis</h6>
                <a href="{{ url('/laporan-servis') }}" class="btn btn-sm btn-outline-secondary">View Report</a>
            </div>
            <div class="chart-wrap">
                <canvas id="servisChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-xl-6">
        <div class="panel-card">
            <div class="panel-head">
                <h6>Hasil Analisis Prediktif</h6>
                <a href="{{ url('/perbandingan-hasil') }}">Lihat Semua</a>
            </div>

            <div class="analysis-grid">
                <div class="analysis-tile">
                    <div class="analysis-icon"><i class="fa-solid fa-brain"></i></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                <div class="analysis-title">Naive Bayes</div>
                                <div class="analysis-sub">Rata-rata Akurasi</div>
                            </div>
                            <div class="analysis-score">{{ $nbAkurasi }}%</div>
                        </div>
                        <div class="analysis-bar"><div class="analysis-fill" style="width: {{ min($nbAkurasi, 100) }}%"></div></div>
                    </div>
                </div>

                <div class="analysis-tile">
                    <div class="analysis-icon"><i class="fa-solid fa-sitemap"></i></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                <div class="analysis-title">Decision Tree</div>
                                <div class="analysis-sub">Rata-rata Akurasi</div>
                            </div>
                            <div class="analysis-score">{{ $dtAkurasi }}%</div>
                        </div>
                        <div class="analysis-bar"><div class="analysis-fill" style="width: {{ min($dtAkurasi, 100) }}%"></div></div>
                    </div>
                </div>

                <div class="analysis-tile alert-soft">
                    <div class="analysis-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <div>
                        <div class="analysis-title">{{ $nbPerluServis }}</div>
                        <div class="analysis-sub mb-0">Naive Bayes Perlu Servis</div>
                    </div>
                </div>

                <div class="analysis-tile alert-soft">
                    <div class="analysis-icon"><i class="fa-solid fa-code-branch"></i></div>
                    <div>
                        <div class="analysis-title">{{ $dtPerluServis }}</div>
                        <div class="analysis-sub mb-0">Decision Tree Perlu Servis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="panel-card">
            <div class="panel-head">
                <h6>Riwayat Servis Terbaru</h6>
                <a href="{{ url('/riwayat-servis') }}">Lihat Semua</a>
            </div>

            <table class="table table-sm align-middle dashboard-table datatable">
                <thead>
                    <tr>
                        <th>No Polisi</th>
                        <th>Kendaraan</th>
                        <th>KM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatServis as $r)
                    <tr>
                        <td>{{ $r->kendaraan->no_polisi ?? '-' }}</td>
                        <td>{{ ucfirst($r->kendaraan->jenis ?? 'truk') }} - {{ trim(($r->kendaraan->merk ?? '').' '.($r->kendaraan->tipe ?? '')) ?: ($r->jenis_servis ?? '-') }}</td>
                        <td>{{ number_format($r->kilometer ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxServis = document.getElementById('servisChart');

    if (ctxServis) {
        new Chart(ctxServis, {
            data: {
                labels: @json($bulan),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Services by Month',
                        data: @json($total),
                        backgroundColor: '#2f7fd1',
                        borderRadius: 2,
                        maxBarThickness: 28
                    },
                    {
                        type: 'line',
                        label: 'Trend',
                        data: @json($total),
                        borderColor: '#5aa0e6',
                        backgroundColor: 'rgba(90,160,230,.14)',
                        pointBackgroundColor: '#5aa0e6',
                        pointRadius: 3,
                        borderWidth: 3,
                        tension: .35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { boxWidth: 34 } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#e7edf5' } },
                    x: { grid: { color: '#eef2f7' } }
                }
            }
        });
    }
</script>
@endpush

@endsection
