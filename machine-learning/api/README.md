# API Prediksi Kendaraan

Jalankan API:

```bash
python api/app.py
```

Endpoint:

```text
POST /prediksi-kendaraan
```

Contoh body JSON:

```json
{
  "umur_kendaraan": 5,
  "jarak_tempuh_tahun": 25000,
  "frekuensi_servis_tahun": 2,
  "km_oli": 12000,
  "km_rem": 8000,
  "km_busi": 18000,
  "km_ban": 42000,
  "jenis_kendaraan": "truk",
  "riwayat_perawatan": "Cukup",
  "jumlah_keluhan": 3,
  "riwayat_kecelakaan": 1,
  "kondisi_rem": "Baik",
  "kondisi_ban": "Aus",
  "kondisi_aki": "Lemah"
}
```

Contoh response:

```json
{
  "naivebayes": {
    "akurasi_kelas": {
      "layak": 1.0,
      "perlu_servis": 0.0
    },
    "evaluasi_model": {
      "akurasi": 0.8238,
      "precision": 0.7647,
      "recall": 0.7206,
      "f1_score": 0.7376
    },
    "status": "layak",
    "rekomendasi_servis": ["rem", "ban", "aki"]
  },
  "decision_tree": {
    "akurasi_kelas": {
      "layak": 1.0,
      "perlu_servis": 0.0
    },
    "evaluasi_model": {
      "akurasi": 0.9435,
      "precision": 0.91,
      "recall": 0.9458,
      "f1_score": 0.9259
    },
    "status": "layak",
    "rekomendasi_servis": ["rem", "ban", "aki"]
  }
}
```

`akurasi_kelas` pada response adalah probabilitas/confidence dari masing-masing model untuk tiap kelas, bukan akurasi evaluasi keseluruhan model.
