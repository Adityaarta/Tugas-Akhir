<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();

            $table->string('no_polisi');
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->integer('tahun')->nullable();

            $table->integer('kilometer')->default(0);

            // STATUS SUDAH DISAMAKAN DENGAN DASHBOARD
            $table->enum('status', ['aktif', 'dalam_servis', 'tidak_aktif'])
                  ->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};