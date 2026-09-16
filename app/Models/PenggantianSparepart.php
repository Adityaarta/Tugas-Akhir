<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggantianSparepart extends Model
{
    protected $table = 'penggantian_sparepart';

    protected $fillable = [
        'kendaraan_id',
        'sparepart_id',
        'tanggal_penggantian',
        'kilometer_penggantian',
        'km_ganti_berikutnya',
        'umur_sparepart',
        'kondisi_sparepart',
        'biaya',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_penggantian' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }
}
