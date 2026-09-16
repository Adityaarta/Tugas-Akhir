@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Pemakai Kendaraan</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPemakai">
        + Tambah
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Total Pemakai</h6>
            <h3>{{ $total }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Sedang Dipakai</h6>
            <h3>{{ $dipakai }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Dikembalikan</h6>
            <h3>{{ $dikembalikan }}</h3>
        </div>
    </div>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Pemakai</th>
                <th>Kendaraan</th>
                <th>Tanggal Pakai</th>
                <th>Tanggal Kembali</th>
                <th>Tujuan</th>
                <th>KM</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $i => $item)
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
                <td>{{ $item->km_awal ?? '-' }} / {{ $item->km_akhir ?? '-' }}</td>
                <td>
                    @if(in_array($item->status, ['dipakai', 'disetujui']))
                        <span class="badge bg-warning text-dark">Dipakai</span>
                    @elseif($item->status === 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-primary">Dikembalikan</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @if($item->status === 'diajukan')
                            <a href="/data-pemakai/approve/{{ $item->id }}"
                               class="btn btn-sm btn-success"
                               onclick="return confirm('Aktifkan pemakaian ini?')">
                                <i class="fa fa-check"></i>
                            </a>
                            <a href="/data-pemakai/reject/{{ $item->id }}"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Tolak data pemakai ini?')">
                                <i class="fa fa-xmark"></i>
                            </a>
                        @endif

                        @if(in_array($item->status, ['dipakai', 'disetujui']))
                            <a href="/data-pemakai/return/{{ $item->id }}" class="btn btn-sm btn-info text-white">
                                <i class="fa fa-rotate-left"></i>
                            </a>
                        @endif

                        <a href="/data-pemakai/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="/data-pemakai/delete/{{ $item->id }}"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Yakin hapus data ini?')">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalPemakai" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/data-pemakai/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pemakai Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pemakai</label>
                            <select name="sopir_id" class="form-control" required>
                                @foreach($sopir as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->no_sim }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kendaraan</label>
                            <select name="kendaraan_id" id="pemakaiKendaraan" class="form-control" required>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}">
                                        {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pakai</label>
                            <input type="date" name="tanggal_pinjam" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rencana Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">KM Awal</label>
                            <input type="number" name="km_awal_display" id="pemakaiKmAwal" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tujuan</label>
                            <input type="text" name="tujuan" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
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
    function syncPemakaiKmAwal() {
        const selected = $('#pemakaiKendaraan option:selected');
        $('#pemakaiKmAwal').val(selected.data('km') ?? 0);
    }

    $(function () {
        syncPemakaiKmAwal();
        $('#pemakaiKendaraan').on('change', syncPemakaiKmAwal);
    });
</script>
@endpush
