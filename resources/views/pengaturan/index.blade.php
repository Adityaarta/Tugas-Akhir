@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pengaturan Aplikasi</h4>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/pengaturan/update">
    @csrf

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card card-box p-3">
                <h6 class="mb-3">Informasi Utama</h6>

                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="nama_aplikasi" value="{{ old('nama_aplikasi', $pengaturan->nama_aplikasi) }}" class="form-control mb-3" required>

                <label class="form-label">Nama Instansi</label>
                <input type="text" name="nama_instansi" value="{{ old('nama_instansi', $pengaturan->nama_instansi) }}" class="form-control mb-3" required>

                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control mb-3" rows="3">{{ old('deskripsi', $pengaturan->deskripsi) }}</textarea>

                <button class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-box p-3">
                <h6 class="mb-3">Kontak</h6>

                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $pengaturan->email) }}" class="form-control mb-3">

                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $pengaturan->telepon) }}" class="form-control mb-3">

                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="4">{{ old('alamat', $pengaturan->alamat) }}</textarea>
            </div>
        </div>
    </div>
</form>

@endsection
