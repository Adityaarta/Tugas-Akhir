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
        Schema::create('servis_kendaraan', function (Blueprint $table) {

            $table->id();

            // Relasi ke kendaraan
            $table->foreignId('kendaraan_id')
                ->constrained('kendaraan')
                ->cascadeOnDelete();

            // Nomor servis otomatis
            $table->string('no_servis')->unique();

            // Tanggal servis
            $table->date('tanggal_servis');

            // Kondisi kendaraan
            $table->integer('kilometer');

            $table->integer('umur_tahun');

            $table->integer('lama_servis');

            $table->integer('frekuensi_servis')->default(1);

            // Keluhan
            $table->string('keluhan');

            // Jenis kerusakan
            $table->string('jenis_kerusakan');

            // Hasil akhir servis
            $table->enum('jenis_servis',[
                'Periodic Maintenance',
                'General Repair',
                'Body Repair'
            ]);

            // Bengkel / Mekanik
            $table->string('mekanik');

            // Biaya
            $table->decimal('biaya',12,2)->default(0);

            // Status pengerjaan
            $table->enum('status',[
                'Menunggu',
                'Sedang Dikerjakan',
                'Selesai'
            ])->default('Menunggu');

            // Catatan
            $table->text('catatan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | HASIL NAIVE BAYES
            |--------------------------------------------------------------------------
            */

            $table->string('hasil_naive_bayes')->nullable();

            $table->decimal('probabilitas_nb',5,2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | HASIL DECISION TREE
            |--------------------------------------------------------------------------
            */

            $table->string('hasil_decision_tree')->nullable();

            $table->decimal('probabilitas_dt',5,2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | ALGORITMA TERBAIK
            |--------------------------------------------------------------------------
            */

            $table->string('algoritma_terbaik')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis_kendaraan');
    }
};