@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Servis Kendaraan</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalServis">
        + Tambah
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Total Servis</h6>
            <h3>{{ $total }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Menunggu</h6>
            <h3>{{ $menunggu }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Sedang Dikerjakan</h6>
            <h3>{{ $proses }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Selesai</h6>
            <h3>{{ $selesai }}</h3>
        </div>
    </div>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>No Servis</th>
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>KM</th>
                <th>Jenis</th>
                <th>Kerusakan</th>
                <th>Biaya</th>
                <th>Status</th>
                <th>Aksi</th>
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
                <td>{{ number_format($item->kilometer) }}</td>
                <td>{{ $item->jenis_servis }}</td>
                <td>{{ $item->jenis_kerusakan }}</td>
                <td>Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                <td>
                    @if($item->status === 'Menunggu')
                        <span class="badge bg-secondary">Menunggu</span>
                    @elseif($item->status === 'Sedang Dikerjakan')
                        <span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                    @else
                        <span class="badge bg-success">Selesai</span>
                    @endif
                </td>
                <td class="d-flex gap-1">
                    <a href="/servis/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/servis/delete/{{ $item->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus data servis ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalServis" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/servis/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Servis Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kendaraan</label>
                            <select name="kendaraan_id" id="servisKendaraan" class="form-control" required>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}" data-umur="{{ $item->umur }}">
                                        {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Servis</label>
                            <input type="date" name="tanggal_servis" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kilometer</label>
                            <input type="number" name="kilometer" id="servisKilometer" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Umur Kendaraan</label>
                            <div class="input-group">
                                <input type="number" name="umur_tahun" id="servisUmur" class="form-control" min="0" readonly>
                                <span class="input-group-text">tahun</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Lama Servis</label>
                            <div class="input-group">
                                <input type="number" name="lama_servis" class="form-control" min="0" placeholder="Contoh: 2" required>
                                <span class="input-group-text">hari</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Servis (kali/thn)</label>
                            <input type="number" name="frekuensi_servis" class="form-control" min="1" value="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis Servis</label>
                            <select name="jenis_servis" class="form-control" required>
                                @foreach($jenisServis as $jenis)
                                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                @foreach($statusServis as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Keluhan</label>
                            <input type="text" name="keluhan" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kerusakan</label>
                            <input type="text" name="jenis_kerusakan" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mekanik / Bengkel</label>
                            <input type="text" name="mekanik" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Biaya</label>
                            <input type="number" name="biaya" class="form-control" min="0" step="1000" required>
                        </div>

                    </div>
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

@push('scripts')
<script>
    function syncServisKendaraan() {
        const selected = $('#servisKendaraan option:selected');
        $('#servisKilometer').val(selected.data('km') ?? 0);
        $('#servisUmur').val(selected.data('umur') ?? 0);
    }

    $(function () {
        syncServisKendaraan();
        $('#servisKendaraan').on('change', syncServisKendaraan);
    });
</script>
@endpush
