<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggantian_sparepart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->constrained('sparepart')->cascadeOnDelete();
            $table->date('tanggal_penggantian');
            $table->integer('kilometer_penggantian');
            $table->integer('umur_sparepart')->default(0);
            $table->enum('kondisi_sparepart', ['baik', 'aus', 'rusak', 'diganti'])->default('diganti');
            $table->decimal('biaya', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggantian_sparepart');
    }
};
