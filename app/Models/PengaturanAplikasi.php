<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanAplikasi extends Model
{
    protected $table = 'pengaturan_aplikasi';

    protected $fillable = [
        'nama_aplikasi',
        'nama_instansi',
        'email',
        'telepon',
        'alamat',
        'deskripsi',
    ];
}
