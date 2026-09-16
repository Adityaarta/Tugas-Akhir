@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Servis Kendaraan</h4>
    <a href="/servis" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/servis/update/{{ $servis->id }}">
    @csrf

    <div class="card card-box p-3">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">No Servis</label>
                <input type="text" value="{{ $servis->no_servis }}" class="form-control" disabled>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kendaraan</label>
                <select name="kendaraan_id" id="servisKendaraanEdit" class="form-control" required>
                    @foreach($kendaraan as $item)
                        <option value="{{ $item->id }}" data-km="{{ $item->kilometer }}" data-umur="{{ $item->umur }}" {{ old('kendaraan_id', $servis->kendaraan_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal Servis</label>
                <input type="date" name="tanggal_servis" value="{{ old('tanggal_servis', optional($servis->tanggal_servis)->format('Y-m-d')) }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Kilometer</label>
                <input type="number" name="kilometer" id="servisKilometerEdit" value="{{ old('kilometer', $servis->kilometer) }}" class="form-control" min="0" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Umur Kendaraan</label>
                <div class="input-group">
                    <input type="number" name="umur_tahun" id="servisUmurEdit" value="{{ old('umur_tahun', $servis->umur_tahun) }}" class="form-control" min="0" readonly>
                    <span class="input-group-text">tahun</span>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Lama Servis</label>
                <div class="input-group">
                    <input type="number" name="lama_servis" value="{{ old('lama_servis', $servis->lama_servis) }}" class="form-control" min="0" placeholder="Contoh: 2" required>
                    <span class="input-group-text">hari</span>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Servis (kali/thn)</label>
                <input type="number" name="frekuensi_servis" value="{{ old('frekuensi_servis', $servis->frekuensi_servis) }}" class="form-control" min="1" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Jenis Servis</label>
                <select name="jenis_servis" class="form-control" required>
                    @foreach($jenisServis as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_servis', $servis->jenis_servis) === $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach($statusServis as $status)
                        <option value="{{ $status }}" {{ old('status', $servis->status) === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Mekanik / Bengkel</label>
                <input type="text" name="mekanik" value="{{ old('mekanik', $servis->mekanik) }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Biaya</label>
                <input type="number" name="biaya" value="{{ old('biaya', $servis->biaya) }}" class="form-control" min="0" step="1000" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Keluhan</label>
                <input type="text" name="keluhan" value="{{ old('keluhan', $servis->keluhan) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis Kerusakan</label>
                <input type="text" name="jenis_kerusakan" value="{{ old('jenis_kerusakan', $servis->jenis_kerusakan) }}" class="form-control" required>
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
    function syncServisKendaraanEdit() {
        const selected = $('#servisKendaraanEdit option:selected');
        $('#servisKilometerEdit').val(selected.data('km') ?? 0);
        $('#servisUmurEdit').val(selected.data('umur') ?? 0);
    }

    $(function () {
        $('#servisKendaraanEdit').on('change', syncServisKendaraanEdit);
    });
</script>
@endpush
