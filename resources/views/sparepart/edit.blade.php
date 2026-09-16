@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Sparepart</h4>
    <a href="/sparepart" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/sparepart/update/{{ $sparepart->id }}">
    @csrf

    <div class="card card-box p-3">
        <label class="form-label">Nama Sparepart</label>
        <input type="text" name="nama_sparepart" value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}" class="form-control mb-2" required>

        <label class="form-label">Jenis Sparepart</label>
        <input type="text" name="jenis_sparepart" value="{{ old('jenis_sparepart', $sparepart->jenis_sparepart) }}" class="form-control mb-2">

        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label">Umur Pakai</label>
                <input type="number" name="umur_pakai" value="{{ old('umur_pakai', $sparepart->umur_pakai) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Satuan Umur</label>
                <select name="satuan_umur" class="form-control mb-2" required>
                    @foreach($satuanUmur as $satuan)
                        <option value="{{ $satuan }}" {{ old('satuan_umur', $sparepart->satuan_umur) === $satuan ? 'selected' : '' }}>
                            {{ strtoupper($satuan) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label class="form-label">Batas KM</label>
        <input type="number" name="batas_km" value="{{ old('batas_km', $sparepart->batas_km) }}" class="form-control mb-2" min="0">

        <label class="form-label">Harga Estimasi</label>
        <input type="number" name="harga_estimasi" value="{{ old('harga_estimasi', $sparepart->harga_estimasi) }}" class="form-control mb-2" min="0" step="1000">

        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control mb-3" rows="3">{{ old('keterangan', $sparepart->keterangan) }}</textarea>

        <button class="btn btn-success">Update</button>
    </div>
</form>

@endsection
