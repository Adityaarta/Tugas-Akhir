<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingData extends Model
{
    protected $table = 'training_data';

    protected $fillable = [
        'umur_kendaraan',
        'jarak_tempuh_tahun',
        'frekuensi_servis_tahun',
        'km_oli',
        'km_rem',
        'km_busi',
        'km_ban',
        'kelas',
        'keterangan',
    ];

    protected $casts = [
        'umur_kendaraan' => 'integer',
        'jarak_tempuh_tahun' => 'integer',
        'frekuensi_servis_tahun' => 'integer',
        'km_oli' => 'integer',
        'km_rem' => 'integer',
        'km_busi' => 'integer',
        'km_ban' => 'integer',
    ];
}
