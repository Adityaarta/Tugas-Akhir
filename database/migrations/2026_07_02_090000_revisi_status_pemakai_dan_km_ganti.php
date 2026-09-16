<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->supportsEnumModify()) {
            DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('aktif', 'dipakai', 'dalam_servis', 'tidak_aktif') NOT NULL DEFAULT 'aktif'");
            DB::statement("ALTER TABLE peminjaman_kendaraan MODIFY status ENUM('diajukan', 'dipakai', 'disetujui', 'ditolak', 'dikembalikan') NOT NULL DEFAULT 'dipakai'");
        }

        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            if (! Schema::hasColumn('peminjaman_kendaraan', 'sopir_id')) {
                $table->foreignId('sopir_id')->nullable()->after('user_id')->constrained('sopir')->nullOnDelete();
            }
        });

        Schema::table('penggantian_sparepart', function (Blueprint $table) {
            if (! Schema::hasColumn('penggantian_sparepart', 'km_ganti_berikutnya')) {
                $table->integer('km_ganti_berikutnya')->nullable()->after('kilometer_penggantian');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penggantian_sparepart', function (Blueprint $table) {
            if (Schema::hasColumn('penggantian_sparepart', 'km_ganti_berikutnya')) {
                $table->dropColumn('km_ganti_berikutnya');
            }
        });

        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_kendaraan', 'sopir_id')) {
                $table->dropConstrainedForeignId('sopir_id');
            }
        });

        if ($this->supportsEnumModify()) {
            DB::statement("ALTER TABLE peminjaman_kendaraan MODIFY status ENUM('diajukan', 'disetujui', 'ditolak', 'dikembalikan') NOT NULL DEFAULT 'diajukan'");
            DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('aktif', 'dalam_servis', 'tidak_aktif') NOT NULL DEFAULT 'aktif'");
        }
    }

    private function supportsEnumModify(): bool
    {
        return in_array(DB::getDriverName(), ['mysql', 'mariadb'], true);
    }
};
