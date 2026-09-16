# Use Case Diagram Sistem Pengelolaan Kendaraan UD Sentosa

## Aktor

- **Admin**: pengguna dengan akses penuh ke seluruh sistem.
- **Pengguna Operasional**: petugas yang mengelola data operasional kendaraan.
- **Kepala Bagian**: pimpinan yang memantau dashboard, hasil klasifikasi, riwayat, dan laporan.

## Diagram Mermaid

```mermaid
flowchart LR
    Admin([Admin])
    Operasional([Pengguna Operasional])
    Kepala([Kepala Bagian])

    subgraph Sistem["Sistem Pengelolaan Kendaraan UD Sentosa"]
        Login((Login))
        Dashboard((Melihat Dashboard))
        Logout((Logout))

        KelolaUser((Kelola Data Pengguna))
        Pengaturan((Kelola Pengaturan Aplikasi))
        Training((Kelola Training Data))

        Kendaraan((Kelola Data Kendaraan))
        Sopir((Kelola Data Sopir))
        Sparepart((Kelola Data Sparepart))
        Pemakai((Kelola Data Pemakai Kendaraan))
        Servis((Kelola Data Servis Kendaraan))
        Penggantian((Kelola Penggantian Sparepart))

        Riwayat((Melihat Riwayat Servis))
        Klasifikasi((Melakukan Klasifikasi Kendaraan))
        HasilKlasifikasi((Melihat Hasil Klasifikasi))
        Perbandingan((Melihat Perbandingan Hasil))
        Laporan((Melihat Laporan))

        UpdateStatusPemakai((Update Status Kendaraan Dipakai/Aktif))
        UpdateStatusServis((Update Status Kendaraan Dalam Servis/Aktif))
        HitungKmBerikutnya((Hitung KM Ganti Berikutnya))
        AmbilDataKendaraan((Ambil Data Uji dari Kendaraan))
        NaiveBayes((Proses Naive Bayes))
        DecisionTree((Proses Decision Tree))
    end

    Admin --> Login
    Operasional --> Login
    Kepala --> Login

    Admin --> Dashboard
    Operasional --> Dashboard
    Kepala --> Dashboard

    Admin --> KelolaUser
    Admin --> Pengaturan
    Admin --> Training

    Admin --> Kendaraan
    Admin --> Sopir
    Admin --> Sparepart
    Admin --> Pemakai
    Admin --> Servis
    Admin --> Penggantian
    Admin --> Klasifikasi

    Operasional --> Kendaraan
    Operasional --> Sopir
    Operasional --> Sparepart
    Operasional --> Pemakai
    Operasional --> Servis
    Operasional --> Penggantian
    Operasional --> Klasifikasi

    Admin --> Riwayat
    Operasional --> Riwayat
    Kepala --> Riwayat

    Admin --> HasilKlasifikasi
    Operasional --> HasilKlasifikasi
    Kepala --> HasilKlasifikasi

    Admin --> Perbandingan
    Operasional --> Perbandingan
    Kepala --> Perbandingan

    Admin --> Laporan
    Operasional --> Laporan
    Kepala --> Laporan

    Admin --> Logout
    Operasional --> Logout
    Kepala --> Logout

    Pemakai -. include .-> UpdateStatusPemakai
    Servis -. include .-> UpdateStatusServis
    Penggantian -. include .-> HitungKmBerikutnya
    Klasifikasi -. include .-> AmbilDataKendaraan
    Klasifikasi -. include .-> NaiveBayes
    Klasifikasi -. include .-> DecisionTree
```

## Diagram PlantUML

```plantuml
@startuml
left to right direction

actor "Admin" as Admin
actor "Pengguna Operasional" as Operasional
actor "Kepala Bagian" as Kepala

rectangle "Sistem Pengelolaan Kendaraan UD Sentosa" {
  usecase "Login" as UC_Login
  usecase "Melihat Dashboard" as UC_Dashboard
  usecase "Logout" as UC_Logout

  usecase "Kelola Data Pengguna" as UC_User
  usecase "Kelola Pengaturan Aplikasi" as UC_Pengaturan
  usecase "Kelola Training Data" as UC_Training

  usecase "Kelola Data Kendaraan" as UC_Kendaraan
  usecase "Kelola Data Sopir" as UC_Sopir
  usecase "Kelola Data Sparepart" as UC_Sparepart
  usecase "Kelola Data Pemakai Kendaraan" as UC_Pemakai
  usecase "Kelola Data Servis Kendaraan" as UC_Servis
  usecase "Kelola Penggantian Sparepart" as UC_Penggantian

  usecase "Melakukan Klasifikasi Kendaraan" as UC_Klasifikasi
  usecase "Melihat Hasil Klasifikasi" as UC_Hasil
  usecase "Melihat Perbandingan Hasil" as UC_Perbandingan
  usecase "Melihat Riwayat Servis" as UC_Riwayat
  usecase "Melihat Laporan" as UC_Laporan

  usecase "Update Status Kendaraan\nDipakai/Aktif" as UC_StatusPemakai
  usecase "Update Status Kendaraan\nDalam Servis/Aktif" as UC_StatusServis
  usecase "Hitung KM Ganti Berikutnya" as UC_KmBerikutnya
  usecase "Ambil Data Uji dari Kendaraan" as UC_DataUji
  usecase "Proses Naive Bayes" as UC_NB
  usecase "Proses Decision Tree" as UC_DT
}

Admin --> UC_Login
Operasional --> UC_Login
Kepala --> UC_Login

Admin --> UC_Dashboard
Operasional --> UC_Dashboard
Kepala --> UC_Dashboard

Admin --> UC_User
Admin --> UC_Pengaturan
Admin --> UC_Training

Admin --> UC_Kendaraan
Admin --> UC_Sopir
Admin --> UC_Sparepart
Admin --> UC_Pemakai
Admin --> UC_Servis
Admin --> UC_Penggantian
Admin --> UC_Klasifikasi

Operasional --> UC_Kendaraan
Operasional --> UC_Sopir
Operasional --> UC_Sparepart
Operasional --> UC_Pemakai
Operasional --> UC_Servis
Operasional --> UC_Penggantian
Operasional --> UC_Klasifikasi

Admin --> UC_Hasil
Operasional --> UC_Hasil
Kepala --> UC_Hasil

Admin --> UC_Perbandingan
Operasional --> UC_Perbandingan
Kepala --> UC_Perbandingan

Admin --> UC_Riwayat
Operasional --> UC_Riwayat
Kepala --> UC_Riwayat

Admin --> UC_Laporan
Operasional --> UC_Laporan
Kepala --> UC_Laporan

Admin --> UC_Logout
Operasional --> UC_Logout
Kepala --> UC_Logout

UC_Pemakai ..> UC_StatusPemakai : <<include>>
UC_Servis ..> UC_StatusServis : <<include>>
UC_Penggantian ..> UC_KmBerikutnya : <<include>>
UC_Klasifikasi ..> UC_DataUji : <<include>>
UC_Klasifikasi ..> UC_NB : <<include>>
UC_Klasifikasi ..> UC_DT : <<include>>

@enduml
```

## Ringkasan Hak Akses

| Aktor | Hak Akses |
| --- | --- |
| Admin | Mengakses seluruh fitur sistem |
| Pengguna Operasional | Mengelola data kendaraan, sopir, sparepart, pemakai, servis, penggantian sparepart, dan melakukan klasifikasi |
| Kepala Bagian | Melihat dashboard, riwayat servis, hasil klasifikasi, perbandingan hasil, dan laporan |
