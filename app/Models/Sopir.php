<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sopir extends Model
{
    // pastikan sesuai nama tabel di database
    protected $table = 'sopir';

    // amanin primary key
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'no_sim',
        'no_hp',
    ];

    // kalau tabel kamu pakai created_at & updated_at
    public $timestamps = true;
}
