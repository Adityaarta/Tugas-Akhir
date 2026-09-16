<?php

namespace Database\Seeders;

use App\Models\TrainingData;
use Illuminate\Database\Seeder;

class TrainingDataSeeder extends Seeder
{
    public function run(): void
    {
        TrainingData::query()->delete();

        foreach ($this->trainingDataSeed() as $item) {
            TrainingData::create($item);
        }
    }

    private function trainingDataSeed(): array
    {
        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'umur_kendaraan' => 1 + ($i % 4),
                'jarak_tempuh_tahun' => 8000 + (($i * 730) % 18000),
                'frekuensi_servis_tahun' => 2 + ($i % 5),
                'km_oli' => 1000 + (($i * 137) % 3600),
                'km_rem' => 6000 + (($i * 419) % 12000),
                'km_busi' => 5000 + (($i * 367) % 13000),
                'km_ban' => 10000 + (($i * 811) % 25000),
                'kelas' => 'Layak',
                'keterangan' => null,
            ];
        }

        for ($i = 1; $i <= 100; $i++) {
            $row = [
                'umur_kendaraan' => 1 + ($i % 11),
                'jarak_tempuh_tahun' => 12000 + (($i * 887) % 18000),
                'frekuensi_servis_tahun' => 2 + ($i % 4),
                'km_oli' => 1500 + (($i * 151) % 3000),
                'km_rem' => 7000 + (($i * 421) % 10000),
                'km_busi' => 6000 + (($i * 367) % 11000),
                'km_ban' => 11000 + (($i * 811) % 26000),
                'kelas' => 'Perlu Servis',
                'keterangan' => null,
            ];

            match ($i % 7) {
                0 => $row['km_oli'] = 5200 + (($i * 197) % 7600),
                1 => $row['km_rem'] = 20500 + (($i * 653) % 26000),
                2 => $row['km_busi'] = 20500 + (($i * 587) % 24000),
                3 => $row['km_ban'] = 40500 + (($i * 977) % 39000),
                4 => $row['jarak_tempuh_tahun'] = 30000 + (($i * 1103) % 32000),
                5 => $row['frekuensi_servis_tahun'] = $i % 2,
                default => $row['umur_kendaraan'] = 5 + ($i % 7),
            };

            $data[] = $row;
        }

        return $data;
    }
}
