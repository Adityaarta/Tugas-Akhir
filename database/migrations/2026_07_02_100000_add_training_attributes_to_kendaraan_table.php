<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            if (! Schema::hasColumn('kendaraan', 'jarak_tempuh_tahun')) {
                $table->integer('jarak_tempuh_tahun')->default(0)->after('kilometer');
            }

            if (! Schema::hasColumn('kendaraan', 'frekuensi_servis_tahun')) {
                $table->integer('frekuensi_servis_tahun')->default(0)->after('jarak_tempuh_tahun');
            }

            if (! Schema::hasColumn('kendaraan', 'km_oli')) {
                $table->integer('km_oli')->default(0)->after('frekuensi_servis_tahun');
            }

            if (! Schema::hasColumn('kendaraan', 'km_rem')) {
                $table->integer('km_rem')->default(0)->after('km_oli');
            }

            if (! Schema::hasColumn('kendaraan', 'km_busi')) {
                $table->integer('km_busi')->default(0)->after('km_rem');
            }

            if (! Schema::hasColumn('kendaraan', 'km_ban')) {
                $table->integer('km_ban')->default(0)->after('km_busi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            foreach (['km_ban', 'km_busi', 'km_rem', 'km_oli', 'frekuensi_servis_tahun', 'jarak_tempuh_tahun'] as $column) {
                if (Schema::hasColumn('kendaraan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
