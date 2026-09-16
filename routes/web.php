<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemegangController;
use App\Http\Controllers\PeminjamanKendaraanController;
use App\Http\Controllers\PenggantianSparepartController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\SparepartController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:pengguna_operasional')->group(function () {
        Route::get('/kendaraan', [KendaraanController::class, 'index']);
        Route::get('/kendaraan/create', [KendaraanController::class, 'create']);
        Route::post('/kendaraan/store', [KendaraanController::class, 'store']);
        Route::get('/kendaraan/edit/{id}', [KendaraanController::class, 'edit']);
        Route::post('/kendaraan/update/{id}', [KendaraanController::class, 'update']);
        Route::get('/kendaraan/delete/{id}', [KendaraanController::class, 'destroy']);
        Route::get('/kendaraan/show/{id}', [KendaraanController::class, 'show']);

        Route::get('/pemegang', [PemegangController::class, 'index']);

        Route::get('/sopir', [SopirController::class, 'index']);
        Route::post('/sopir/store', [SopirController::class, 'store']);
        Route::get('/sopir/edit/{id}', [SopirController::class, 'edit']);
        Route::post('/sopir/update/{id}', [SopirController::class, 'update']);
        Route::get('/sopir/delete/{id}', [SopirController::class, 'destroy']);

        Route::get('/sparepart', [SparepartController::class, 'index']);
        Route::post('/sparepart/store', [SparepartController::class, 'store']);
        Route::get('/sparepart/edit/{id}', [SparepartController::class, 'edit']);
        Route::post('/sparepart/update/{id}', [SparepartController::class, 'update']);
        Route::get('/sparepart/delete/{id}', [SparepartController::class, 'destroy']);

        Route::get('/data-pemakai', [PeminjamanKendaraanController::class, 'index']);
        Route::post('/data-pemakai/store', [PeminjamanKendaraanController::class, 'store']);
        Route::get('/data-pemakai/edit/{id}', [PeminjamanKendaraanController::class, 'edit']);
        Route::post('/data-pemakai/update/{id}', [PeminjamanKendaraanController::class, 'update']);
        Route::get('/data-pemakai/approve/{id}', [PeminjamanKendaraanController::class, 'approve']);
        Route::get('/data-pemakai/reject/{id}', [PeminjamanKendaraanController::class, 'reject']);
        Route::get('/data-pemakai/return/{id}', [PeminjamanKendaraanController::class, 'returnForm']);
        Route::post('/data-pemakai/return/{id}', [PeminjamanKendaraanController::class, 'returnStore']);
        Route::get('/data-pemakai/delete/{id}', [PeminjamanKendaraanController::class, 'destroy']);
        Route::get('/peminjaman-kendaraan', fn () => redirect('/data-pemakai'));
        Route::get('/pengembalian', fn () => redirect('/data-pemakai'));

        Route::get('/servis', [ServisController::class, 'index']);
        Route::post('/servis/store', [ServisController::class, 'store']);
        Route::get('/servis/edit/{id}', [ServisController::class, 'edit']);
        Route::post('/servis/update/{id}', [ServisController::class, 'update']);
        Route::get('/servis/delete/{id}', [ServisController::class, 'destroy']);

        Route::get('/penggantian-sparepart', [PenggantianSparepartController::class, 'index']);
        Route::post('/penggantian-sparepart/store', [PenggantianSparepartController::class, 'store']);
        Route::get('/penggantian-sparepart/edit/{id}', [PenggantianSparepartController::class, 'edit']);
        Route::post('/penggantian-sparepart/update/{id}', [PenggantianSparepartController::class, 'update']);
        Route::get('/penggantian-sparepart/delete/{id}', [PenggantianSparepartController::class, 'destroy']);

        Route::post('/klasifikasi/store', [KlasifikasiController::class, 'store']);
        Route::get('/klasifikasi/delete/{id}', [KlasifikasiController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/pengguna', [PenggunaController::class, 'index']);
        Route::post('/pengguna/store', [PenggunaController::class, 'store']);
        Route::get('/pengguna/edit/{id}', [PenggunaController::class, 'edit']);
        Route::post('/pengguna/update/{id}', [PenggunaController::class, 'update']);
        Route::get('/pengguna/delete/{id}', [PenggunaController::class, 'destroy']);

        Route::get('/pengaturan', [PengaturanController::class, 'index']);
        Route::post('/pengaturan/update', [PengaturanController::class, 'update']);
    });

    Route::middleware('role:pengguna_operasional,kepala_bagian')->group(function () {
        Route::get('/riwayat-servis', [ServisController::class, 'riwayat']);
        Route::get('/prediksi-naive-bayes', [KlasifikasiController::class, 'naiveBayes']);
        Route::get('/prediksi-decision-tree', [KlasifikasiController::class, 'decisionTree']);
        Route::get('/perbandingan-hasil', [KlasifikasiController::class, 'perbandingan']);
        Route::get('/laporan-kendaraan', [LaporanController::class, 'kendaraan']);
        Route::get('/laporan-servis', [LaporanController::class, 'servis']);
        Route::get('/laporan-prediksi', [LaporanController::class, 'prediksi']);
    });
});

require __DIR__.'/auth.php';
