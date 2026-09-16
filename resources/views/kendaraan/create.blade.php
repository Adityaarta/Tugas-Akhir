@extends('layouts.admin')

@section('content')

<h4>Tambah Kendaraan</h4>

<form method="POST" action="/kendaraan/store">
@csrf

<div class="card p-3">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">No Polisi</label>
            <input name="no_polisi" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Jenis Kendaraan</label>
            <select name="jenis" class="form-control" required>
                <option value="truk">Truk</option>
                <option value="mobil">Mobil</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Merk</label>
            <input name="merk" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tipe</label>
            <input name="tipe" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tahun Kendaraan</label>
            <input type="number" name="tahun" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Kilometer Saat Ini</label>
            <input type="number" name="kilometer" class="form-control" min="0" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Jarak Tempuh per Tahun</label>
            <input type="number" name="jarak_tempuh_tahun" class="form-control" min="0" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Servis per Tahun</label>
            <input type="number" name="frekuensi_servis_tahun" class="form-control" min="0" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Oli</label>
            <input type="number" name="km_oli" class="form-control" min="0" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Rem</label>
            <input type="number" name="km_rem" class="form-control" min="0" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Busi</label>
            <input type="number" name="km_busi" class="form-control" min="0" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Ban</label>
            <input type="number" name="km_ban" class="form-control" min="0" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Riwayat Perawatan</label>
            <select name="riwayat_perawatan" class="form-control" required>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
                <option value="Buruk">Buruk</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jumlah Keluhan</label>
            <input type="number" name="jumlah_keluhan" class="form-control" min="0" value="0" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Riwayat Kecelakaan</label>
            <input type="number" name="riwayat_kecelakaan" class="form-control" min="0" value="0" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Rem</label>
            <select name="kondisi_rem" class="form-control" required>
                <option value="Baik">Baik</option>
                <option value="Aus">Aus</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Ban</label>
            <select name="kondisi_ban" class="form-control" required>
                <option value="Baik">Baik</option>
                <option value="Aus">Aus</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kondisi Aki</label>
            <select name="kondisi_aki" class="form-control" required>
                <option value="Baik">Baik</option>
                <option value="Lemah">Lemah</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Simpan</button>
        </div>
    </div>

</div>

</form>

@endsection
