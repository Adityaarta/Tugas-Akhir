<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'sparepart';

    protected $fillable = [
        'nama_sparepart',
        'jenis_sparepart',
        'umur_pakai',
        'satuan_umur',
        'batas_km',
        'harga_estimasi',
        'keterangan',
    ];

    protected $casts = [
        'harga_estimasi' => 'decimal:2',
    ];

    public function penggantian()
    {
        return $this->hasMany(PenggantianSparepart::class, 'sparepart_id');
    }
}
