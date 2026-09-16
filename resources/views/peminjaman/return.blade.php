@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pengembalian Pemakaian</h4>
    <a href="/data-pemakai" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card card-box p-3 mb-3">
    <div class="row g-3">
        <div class="col-md-4">
            <small class="text-muted">Pemakai</small>
            <div class="fw-semibold">{{ $peminjaman->sopir->nama ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <small class="text-muted">Kendaraan</small>
            <div class="fw-semibold">{{ $peminjaman->kendaraan->no_polisi ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <small class="text-muted">Tanggal Pakai</small>
            <div class="fw-semibold">{{ optional($peminjaman->tanggal_pinjam)->format('d-m-Y') }}</div>
        </div>
    </div>
</div>

<form method="POST" action="/data-pemakai/return/{{ $peminjaman->id }}">
    @csrf

    <div class="card card-box p-3">
        <label class="form-label">Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', now()->format('Y-m-d')) }}" class="form-control mb-2" required>

        <label class="form-label">KM Awal</label>
        <input type="number" value="{{ $peminjaman->km_awal ?? $peminjaman->kendaraan->kilometer ?? 0 }}" class="form-control mb-2" readonly>

        <label class="form-label">KM Akhir</label>
        <input type="number" name="km_akhir" value="{{ old('km_akhir', $peminjaman->km_akhir) }}" class="form-control mb-2" min="{{ $peminjaman->km_awal ?? 0 }}" required>

        <label class="form-label">Catatan</label>
        <textarea name="catatan" class="form-control mb-3" rows="3">{{ old('catatan', $peminjaman->catatan) }}</textarea>

        <button class="btn btn-success">Simpan Pengembalian</button>
    </div>
</form>

@endsection
