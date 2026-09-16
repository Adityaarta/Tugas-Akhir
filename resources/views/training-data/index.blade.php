@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Training Data</h4>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class="fa-solid fa-file-import me-1"></i>
            Import
        </button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTrainingData">
            + Tambah
        </button>
    </div>
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
                <th>Umur</th>
                <th>Jarak/Tahun</th>
                <th>Servis (kali/thn)</th>
                <th>KM Oli</th>
                <th>KM Rem</th>
                <th>KM Busi</th>
                <th>KM Ban</th>
                <th>Kelas</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainingData as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ number_format($item->umur_kendaraan) }} tahun</td>
                <td>{{ number_format($item->jarak_tempuh_tahun) }} km</td>
                <td>{{ number_format($item->frekuensi_servis_tahun) }} kali</td>
                <td>{{ number_format($item->km_oli) }} km</td>
                <td>{{ number_format($item->km_rem) }} km</td>
                <td>{{ number_format($item->km_busi) }} km</td>
                <td>{{ number_format($item->km_ban) }} km</td>
                <td>
                    <span class="badge {{ $item->kelas === 'Layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ $item->kelas }}
                    </span>
                </td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td class="d-flex gap-1">
                    <a href="/training-data/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/training-data/delete/{{ $item->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus data training ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTrainingData" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/training-data/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Training Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Umur Kendaraan</label>
                            <input type="number" name="umur_kendaraan" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jarak Tempuh/Tahun</label>
                            <input type="number" name="jarak_tempuh_tahun" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Servis (kali/thn)</label>
                            <input type="number" name="frekuensi_servis_tahun" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">KM Oli</label>
                            <input type="number" name="km_oli" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">KM Rem</label>
                            <input type="number" name="km_rem" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">KM Busi</label>
                            <input type="number" name="km_busi" class="form-control mb-2" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">KM Ban</label>
                            <input type="number" name="km_ban" class="form-control mb-2" min="0" required>
                        </div>
                    </div>

                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control mb-2" required>
                        @foreach($kelas as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>

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

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/training-data/import" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Import Training Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">File Excel/CSV</label>
                    <input type="file" name="file" class="form-control mb-2" accept=".csv,.txt,.xlsx" required>
                    <small class="text-muted d-block mb-3">
                        Kolom wajib: umur_kendaraan, jarak_tempuh_tahun, servis_kali_tahun, km_oli, km_rem, km_busi, km_ban, kelas.
                    </small>

                    <a href="/training-data/template" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-download me-1"></i>
                        Download Template CSV
                    </a>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
