@extends('layouts.admin')

@section('content')

<h4>Detail Kendaraan</h4>

<div class="card p-3">

    <p><b>No Polisi:</b> {{ $kendaraan->no_polisi }}</p>
    <p><b>Jenis:</b> {{ ucfirst($kendaraan->jenis ?? 'truk') }}</p>
    <p><b>Merk:</b> {{ $kendaraan->merk }}</p>
    <p><b>Tipe:</b> {{ $kendaraan->tipe }}</p>
    <p><b>Tahun:</b> {{ $kendaraan->tahun }}</p>
    <p><b>Umur:</b> {{ $kendaraan->umur }} tahun</p>
    <p><b>Kilometer:</b> {{ number_format($kendaraan->kilometer ?? 0) }}</p>
    <p><b>Jarak Tempuh/Tahun:</b> {{ number_format($kendaraan->jarak_tempuh_tahun ?? 0) }}</p>
    <p><b>Servis (kali/thn):</b> {{ $kendaraan->frekuensi_servis_tahun ?? 0 }}</p>
    <p><b>KM Oli:</b> {{ number_format($kendaraan->km_oli ?? 0) }}</p>
    <p><b>KM Rem:</b> {{ number_format($kendaraan->km_rem ?? 0) }}</p>
    <p><b>KM Busi:</b> {{ number_format($kendaraan->km_busi ?? 0) }}</p>
    <p><b>KM Ban:</b> {{ number_format($kendaraan->km_ban ?? 0) }}</p>
    <p><b>Riwayat Perawatan:</b> {{ $kendaraan->riwayat_perawatan ?? 'Baik' }}</p>
    <p><b>Jumlah Keluhan:</b> {{ $kendaraan->jumlah_keluhan ?? 0 }}</p>
    <p><b>Riwayat Kecelakaan:</b> {{ $kendaraan->riwayat_kecelakaan ?? 0 }}</p>
    <p><b>Kondisi Rem:</b> {{ $kendaraan->kondisi_rem ?? 'Baik' }}</p>
    <p><b>Kondisi Ban:</b> {{ $kendaraan->kondisi_ban ?? 'Baik' }}</p>
    <p><b>Kondisi Aki:</b> {{ $kendaraan->kondisi_aki ?? 'Baik' }}</p>
    <p><b>Status:</b> {{ ucfirst(str_replace('_', ' ', $kendaraan->status)) }}</p>

</div>

@endsection
