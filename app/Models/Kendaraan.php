<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_DIPAKAI = 'dipakai';
    public const STATUS_DALAM_SERVIS = 'dalam_servis';
    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';

    // Nama tabel di database
    protected $table = 'kendaraan';

    // Primary key (default Laravel sebenarnya sudah 'id', ini opsional)
    protected $primaryKey = 'id';

    // Mass assignment (WAJIB supaya create & update tidak error)
    protected $fillable = [
        'no_polisi',
        'jenis',
        'merk',
        'tipe',
        'tahun',
        'kilometer',
        'jarak_tempuh_tahun',
        'frekuensi_servis_tahun',
        'km_oli',
        'km_rem',
        'km_busi',
        'km_ban',
        'riwayat_perawatan',
        'jumlah_keluhan',
        'riwayat_kecelakaan',
        'kondisi_rem',
        'kondisi_ban',
        'kondisi_aki',
        'status'
    ];

    // timestamps aktif (created_at, updated_at)
    public $timestamps = true;

    public function getUmurAttribute(): int
    {
        if (! $this->tahun) {
            return 0;
        }

        return max(0, now()->year - (int) $this->tahun);
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanKendaraan::class, 'kendaraan_id');
    }

    public function servis()
    {
        return $this->hasMany(Servis::class, 'kendaraan_id');
    }

    public function penggantianSparepart()
    {
        return $this->hasMany(PenggantianSparepart::class, 'kendaraan_id');
    }

    public function klasifikasi()
    {
        return $this->hasMany(KlasifikasiKendaraan::class, 'kendaraan_id');
    }
}
