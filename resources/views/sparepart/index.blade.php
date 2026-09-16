@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sparepart</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSparepart">
        + Tambah
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Sparepart</th>
                <th>Jenis</th>
                <th>Umur Pakai</th>
                <th>Batas KM</th>
                <th>Harga Estimasi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sparepart as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->nama_sparepart }}</td>
                <td>{{ $item->jenis_sparepart ?? '-' }}</td>
                <td>{{ number_format($item->umur_pakai) }} {{ $item->satuan_umur }}</td>
                <td>{{ $item->batas_km ? number_format($item->batas_km).' km' : '-' }}</td>
                <td>Rp {{ number_format($item->harga_estimasi, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td class="d-flex gap-1">
                    <a href="/sparepart/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/sparepart/delete/{{ $item->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus sparepart ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalSparepart" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/sparepart/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sparepart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nama Sparepart</label>
                    <input type="text" name="nama_sparepart" class="form-control mb-2" required>

                    <label class="form-label">Jenis Sparepart</label>
                    <input type="text" name="jenis_sparepart" class="form-control mb-2">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Umur Pakai</label>
                            <input type="number" name="umur_pakai" class="form-control mb-2" min="0" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan Umur</label>
                            <select name="satuan_umur" class="form-control mb-2" required>
                                @foreach($satuanUmur as $satuan)
                                    <option value="{{ $satuan }}">{{ strtoupper($satuan) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <label class="form-label">Batas KM</label>
                    <input type="number" name="batas_km" class="form-control mb-2" min="0">

                    <label class="form-label">Harga Estimasi</label>
                    <input type="number" name="harga_estimasi" class="form-control mb-2" min="0" step="1000" value="0">

                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
