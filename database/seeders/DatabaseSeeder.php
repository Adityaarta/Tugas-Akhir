<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\PengaturanAplikasi;
use App\Models\Sopir;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin UD Sentosa',
                'email' => 'admin@udsentosa.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Pengguna Operasional',
                'email' => 'operasional@udsentosa.test',
                'role' => 'pengguna_operasional',
            ],
            [
                'name' => 'Kepala Bagian',
                'email' => 'kepala@udsentosa.test',
                'role' => 'kepala_bagian',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        PengaturanAplikasi::firstOrCreate([], [
            'nama_aplikasi' => 'UD Sentosa',
            'nama_instansi' => 'UD Sentosa',
            'email' => 'admin@udsentosa.test',
            'telepon' => '081234567800',
            'alamat' => 'Indonesia',
            'deskripsi' => 'Sistem Pengelolaan Kendaraan Operasional',
        ]);

        $kendaraan = $this->kendaraanSeed();

        foreach ($kendaraan as $item) {
            Kendaraan::updateOrCreate(['no_polisi' => $item['no_polisi']], $item);
        }

        Kendaraan::whereNotIn('no_polisi', array_column($kendaraan, 'no_polisi'))->delete();

        $sopir = [
            ['nama' => 'Budi Santoso', 'no_sim' => 'SIM-B1-001', 'no_hp' => '081234567801'],
            ['nama' => 'Agus Pratama', 'no_sim' => 'SIM-B1-002', 'no_hp' => '081234567802'],
            ['nama' => 'Dedi Kurniawan', 'no_sim' => 'SIM-B1-003', 'no_hp' => '081234567803'],
        ];

        foreach ($sopir as $item) {
            Sopir::updateOrCreate(['no_sim' => $item['no_sim']], $item);
        }

        $sparepart = [
            ['nama_sparepart' => 'Oli Mesin', 'jenis_sparepart' => 'Pelumas', 'umur_pakai' => 5000, 'satuan_umur' => 'km', 'batas_km' => 5000, 'harga_estimasi' => 450000],
            ['nama_sparepart' => 'Kampas Rem', 'jenis_sparepart' => 'Rem', 'umur_pakai' => 20000, 'satuan_umur' => 'km', 'batas_km' => 20000, 'harga_estimasi' => 750000],
            ['nama_sparepart' => 'Busi', 'jenis_sparepart' => 'Pengapian', 'umur_pakai' => 20000, 'satuan_umur' => 'km', 'batas_km' => 20000, 'harga_estimasi' => 250000],
            ['nama_sparepart' => 'Ban', 'jenis_sparepart' => 'Roda', 'umur_pakai' => 40000, 'satuan_umur' => 'km', 'batas_km' => 40000, 'harga_estimasi' => 1800000],
        ];

        foreach ($sparepart as $item) {
            Sparepart::updateOrCreate(['nama_sparepart' => $item['nama_sparepart']], $item);
        }

    }

    private function kendaraanSeed(): array
    {
        $truk = [
            ['Mitsubishi', 'Colt Diesel'],
            ['Mitsubishi', 'Canter'],
            ['Isuzu', 'Elf'],
            ['Hino', 'Dutro'],
            ['Toyota', 'Dyna'],
        ];
        $mobil = [
            ['Toyota', 'Avanza'],
            ['Daihatsu', 'Gran Max'],
            ['Suzuki', 'Carry'],
            ['Mitsubishi', 'L300'],
            ['Toyota', 'Innova'],
        ];

        $data = [];

        for ($i = 1; $i <= 26; $i++) {
            [$merk, $tipe] = $truk[($i - 1) % count($truk)];
            $tahun = 2016 + ($i % 9);
            $kilometer = 22000 + ($i * 3100);
            $umur = max(1, now()->year - $tahun);
            $data[] = [
                'no_polisi' => 'B '.(9000 + $i).' UDS',
                'jenis' => 'truk',
                'merk' => $merk,
                'tipe' => $tipe,
                'tahun' => $tahun,
                'kilometer' => $kilometer,
                'jarak_tempuh_tahun' => (int) round($kilometer / $umur),
                'frekuensi_servis_tahun' => 1 + ($i % 5),
                'km_oli' => 1000 + ($kilometer % 7000),
                'km_rem' => 6000 + ($kilometer % 26000),
                'km_busi' => 5000 + ($kilometer % 26000),
                'km_ban' => 10000 + ($kilometer % 45000),
                'riwayat_perawatan' => $i % 4 === 0 ? 'Cukup' : 'Baik',
                'jumlah_keluhan' => $i % 5,
                'riwayat_kecelakaan' => $i % 11 === 0 ? 1 : 0,
                'kondisi_rem' => (6000 + ($kilometer % 26000)) >= 20000 ? 'Aus' : 'Baik',
                'kondisi_ban' => (10000 + ($kilometer % 45000)) >= 40000 ? 'Aus' : 'Baik',
                'kondisi_aki' => $i % 9 === 0 ? 'Lemah' : 'Baik',
                'status' => 'aktif',
            ];
        }

        for ($i = 1; $i <= 14; $i++) {
            [$merk, $tipe] = $mobil[($i - 1) % count($mobil)];
            $tahun = 2018 + ($i % 7);
            $kilometer = 12000 + ($i * 2400);
            $umur = max(1, now()->year - $tahun);
            $data[] = [
                'no_polisi' => 'B '.(7000 + $i).' UDS',
                'jenis' => 'mobil',
                'merk' => $merk,
                'tipe' => $tipe,
                'tahun' => $tahun,
                'kilometer' => $kilometer,
                'jarak_tempuh_tahun' => (int) round($kilometer / $umur),
                'frekuensi_servis_tahun' => 1 + ($i % 4),
                'km_oli' => 800 + ($kilometer % 6500),
                'km_rem' => 5000 + ($kilometer % 24000),
                'km_busi' => 5000 + ($kilometer % 22000),
                'km_ban' => 9000 + ($kilometer % 42000),
                'riwayat_perawatan' => $i % 5 === 0 ? 'Cukup' : 'Baik',
                'jumlah_keluhan' => $i % 4,
                'riwayat_kecelakaan' => $i % 10 === 0 ? 1 : 0,
                'kondisi_rem' => (5000 + ($kilometer % 24000)) >= 20000 ? 'Aus' : 'Baik',
                'kondisi_ban' => (9000 + ($kilometer % 42000)) >= 40000 ? 'Aus' : 'Baik',
                'kondisi_aki' => $i % 8 === 0 ? 'Lemah' : 'Baik',
                'status' => 'aktif',
            ];
        }

        return $data;
    }

}
