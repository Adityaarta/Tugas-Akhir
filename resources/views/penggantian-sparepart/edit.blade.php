@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Penggantian Sparepart</h4>
    <a href="/penggantian-sparepart" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/penggantian-sparepart/update/{{ $penggantian->id }}">
    @csrf

    <div class="card card-box p-3">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kendaraan</label>
                <select name="kendaraan_id" id="penggantianKendaraanEdit" class="form-control" required>
                    @foreach($kendaraan as $item)
                        <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}" {{ old('kendaraan_id', $penggantian->kendaraan_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sparepart</label>
                <select name="sparepart_id" id="penggantianSparepartEdit" class="form-control" required>
                    @foreach($sparepart as $item)
                        <option value="{{ $item->id }}" data-batas-km="{{ $item->batas_km ?? 0 }}" data-harga="{{ $item->harga_estimasi ?? 0 }}" {{ old('sparepart_id', $penggantian->sparepart_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_sparepart }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Penggantian</label>
                <input type="date" name="tanggal_penggantian" value="{{ old('tanggal_penggantian', optional($penggantian->tanggal_penggantian)->format('Y-m-d')) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">KM Penggantian</label>
                <input type="number" name="kilometer_penggantian" id="penggantianKmEdit" value="{{ old('kilometer_penggantian', $penggantian->kilometer_penggantian) }}" class="form-control" min="0" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">KM Ganti Berikutnya</label>
                <input type="number" id="penggantianKmBerikutnyaEdit" value="{{ old('km_ganti_berikutnya', $penggantian->km_ganti_berikutnya) }}" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Biaya</label>
                <input type="number" name="biaya" id="penggantianBiayaEdit" value="{{ old('biaya', $penggantian->biaya) }}" class="form-control" min="0" step="1000">
            </div>

            <div class="col-md-12">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $penggantian->keterangan) }}</textarea>
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-success">Update</button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function syncPenggantianKmEdit(updateBiaya = false) {
        const km = Number($('#penggantianKendaraanEdit option:selected').attr('data-km') || 0);
        const selectedSparepart = $('#penggantianSparepartEdit option:selected');
        const batasKm = Number(selectedSparepart.attr('data-batas-km') || 0);
        const harga = Number(selectedSparepart.attr('data-harga') || 0);
        const biayaSekarang = Number($('#penggantianBiayaEdit').val() || 0);
        $('#penggantianKmEdit').val(km);
        $('#penggantianKmBerikutnyaEdit').val(km + batasKm);

        if (updateBiaya || biayaSekarang <= 0) {
            $('#penggantianBiayaEdit').val(harga);
        }
    }

    $(function () {
        syncPenggantianKmEdit(false);
        $('#penggantianKendaraanEdit').on('change', function () {
            syncPenggantianKmEdit(false);
        });
        $('#penggantianSparepartEdit').on('change', function () {
            syncPenggantianKmEdit(true);
        });
    });
</script>
@endpush
