<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiKendaraan extends Model
{
    protected $table = 'klasifikasi_kendaraan';

    protected $fillable = [
        'kendaraan_id',
        'umur_kendaraan',
        'jarak_tempuh_tahun',
        'frekuensi_servis_tahun',
        'km_oli',
        'km_rem',
        'km_busi',
        'km_ban',
        'hasil_naive_bayes',
        'probabilitas_nb',
        'hasil_decision_tree',
        'probabilitas_dt',
        'algoritma_terbaik',
        'rekomendasi',
        'tanggal_klasifikasi',
    ];

    protected $casts = [
        'tanggal_klasifikasi' => 'date',
        'probabilitas_nb' => 'decimal:2',
        'probabilitas_dt' => 'decimal:2',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function rekomendasiUntukHasil(string $hasil): string
    {
        if ($hasil === 'Layak') {
            return 'Kendaraan masih layak digunakan, lakukan monitoring berkala.';
        }

        $parts = [];

        if ($this->km_oli >= 5000) {
            $parts[] = 'ganti oli';
        }

        if ($this->km_rem >= 20000) {
            $parts[] = 'cek kampas rem';
        }

        if ($this->km_busi >= 20000) {
            $parts[] = 'cek busi';
        }

        if ($this->km_ban >= 40000) {
            $parts[] = 'cek ban';
        }

        return 'Perlu servis: '.(empty($parts) ? 'lakukan pemeriksaan menyeluruh.' : implode(', ', $parts).'.');
    }
}
