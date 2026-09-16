@extends('layouts.admin')

@section('content')

<h4 class="mb-3">Kendaraan</h4>

{{-- ================= KPI ================= --}}
<div class="row g-3">

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Total Kendaraan</h6>
            <h3>{{ $total ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Kendaraan Aktif</h6>
            <h3>{{ $aktif ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Dalam Servis</h6>
            <h3>{{ $servis ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-box p-3">
            <h6>Dipakai</h6>
            <h3>{{ $dipakai ?? 0 }}</h3>
        </div>
    </div>

</div>

{{-- ================= TABLE ================= --}}
<div class="card card-box p-3 mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h6>Data Kendaraan</h6>

        <div class="d-flex gap-2">

            {{-- ✔ BUTTON MODAL --}}
            <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalKendaraan">
                + Tambah
            </button>

        </div>
    </div>

    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>No Polisi</th>
                <th>Jenis</th>
                <th>Merk</th>
                <th>Tipe</th>
                <th>Tahun</th>
                <th>Kilometer</th>
                <th>Jarak/Thn</th>
                <th>Servis/Thn</th>
                <th>KM Oli</th>
                <th>KM Rem</th>
                <th>KM Busi</th>
                <th>KM Ban</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($kendaraan ?? [] as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->no_polisi ?? '-' }}</td>
                <td>{{ ucfirst($k->jenis ?? 'truk') }}</td>
                <td>{{ $k->merk ?? '-' }}</td>
                <td>{{ $k->tipe ?? '-' }}</td>
                <td>{{ $k->tahun ?? '-' }}</td>
                <td>{{ number_format($k->kilometer ?? 0) }}</td>
                <td>{{ number_format($k->jarak_tempuh_tahun ?? 0) }}</td>
                <td>{{ $k->frekuensi_servis_tahun ?? 0 }}</td>
                <td>{{ number_format($k->km_oli ?? 0) }}</td>
                <td>{{ number_format($k->km_rem ?? 0) }}</td>
                <td>{{ number_format($k->km_busi ?? 0) }}</td>
                <td>{{ number_format($k->km_ban ?? 0) }}</td>

                <td>
                    @if($k->status == 'aktif')
                        <span class="badge bg-success">Aktif</span>
                    @elseif($k->status == 'dipakai')
                        <span class="badge bg-warning text-dark">Dipakai</span>
                    @elseif($k->status == 'dalam_servis')
                        <span class="badge bg-warning text-dark">Dalam Servis</span>
                    @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                </td>

                <td class="d-flex gap-1">

                    <a href="/kendaraan/show/{{ $k->id }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-eye"></i>
                    </a>

                    <a href="/kendaraan/edit/{{ $k->id }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="/kendaraan/delete/{{ $k->id }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin hapus data ini?')">
                        <i class="fa fa-trash"></i>
                    </a>

                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

{{-- ================= MODAL TAMBAH (WAJIB ADA) ================= --}}
<div class="modal fade" id="modalKendaraan" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form action="/kendaraan/store" method="POST">
        @csrf

        <div class="modal-header">
            <h5 class="modal-title">Tambah Kendaraan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">No Polisi</label>
                    <input type="text" name="no_polisi" class="form-control" required>
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
                    <input type="text" name="merk" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe</label>
                    <input type="text" name="tipe" class="form-control">
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
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Batal
            </button>

            <button class="btn btn-primary">
                Simpan
            </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
