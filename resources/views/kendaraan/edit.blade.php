@extends('layouts.admin')

@section('content')

<h4>Edit Kendaraan</h4>

<form method="POST" action="/kendaraan/update/{{ $kendaraan->id }}">
@csrf

<div class="card p-3">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">No Polisi</label>
            <input name="no_polisi" value="{{ $kendaraan->no_polisi }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Jenis Kendaraan</label>
            <select name="jenis" class="form-control">
                <option value="truk" {{ ($kendaraan->jenis ?? 'truk')=='truk'?'selected':'' }}>Truk</option>
                <option value="mobil" {{ ($kendaraan->jenis ?? 'truk')=='mobil'?'selected':'' }}>Mobil</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Merk</label>
            <input name="merk" value="{{ $kendaraan->merk }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tipe</label>
            <input name="tipe" value="{{ $kendaraan->tipe }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tahun Kendaraan</label>
            <input type="number" name="tahun" value="{{ $kendaraan->tahun }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Kilometer Saat Ini</label>
            <input type="number" name="kilometer" value="{{ $kendaraan->kilometer }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Jarak Tempuh per Tahun</label>
            <input type="number" name="jarak_tempuh_tahun" value="{{ $kendaraan->jarak_tempuh_tahun }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Servis per Tahun</label>
            <input type="number" name="frekuensi_servis_tahun" value="{{ $kendaraan->frekuensi_servis_tahun }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Oli</label>
            <input type="number" name="km_oli" value="{{ $kendaraan->km_oli }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Rem</label>
            <input type="number" name="km_rem" value="{{ $kendaraan->km_rem }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Busi</label>
            <input type="number" name="km_busi" value="{{ $kendaraan->km_busi }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Ban</label>
            <input type="number" name="km_ban" value="{{ $kendaraan->km_ban }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Riwayat Perawatan</label>
            <select name="riwayat_perawatan" class="form-control">
                @foreach(['Baik', 'Cukup', 'Buruk'] as $opsi)
                    <option value="{{ $opsi }}" {{ ($kendaraan->riwayat_perawatan ?? 'Baik') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jumlah Keluhan</label>
            <input type="number" name="jumlah_keluhan" value="{{ $kendaraan->jumlah_keluhan ?? 0 }}" class="form-control" min="0">
        </div>
        <div class="col-md-3">
            <label class="form-label">Riwayat Kecelakaan</label>
            <input type="number" name="riwayat_kecelakaan" value="{{ $kendaraan->riwayat_kecelakaan ?? 0 }}" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Rem</label>
            <select name="kondisi_rem" class="form-control">
                @foreach(['Baik', 'Aus'] as $opsi)
                    <option value="{{ $opsi }}" {{ ($kendaraan->kondisi_rem ?? 'Baik') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Ban</label>
            <select name="kondisi_ban" class="form-control">
                @foreach(['Baik', 'Aus'] as $opsi)
                    <option value="{{ $opsi }}" {{ ($kendaraan->kondisi_ban ?? 'Baik') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Aki</label>
            <select name="kondisi_aki" class="form-control">
                @foreach(['Baik', 'Lemah'] as $opsi)
                    <option value="{{ $opsi }}" {{ ($kendaraan->kondisi_aki ?? 'Baik') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Status Kendaraan</label>
            <select name="status" class="form-control">
                <option value="aktif" {{ $kendaraan->status=='aktif'?'selected':'' }}>Aktif</option>
                <option value="dipakai" {{ $kendaraan->status=='dipakai'?'selected':'' }}>Dipakai</option>
                <option value="dalam_servis" {{ $kendaraan->status=='dalam_servis'?'selected':'' }}>Dalam Servis</option>
                <option value="tidak_aktif" {{ $kendaraan->status=='tidak_aktif'?'selected':'' }}>Tidak Aktif</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-success">Update</button>
        </div>
    </div>

</div>

</form>

@endsection
