<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->integer('umur_kendaraan');
            $table->integer('jarak_tempuh_tahun');
            $table->integer('frekuensi_servis_tahun');
            $table->integer('km_oli');
            $table->integer('km_rem');
            $table->integer('km_busi');
            $table->integer('km_ban');
            $table->string('hasil_naive_bayes');
            $table->decimal('probabilitas_nb', 5, 2)->default(0);
            $table->string('hasil_decision_tree');
            $table->decimal('probabilitas_dt', 5, 2)->default(0);
            $table->string('algoritma_terbaik')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->date('tanggal_klasifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_kendaraan');
    }
};
