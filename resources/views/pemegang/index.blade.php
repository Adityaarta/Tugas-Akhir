@extends('layouts.admin')

@section('content')

<h4 class="mb-3">Pemakai Kendaraan Aktif</h4>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Pemakai</th>
                <th>Kendaraan</th>
                <th>Tanggal Pakai</th>
                <th>Rencana Kembali</th>
                <th>Tujuan</th>
                <th>KM Awal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemegang as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->sopir->nama ?? '-' }}</td>
                <td>
                    {{ $item->kendaraan->no_polisi ?? '-' }}
                    <small class="d-block text-muted">
                        {{ ucfirst($item->kendaraan->jenis ?? 'truk') }} - {{ trim(($item->kendaraan->merk ?? '').' '.($item->kendaraan->tipe ?? '')) ?: '-' }}
                    </small>
                </td>
                <td>{{ optional($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td>{{ optional($item->tanggal_kembali)->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $item->tujuan }}</td>
                <td>{{ $item->km_awal ? number_format($item->km_awal) : '-' }}</td>
                <td><span class="badge bg-warning text-dark">Dipakai</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
