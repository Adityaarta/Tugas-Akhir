<?php

namespace App\Services;

use App\Models\Kendaraan;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PrediksiKendaraanApiService
{
    public function prediksi(Kendaraan $kendaraan): array
    {
        $payload = $this->buatPayload($kendaraan);
        $endpoint = rtrim((string) config('services.prediksi_kendaraan.url'), '/');

        if ($endpoint === '') {
            throw new RuntimeException('URL API prediksi kendaraan belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $payload);
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'API prediksi kendaraan tidak dapat dihubungi. Jalankan API Python dengan perintah: cd machine-learning && python api/app.py'
            );
        }

        if (! $response->successful()) {
            $message = $response->json('error') ?: $response->body();

            throw new RuntimeException('API prediksi kendaraan gagal memproses data. '.$message);
        }

        $data = $response->json();

        if (! isset($data['naivebayes'], $data['decision_tree'])) {
            throw new RuntimeException('Format response API prediksi kendaraan tidak sesuai.');
        }

        return [
            'payload' => $payload,
            'raw' => $data,
            'klasifikasi' => $this->ubahKeDataKlasifikasi($data),
        ];
    }

    private function buatPayload(Kendaraan $kendaraan): array
    {
        return [
            'umur_kendaraan' => (int) $kendaraan->umur,
            'jarak_tempuh_tahun' => (int) $kendaraan->jarak_tempuh_tahun,
            'frekuensi_servis_tahun' => (int) $kendaraan->frekuensi_servis_tahun,
            'km_oli' => (int) $kendaraan->km_oli,
            'km_rem' => (int) $kendaraan->km_rem,
            'km_busi' => (int) $kendaraan->km_busi,
            'km_ban' => (int) $kendaraan->km_ban,
            'jenis_kendaraan' => $kendaraan->jenis ?: 'truk',
            'riwayat_perawatan' => $kendaraan->riwayat_perawatan ?: 'Baik',
            'jumlah_keluhan' => (int) $kendaraan->jumlah_keluhan,
            'riwayat_kecelakaan' => (int) $kendaraan->riwayat_kecelakaan,
            'kondisi_rem' => $kendaraan->kondisi_rem ?: 'Baik',
            'kondisi_ban' => $kendaraan->kondisi_ban ?: 'Baik',
            'kondisi_aki' => $kendaraan->kondisi_aki ?: 'Baik',
        ];
    }

    private function ubahKeDataKlasifikasi(array $data): array
    {
        $nb = $data['naivebayes'];
        $dt = $data['decision_tree'];
        $akurasiNb = $this->akurasiModel($nb);
        $akurasiDt = $this->akurasiModel($dt);
        $rekomendasi = $this->buatKalimatRekomendasi($nb['rekomendasi_servis'] ?? []);

        return [
            'hasil_naive_bayes' => $this->statusKeLabel($nb['status'] ?? 'layak'),
            'probabilitas_nb' => $akurasiNb,
            'hasil_decision_tree' => $this->statusKeLabel($dt['status'] ?? 'layak'),
            'probabilitas_dt' => $akurasiDt,
            'algoritma_terbaik' => $akurasiNb >= $akurasiDt ? 'Naive Bayes' : 'Decision Tree',
            'rekomendasi' => $rekomendasi,
        ];
    }

    private function statusKeLabel(string $status): string
    {
        return $status === 'perlu_servis' ? 'Perlu Servis' : 'Layak';
    }

    private function akurasiModel(array $hasilModel): float
    {
        $nilai = (float) ($hasilModel['evaluasi_model']['akurasi'] ?? 0);

        return round($nilai <= 1 ? $nilai * 100 : $nilai, 2);
    }

    private function buatKalimatRekomendasi(array $komponen): string
    {
        if (empty($komponen)) {
            return 'Kendaraan masih layak digunakan, lakukan monitoring berkala.';
        }

        return 'Perlu servis: '.implode(', ', array_map('strtolower', $komponen)).'.';
    }
}
