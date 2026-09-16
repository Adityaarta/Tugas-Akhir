@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sopir</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSopir">
        + Tambah
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No SIM</th>
                <th>No HP</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sopir as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->no_sim ?? '-' }}</td>
                <td>{{ $item->no_hp ?? '-' }}</td>
                <td>{{ optional($item->created_at)->format('d-m-Y') }}</td>
                <td class="d-flex gap-1">
                    <a href="/sopir/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/sopir/delete/{{ $item->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus sopir ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalSopir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/sopir/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sopir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control mb-2" required>

                    <label class="form-label">No SIM</label>
                    <input type="text" name="no_sim" class="form-control mb-2">

                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control">
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
