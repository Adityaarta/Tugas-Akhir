<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sparepart', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sparepart');
            $table->string('jenis_sparepart')->nullable();
            $table->integer('umur_pakai')->default(0);
            $table->string('satuan_umur')->default('km');
            $table->integer('batas_km')->nullable();
            $table->decimal('harga_estimasi', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sparepart');
    }
};
