@extends('layouts.admin')

@section('content')

<h4 class="mb-3">Perbandingan Hasil</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Total Data Uji</h6>
            <h3>{{ $total }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>NB Perlu Servis</h6>
            <h3>{{ $nbPerlu }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>DT Perlu Servis</h6>
            <h3>{{ $dtPerlu }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Hasil Sama</h6>
            <h3>{{ $sama }}</h3>
        </div>
    </div>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>Naive Bayes</th>
                <th>Akurasi NB</th>
                <th>Decision Tree</th>
                <th>Akurasi DT</th>
                <th>Algoritma Terbaik</th>
                <th>Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($klasifikasi as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ optional($item->tanggal_klasifikasi)->format('d-m-Y') }}</td>
                <td>{{ $item->kendaraan->no_polisi ?? '-' }}</td>
                <td>
                    <span class="badge {{ $item->hasil_naive_bayes === 'Perlu Servis' ? 'bg-danger' : 'bg-success' }}">
                        {{ $item->hasil_naive_bayes }}
                    </span>
                </td>
                <td>{{ $item->probabilitas_nb }}%</td>
                <td>
                    <span class="badge {{ $item->hasil_decision_tree === 'Perlu Servis' ? 'bg-danger' : 'bg-success' }}">
                        {{ $item->hasil_decision_tree }}
                    </span>
                </td>
                <td>{{ $item->probabilitas_dt }}%</td>
                <td>{{ $item->algoritma_terbaik }}</td>
                <td>{{ $item->rekomendasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
