<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanKendaraan;

class PemegangController extends Controller
{
    public function index()
    {
        $pemegang = PeminjamanKendaraan::with(['sopir', 'kendaraan'])
            ->whereIn('status', ['dipakai', 'disetujui'])
            ->latest()
            ->get();

        return view('pemegang.index', compact('pemegang'));
    }
}
