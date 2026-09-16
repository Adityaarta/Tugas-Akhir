@extends('layouts.admin')

@section('content')
@php
    $canManageKlasifikasi = in_array(auth()->user()?->role, ['admin', 'pengguna_operasional'], true);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $title }}</h4>
    @if($canManageKlasifikasi)
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalKlasifikasi">
        + Proses Klasifikasi
    </button>
    @endif
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
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>Umur</th>
                <th>Jarak/Thn</th>
                <th>Servis (kali/thn)</th>
                <th>KM Oli</th>
                <th>KM Rem</th>
                <th>KM Busi</th>
                <th>KM Ban</th>
                <th>Hasil</th>
                <th>Akurasi</th>
                <th>Rekomendasi</th>
                @if($canManageKlasifikasi)
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($klasifikasi as $i => $item)
            @php
                $hasil = $mode === 'naive-bayes' ? $item->hasil_naive_bayes : $item->hasil_decision_tree;
                $probabilitas = $mode === 'naive-bayes' ? $item->probabilitas_nb : $item->probabilitas_dt;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ optional($item->tanggal_klasifikasi)->format('d-m-Y') }}</td>
                <td>{{ $item->kendaraan->no_polisi ?? '-' }}</td>
                <td>{{ $item->umur_kendaraan }} thn</td>
                <td>{{ number_format($item->jarak_tempuh_tahun) }}</td>
                <td>{{ $item->frekuensi_servis_tahun }}</td>
                <td>{{ number_format($item->km_oli) }}</td>
                <td>{{ number_format($item->km_rem) }}</td>
                <td>{{ number_format($item->km_busi) }}</td>
                <td>{{ number_format($item->km_ban) }}</td>
                <td>
                    <span class="badge {{ $hasil === 'Perlu Servis' ? 'bg-danger' : 'bg-success' }}">
                        {{ $hasil }}
                    </span>
                </td>
                <td>{{ $probabilitas }}%</td>
                <td>{{ $item->rekomendasi ?: $item->rekomendasiUntukHasil($hasil) }}</td>
                @if($canManageKlasifikasi)
                    <td>
                        <a href="/klasifikasi/delete/{{ $item->id }}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin hapus data klasifikasi ini?')">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($canManageKlasifikasi)
<div class="modal fade" id="modalKlasifikasi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/klasifikasi/store" method="POST">
                @csrf
                <input type="hidden" name="source" value="{{ $source }}">

                <div class="modal-header">
                    <h5 class="modal-title">Input Data Uji Klasifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Kendaraan</label>
                            <select name="kendaraan_id" id="klasifikasiKendaraan" class="form-control" required>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}"
                                        data-umur="{{ $item->umur }}"
                                        data-jarak-tempuh-tahun="{{ $item->jarak_tempuh_tahun ?? 0 }}"
                                        data-frekuensi-servis-tahun="{{ $item->frekuensi_servis_tahun ?? 0 }}"
                                        data-km-oli="{{ $item->km_oli ?? 0 }}"
                                        data-km-rem="{{ $item->km_rem ?? 0 }}"
                                        data-km-busi="{{ $item->km_busi ?? 0 }}"
                                        data-km-ban="{{ $item->km_ban ?? 0 }}"
                                        data-riwayat-perawatan="{{ $item->riwayat_perawatan ?? 'Baik' }}"
                                        data-jumlah-keluhan="{{ $item->jumlah_keluhan ?? 0 }}"
                                        data-riwayat-kecelakaan="{{ $item->riwayat_kecelakaan ?? 0 }}"
                                        data-kondisi-rem="{{ $item->kondisi_rem ?? 'Baik' }}"
                                        data-kondisi-ban="{{ $item->kondisi_ban ?? 'Baik' }}"
                                        data-kondisi-aki="{{ $item->kondisi_aki ?? 'Baik' }}">
                                        {{ $item->no_polisi }} - {{ ucfirst($item->jenis ?? 'truk') }} {{ trim(($item->merk ?? '').' '.($item->tipe ?? '')) ?: 'Kendaraan' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Umur Kendaraan</label>
                            <input type="number" id="umurKendaraanPreview" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jarak Tempuh/Tahun</label>
                            <input type="number" name="jarak_tempuh_tahun" class="form-control klasifikasi-auto" data-key="jarakTempuhTahun" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Servis (kali/thn)</label>
                            <input type="number" name="frekuensi_servis_tahun" class="form-control klasifikasi-auto" data-key="frekuensiServisTahun" min="0" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">KM Oli</label>
                            <input type="number" name="km_oli" class="form-control klasifikasi-auto" data-key="kmOli" min="0" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">KM Rem</label>
                            <input type="number" name="km_rem" class="form-control klasifikasi-auto" data-key="kmRem" min="0" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">KM Busi</label>
                            <input type="number" name="km_busi" class="form-control klasifikasi-auto" data-key="kmBusi" min="0" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">KM Ban</label>
                            <input type="number" name="km_ban" class="form-control klasifikasi-auto" data-key="kmBan" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Riwayat Perawatan</label>
                            <input type="text" id="riwayatPerawatanPreview" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Keluhan</label>
                            <input type="number" id="jumlahKeluhanPreview" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Riwayat Kecelakaan</label>
                            <input type="number" id="riwayatKecelakaanPreview" class="form-control" min="0" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kondisi Rem</label>
                            <input type="text" id="kondisiRemPreview" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kondisi Ban</label>
                            <input type="text" id="kondisiBanPreview" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kondisi Aki</label>
                            <input type="text" id="kondisiAkiPreview" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    $(function () {
        function syncKlasifikasiFields() {
            const selected = $('#klasifikasiKendaraan option:selected');

            $('#umurKendaraanPreview').val(selected.attr('data-umur') || 0);
            $('[name="jarak_tempuh_tahun"]').val(selected.attr('data-jarak-tempuh-tahun') || 0);
            $('[name="frekuensi_servis_tahun"]').val(selected.attr('data-frekuensi-servis-tahun') || 0);
            $('[name="km_oli"]').val(selected.attr('data-km-oli') || 0);
            $('[name="km_rem"]').val(selected.attr('data-km-rem') || 0);
            $('[name="km_busi"]').val(selected.attr('data-km-busi') || 0);
            $('[name="km_ban"]').val(selected.attr('data-km-ban') || 0);
            $('#riwayatPerawatanPreview').val(selected.attr('data-riwayat-perawatan') || 'Baik');
            $('#jumlahKeluhanPreview').val(selected.attr('data-jumlah-keluhan') || 0);
            $('#riwayatKecelakaanPreview').val(selected.attr('data-riwayat-kecelakaan') || 0);
            $('#kondisiRemPreview').val(selected.attr('data-kondisi-rem') || 'Baik');
            $('#kondisiBanPreview').val(selected.attr('data-kondisi-ban') || 'Baik');
            $('#kondisiAkiPreview').val(selected.attr('data-kondisi-aki') || 'Baik');
        }

        $('#klasifikasiKendaraan').on('change', syncKlasifikasiFields);
        $('#modalKlasifikasi').on('shown.bs.modal', syncKlasifikasiFields);
        syncKlasifikasiFields();
    });
</script>
@endpush
