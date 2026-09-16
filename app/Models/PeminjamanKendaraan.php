<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanKendaraan extends Model
{
    protected $table = 'peminjaman_kendaraan';

    protected $fillable = [
        'user_id',
        'sopir_id',
        'kendaraan_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tujuan',
        'km_awal',
        'km_akhir',
        'status',
        'catatan',
        'approved_by',
        'approved_at',
        'returned_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sopir()
    {
        return $this->belongsTo(Sopir::class, 'sopir_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
