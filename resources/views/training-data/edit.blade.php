@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Training Data</h4>
    <a href="/training-data" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/training-data/update/{{ $trainingData->id }}">
    @csrf

    <div class="card card-box p-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Umur Kendaraan</label>
                <input type="number" name="umur_kendaraan" value="{{ old('umur_kendaraan', $trainingData->umur_kendaraan) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jarak Tempuh/Tahun</label>
                <input type="number" name="jarak_tempuh_tahun" value="{{ old('jarak_tempuh_tahun', $trainingData->jarak_tempuh_tahun) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Servis (kali/thn)</label>
                <input type="number" name="frekuensi_servis_tahun" value="{{ old('frekuensi_servis_tahun', $trainingData->frekuensi_servis_tahun) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">KM Oli</label>
                <input type="number" name="km_oli" value="{{ old('km_oli', $trainingData->km_oli) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">KM Rem</label>
                <input type="number" name="km_rem" value="{{ old('km_rem', $trainingData->km_rem) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">KM Busi</label>
                <input type="number" name="km_busi" value="{{ old('km_busi', $trainingData->km_busi) }}" class="form-control mb-2" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">KM Ban</label>
                <input type="number" name="km_ban" value="{{ old('km_ban', $trainingData->km_ban) }}" class="form-control mb-2" min="0" required>
            </div>
        </div>

        <label class="form-label">Kelas</label>
        <select name="kelas" class="form-control mb-2" required>
            @foreach($kelas as $item)
                <option value="{{ $item }}" {{ old('kelas', $trainingData->kelas) === $item ? 'selected' : '' }}>
                    {{ $item }}
                </option>
            @endforeach
        </select>

        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control mb-3" rows="3">{{ old('keterangan', $trainingData->keterangan) }}</textarea>

        <button class="btn btn-success">Update</button>
    </div>
</form>

@endsection
