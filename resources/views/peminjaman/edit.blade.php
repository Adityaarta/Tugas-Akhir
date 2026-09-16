@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Data Pemakai Kendaraan</h4>
    <a href="/data-pemakai" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="/data-pemakai/update/{{ $peminjaman->id }}">
    @csrf

    <div class="card card-box p-3">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Pemakai</label>
                <select name="sopir_id" class="form-control" required>
                    @foreach($sopir as $item)
                        <option value="{{ $item->id }}" {{ old('sopir_id', $peminjaman->sopir_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }} - {{ $item->no_sim }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kendaraan</label>
                <select name="kendaraan_id" id="pemakaiKendaraanEdit" class="form-control" required>
                    @foreach($kendaraan as $item)
                        <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}" {{ old('kendaraan_id', $peminjaman->kendaraan_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Pakai</label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', optional($peminjaman->tanggal_pinjam)->format('Y-m-d')) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', optional($peminjaman->tanggal_kembali)->format('Y-m-d')) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">KM Awal</label>
                <input type="number" name="km_awal_display" value="{{ old('km_awal', $peminjaman->km_awal) }}" class="form-control" min="0" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">KM Akhir</label>
                <input type="number" name="km_akhir" value="{{ old('km_akhir', $peminjaman->km_akhir) }}" class="form-control" min="0">
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="dipakai" {{ in_array(old('status', $peminjaman->status), ['dipakai', 'disetujui']) ? 'selected' : '' }}>Dipakai</option>
                    <option value="ditolak" {{ old('status', $peminjaman->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dikembalikan" {{ old('status', $peminjaman->status) === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tujuan</label>
                <input type="text" name="tujuan" value="{{ old('tujuan', $peminjaman->tujuan) }}" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $peminjaman->catatan) }}</textarea>
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
    function syncPemakaiKmAwalEdit() {
        const selected = $('#pemakaiKendaraanEdit option:selected');
        $('input[name="km_awal_display"]').val(selected.data('km') ?? 0);
    }

    $(function () {
        $('#pemakaiKendaraanEdit').on('change', syncPemakaiKmAwalEdit);
    });
</script>
@endpush
