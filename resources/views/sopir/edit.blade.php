@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Sopir</h4>
    <a href="/sopir" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form method="POST" action="/sopir/update/{{ $sopir->id }}">
    @csrf

    <div class="card card-box p-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $sopir->nama) }}" class="form-control mb-2" required>

        <label class="form-label">No SIM</label>
        <input type="text" name="no_sim" value="{{ old('no_sim', $sopir->no_sim) }}" class="form-control mb-2">

        <label class="form-label">No HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $sopir->no_hp) }}" class="form-control mb-3">

        <button class="btn btn-success">Update</button>
    </div>
</form>

@endsection
