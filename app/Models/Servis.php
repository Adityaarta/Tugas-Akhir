<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    protected $table = 'servis_kendaraan';

    protected $fillable = [
        'kendaraan_id',
        'no_servis',
        'tanggal_servis',
        'kilometer',
        'umur_tahun',
        'lama_servis',
        'frekuensi_servis',
        'keluhan',
        'jenis_kerusakan',
        'jenis_servis',
        'mekanik',
        'biaya',
        'status',
        'catatan',
        'hasil_naive_bayes',
        'probabilitas_nb',
        'hasil_decision_tree',
        'probabilitas_dt',
        'algoritma_terbaik',
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'biaya' => 'decimal:2',
    ];

    public $timestamps = true;

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
