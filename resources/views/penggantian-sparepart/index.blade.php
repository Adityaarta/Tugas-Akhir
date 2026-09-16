@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Penggantian Sparepart</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPenggantian">
        + Tambah
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-box p-3">
            <h6>Total Penggantian</h6>
            <h3>{{ $total }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-box p-3">
            <h6>Penggantian Bulan Ini</h6>
            <h3>{{ $bulanIni }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-box p-3">
            <h6>Total Biaya</h6>
            <h3>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>Sparepart</th>
                <th>KM Penggantian</th>
                <th>KM Ganti Berikutnya</th>
                <th>Biaya</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penggantian as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ optional($item->tanggal_penggantian)->format('d-m-Y') }}</td>
                <td>
                    {{ $item->kendaraan->no_polisi ?? '-' }}
                    <small class="d-block text-muted">
                        {{ ucfirst($item->kendaraan->jenis ?? 'truk') }} - {{ trim(($item->kendaraan->merk ?? '').' '.($item->kendaraan->tipe ?? '')) ?: '-' }}
                    </small>
                </td>
                <td>{{ $item->sparepart->nama_sparepart ?? '-' }}</td>
                <td>{{ number_format($item->kilometer_penggantian) }} km</td>
                <td>{{ $item->km_ganti_berikutnya ? number_format($item->km_ganti_berikutnya).' km' : '-' }}</td>
                <td>Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td class="d-flex gap-1">
                    <a href="/penggantian-sparepart/edit/{{ $item->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/penggantian-sparepart/delete/{{ $item->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus data penggantian ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalPenggantian" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/penggantian-sparepart/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penggantian Sparepart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kendaraan</label>
                            <select name="kendaraan_id" id="penggantianKendaraan" class="form-control" required>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}">
                                        {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sparepart</label>
                            <select name="sparepart_id" id="penggantianSparepart" class="form-control" required>
                                @foreach($sparepart as $item)
                                    <option value="{{ $item->id }}" data-batas-km="{{ $item->batas_km ?? 0 }}" data-harga="{{ $item->harga_estimasi ?? 0 }}">{{ $item->nama_sparepart }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Penggantian</label>
                            <input type="date" name="tanggal_penggantian" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">KM Penggantian</label>
                            <input type="number" name="kilometer_penggantian" id="penggantianKm" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">KM Ganti Berikutnya</label>
                            <input type="number" id="penggantianKmBerikutnya" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Biaya</label>
                            <input type="number" name="biaya" id="penggantianBiaya" class="form-control" min="0" step="1000" value="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
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
    function syncPenggantianKm() {
        const km = Number($('#penggantianKendaraan option:selected').attr('data-km') || 0);
        const selectedSparepart = $('#penggantianSparepart option:selected');
        const batasKm = Number(selectedSparepart.attr('data-batas-km') || 0);
        const harga = Number(selectedSparepart.attr('data-harga') || 0);
        $('#penggantianKm').val(km);
        $('#penggantianKmBerikutnya').val(km + batasKm);
        $('#penggantianBiaya').val(harga);
    }

    $(function () {
        syncPenggantianKm();
        $('#penggantianKendaraan, #penggantianSparepart').on('change', syncPenggantianKm);
    });
</script>
@endpush
