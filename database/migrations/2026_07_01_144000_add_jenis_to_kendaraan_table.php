<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kendaraan', 'jenis')) {
            Schema::table('kendaraan', function (Blueprint $table) {
                $table->enum('jenis', ['truk', 'mobil'])->default('truk')->after('no_polisi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kendaraan', 'jenis')) {
            Schema::table('kendaraan', function (Blueprint $table) {
                $table->dropColumn('jenis');
            });
        }
    }
};
