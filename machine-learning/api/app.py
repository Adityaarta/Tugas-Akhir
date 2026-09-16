from pathlib import Path

import joblib
import pandas as pd
from flask import Flask, jsonify, request


BASE_DIR = Path(__file__).resolve().parent
MODEL_PATH = BASE_DIR / "model_kendaraan.pkl"

FEATURE_COLUMNS = [
    "umur_kendaraan",
    "jarak_tempuh_tahun",
    "frekuensi_servis_tahun",
    "km_oli",
    "km_rem",
    "km_busi",
    "km_ban",
    "jenis_kendaraan",
    "riwayat_perawatan",
    "jumlah_keluhan",
    "riwayat_kecelakaan",
    "kondisi_rem",
    "kondisi_ban",
    "kondisi_aki",
]

NUMERIC_COLUMNS = {
    "umur_kendaraan",
    "jarak_tempuh_tahun",
    "frekuensi_servis_tahun",
    "km_oli",
    "km_rem",
    "km_busi",
    "km_ban",
    "jumlah_keluhan",
    "riwayat_kecelakaan",
}

STATUS_KEY = {
    "Layak": "layak",
    "Perlu Servis": "perlu_servis",
}

MODEL_RESPONSE_KEY = {
    "Naive Bayes": "naivebayes",
    "Decision Tree": "decision_tree",
}


app = Flask(__name__)
model_bundle = joblib.load(MODEL_PATH)
models = model_bundle.get("models")
if models is None:
    models = {model_bundle.get("model_name", "model"): model_bundle["model"]}
label_encoder = model_bundle["label_encoder"]
model_feature_columns = model_bundle.get("feature_columns", FEATURE_COLUMNS)
model_metrics = {
    item["model"]: item
    for item in model_bundle.get("metrics", [])
}


def buat_rekomendasi_servis(data):
    rekomendasi = []

    for kolom_km, komponen in {
        "km_oli": "oli",
        "km_rem": "rem",
        "km_busi": "busi",
        "km_ban": "ban",
    }.items():
        if float(data[kolom_km]) <= 10000:
            rekomendasi.append(komponen)

    if data.get("kondisi_rem") == "Aus" and "rem" not in rekomendasi:
        rekomendasi.append("rem")
    if data.get("kondisi_ban") == "Aus" and "ban" not in rekomendasi:
        rekomendasi.append("ban")
    if data.get("kondisi_aki") == "Lemah":
        rekomendasi.append("aki")

    return rekomendasi


def buat_ringkasan_metrik(nama_model):
    metrik = model_metrics.get(nama_model, {})
    return {
        "akurasi": round(float(metrik.get("akurasi", 0)), 4),
        "precision": round(float(metrik.get("precision_macro", 0)), 4),
        "recall": round(float(metrik.get("recall_macro", 0)), 4),
        "f1_score": round(float(metrik.get("f1_score_macro", 0)), 4),
    }


def validasi_payload(payload):
    if not isinstance(payload, dict):
        return None, "Body request harus berupa JSON object."

    missing_fields = [kolom for kolom in FEATURE_COLUMNS if kolom not in payload]
    if missing_fields:
        return None, f"Field wajib belum lengkap: {', '.join(missing_fields)}"

    data = {kolom: payload[kolom] for kolom in FEATURE_COLUMNS}
    for kolom in NUMERIC_COLUMNS:
        try:
            data[kolom] = float(data[kolom])
        except (TypeError, ValueError):
            return None, f"Field {kolom} harus berupa angka."

    return data, None


@app.get("/")
def index():
    return jsonify(
        {
            "message": "API prediksi kendaraan aktif",
            "endpoint": "/prediksi-kendaraan",
            "method": "POST",
        }
    )


@app.post("/prediksi-kendaraan")
def prediksi_kendaraan():
    payload = request.get_json(silent=True)
    data, error = validasi_payload(payload)

    if error:
        return jsonify({"error": error}), 400

    kelas = label_encoder.classes_
    input_df = pd.DataFrame([{kolom: data[kolom] for kolom in model_feature_columns}])
    rekomendasi_servis = buat_rekomendasi_servis(data)

    hasil = {}
    for nama_model, model_pipeline in models.items():
        probabilitas = model_pipeline.predict_proba(input_df)[0]
        probabilitas_kelas = {
            STATUS_KEY[label]: round(float(nilai), 4)
            for label, nilai in zip(kelas, probabilitas)
        }
        prediksi_index = int(probabilitas.argmax())
        prediksi_label = kelas[prediksi_index]
        response_key = MODEL_RESPONSE_KEY.get(nama_model, nama_model.lower().replace(" ", "_"))

        hasil[response_key] = {
            "akurasi_kelas": probabilitas_kelas,
            "status": STATUS_KEY[prediksi_label],
            "rekomendasi_servis": rekomendasi_servis,
            "evaluasi_model": buat_ringkasan_metrik(nama_model),
        }

    return jsonify(hasil)


if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5000, debug=False)
