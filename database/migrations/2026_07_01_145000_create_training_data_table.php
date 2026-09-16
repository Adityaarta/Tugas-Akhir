<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('umur_kendaraan');
            $table->unsignedInteger('jarak_tempuh_tahun');
            $table->unsignedInteger('frekuensi_servis_tahun');
            $table->unsignedInteger('km_oli');
            $table->unsignedInteger('km_rem');
            $table->unsignedInteger('km_busi');
            $table->unsignedInteger('km_ban');
            $table->enum('kelas', ['Layak', 'Perlu Servis']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_data');
    }
};
