<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            if (! Schema::hasColumn('kendaraan', 'riwayat_perawatan')) {
                $table->string('riwayat_perawatan')->default('Baik')->after('km_ban');
            }

            if (! Schema::hasColumn('kendaraan', 'jumlah_keluhan')) {
                $table->unsignedInteger('jumlah_keluhan')->default(0)->after('riwayat_perawatan');
            }

            if (! Schema::hasColumn('kendaraan', 'riwayat_kecelakaan')) {
                $table->unsignedInteger('riwayat_kecelakaan')->default(0)->after('jumlah_keluhan');
            }

            if (! Schema::hasColumn('kendaraan', 'kondisi_rem')) {
                $table->string('kondisi_rem')->default('Baik')->after('riwayat_kecelakaan');
            }

            if (! Schema::hasColumn('kendaraan', 'kondisi_ban')) {
                $table->string('kondisi_ban')->default('Baik')->after('kondisi_rem');
            }

            if (! Schema::hasColumn('kendaraan', 'kondisi_aki')) {
                $table->string('kondisi_aki')->default('Baik')->after('kondisi_ban');
            }
        });

        DB::table('kendaraan')
            ->orderBy('id')
            ->chunkById(100, function ($kendaraan) {
                foreach ($kendaraan as $item) {
                    DB::table('kendaraan')
                        ->where('id', $item->id)
                        ->update([
                            'kondisi_rem' => ((int) ($item->km_rem ?? 0)) >= 20000 ? 'Aus' : 'Baik',
                            'kondisi_ban' => ((int) ($item->km_ban ?? 0)) >= 40000 ? 'Aus' : 'Baik',
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            foreach ([
                'kondisi_aki',
                'kondisi_ban',
                'kondisi_rem',
                'riwayat_kecelakaan',
                'jumlah_keluhan',
                'riwayat_perawatan',
            ] as $column) {
                if (Schema::hasColumn('kendaraan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
