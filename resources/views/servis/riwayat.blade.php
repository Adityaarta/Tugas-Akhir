@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Riwayat Servis</h4>
    <a href="/servis" class="btn btn-primary btn-sm">Kelola Servis</a>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>No Servis</th>
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>Jenis Servis</th>
                <th>Keluhan</th>
                <th>Mekanik</th>
                <th>Biaya</th>
                <th>Naive Bayes</th>
                <th>Decision Tree</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servis as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->no_servis }}</td>
                <td>{{ optional($item->tanggal_servis)->format('d-m-Y') }}</td>
                <td>
                    {{ $item->kendaraan->no_polisi ?? '-' }}
                    <small class="d-block text-muted">
                        {{ ucfirst($item->kendaraan->jenis ?? 'truk') }} - {{ trim(($item->kendaraan->merk ?? '').' '.($item->kendaraan->tipe ?? '')) ?: '-' }}
                    </small>
                </td>
                <td>{{ $item->jenis_servis }}</td>
                <td>{{ $item->keluhan }}</td>
                <td>{{ $item->mekanik }}</td>
                <td>Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                <td>{{ $item->hasil_naive_bayes ?? '-' }}</td>
                <td>{{ $item->hasil_decision_tree ?? '-' }}</td>
                <td>
                    @if($item->status === 'Menunggu')
                        <span class="badge bg-secondary">Menunggu</span>
                    @elseif($item->status === 'Sedang Dikerjakan')
                        <span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                    @else
                        <span class="badge bg-success">Selesai</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
