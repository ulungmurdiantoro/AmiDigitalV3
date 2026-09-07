# Changelog

Semua perubahan penting pada **AMI Digital** (aplikasi Audit Mutu Internal berbasis
Laravel untuk program studi perguruan tinggi) didokumentasikan di berkas ini.

Format mengacu pada [Keep a Changelog](https://keepachangelog.com/id-ID/1.1.0/).
Repositori belum memakai tag rilis, sehingga versi di bawah ditandai dengan
**tanggal** (mengikuti tanggal commit) beserta label milestone-nya.

Instrumen akreditasi yang didukung: **BAN-PT, LAMDIK, LAMEMBA, LAMINFOKOM,
LAMTEKNIK, LAMSAMA**. Peran pengguna: **Admin, Auditor, Prodi (User)**.

---

## [Belum Dirilis]

### Ditambahkan
- Panel **Panduan Penggunaan** yang dapat dibuka/tutup pada dashboard Admin,
  Auditor, dan Prodi — memuat ulang isi panduan peran terkait dan menautkan ke
  halaman Bantuan lengkap (partial `pages.admin.bantuan._panduan-dashboard`).

### Dihapus
- Berkas duplikat `app/Http/Controllers/Auditor/AuditorForcastingController copy.php`.

---

## [2026-06-26] — Terminologi "Perbaikan AMI" & halaman panduan

### Ditambahkan
- Halaman panduan/bantuan baru untuk **Admin**, **Auditor**, dan **Prodi** yang
  menjelaskan peran serta alur kerja masing-masing (`_panduan-admin`,
  `_panduan-auditor`, `_panduan-prodi`).
- Controller `BantuanAuditorController` dan `BantuanUserController`; route Bantuan
  auditor & user dialihkan ke controller baru tersebut.
- Kolom `standar_akreditasi` pada tabel `program_studis` (migration baru).
- `DummyAmiDataSeeder` untuk mengisi data simulasi AMI (penjadwalan, pengajuan,
  penilaian) guna keperluan demo dan pengujian.

### Diubah
- Terminologi **"Koreksi AMI" → "Perbaikan AMI"** pada halaman evaluasi auditor,
  tampilan data koreksi, dan judul breadcrumb.

### Diperbaiki
- `DummyAmiDataSeeder`: format tanggal `opening_ami` menjadi satu tanggal tunggal,
  pemotongan nama indikator maksimal 250 karakter, dan pembaruan referensi
  `INFOKOM` → `LAMINFOKOM`.

---

## [2026-06-25] — Integrasi Feeder/NeoFeeder, kalkulator akreditasi & laporan LKPS

### Ditambahkan
- **Integrasi NeoFeeder / PDDikti**: halaman konfigurasi Feeder
  (`FeederConfigController`), `NeoFeederService` dengan driver `Fake` dan `Real`
  (`NeoFeederDriverInterface`), berkas `config/feeder.php`, serta tabel
  `feeder_configs`, `feeder_mahasiswas`, `feeder_dosens`, `feeder_kelulusans`
  dan kolom `feeder_kode_prodi` pada `program_studis`.
- Artisan command `SyncFeederCommand`, `SyncCapaianFiles`, dan `ImportCapaianUnsika`
  untuk sinkronisasi/import data capaian.
- `AccreditationCalculator` (concern) — mesin kalkulasi skor & peringkat akreditasi
  yang dipakai lintas peran.
- **Laporan LKPS**: halaman laporan dengan tabel ringkasan & rinci untuk DTPS, DTT,
  dan data mahasiswa; `LkpsExport` (export Excel), `LkpsComputeService`, model
  `LkpsSnapshot`, dan tabel `lkps_snapshots`.
- `KriteriaOtherTargetSeeder` — target/ambang nilai untuk LAMDIK, LAMINFOKOM,
  LAMTEKNIK, BAN-PT S1, dan LAMEMBA; ditambah `KriteriaBanptTargetSeeder`,
  `KriteriaLamembaTargetSeeder`, `KriteriaLamembaUnggulSeeder`.
- Komponen hasil forecasting khusus **LAMINFOKOM**, **LAMSAMA**, dan **LAMTEKNIK**.

### Diubah
- Dashboard Admin dan Prodi diperbarui (ringkasan progres AMI & akreditasi).
- Refactor besar `routes/web.php` serta controller `PenjadwalanAmiController`,
  `NilaiEvaluasiDiriController`, `EvaluasiAmiAuditorController`,
  `PengajuanAmiUserController`, dan turunannya.
- Beberapa kolom pada `standar_nilais` dan `standar_capaians` dibuat nullable
  (indikator_id, pertanyaan_nama) melalui migration.

---

## [2026-06-23] — Seeder kriteria berbasis Excel resmi (LAMSAMA, LAMTEKNIK, LAMINFOKOM)

### Ditambahkan
- `KriteriaLaminfokomSeeder` — mem-parse 8 berkas XLSX Matriks Penilaian LAM Infokom
  (D1/D2/D3/S1/S1 Terapan/S2/S2 Terapan/S3) ke hierarki
  `standards` → `elements` → `indikators`, dengan deteksi kolom dinamis.
- `KriteriaLamteknikSeeder` — membaca 22 berkas Excel resmi LAM Teknik
  (12 reguler LED+LKPS + 10 perpanjangan), hierarki 3 tingkat
  Standard (I–VII) → Element (sub-bagian) → Indikator, deteksi kolom skor otomatis.
- `KriteriaLamsamaSeeder` — membaca berkas Excel per-jenjang resmi LAMSAMA
  (S1/D3/M/D/STr): 6 standar + 6 elemen + 24 indikator per jenjang, plus info skor
  (BAIK SEKALI/BAIK/CUKUP/KURANG).
- Dokumen sumber LAMINFOKOM (PDF + XLSX) dan seluruh berkas Excel sumber LAMSAMA &
  LAMTEKNIK di `database/data/`.
- Artisan command `HapusDataLamsamaLamteknik` untuk rollback data seed yang salah.
- Skrip `push-deploy.ps1` (PowerShell) untuk push lokal + deploy server.

### Diubah
- **Reorganisasi `database/data/`** menjadi sub-folder per instrumen: `BAN-PT/`,
  `LAMDIK/`, `LAMSAMA/`, `LAMTEKNIK/`, `LAMINFOKOM/`; semua path seeder disesuaikan.
- **Redesain selektor kriteria-dokumen**: selektor dua baris ringkas (tab akreditasi
  + pill jenjang), warna khas per lembaga, jenjang default S1, dan kolom Elemen
  disembunyikan untuk LAMSAMA.
- Format teks info skor diseragamkan lintas instrumen (label skor pada baris
  tersendiri; pemenggalan baris otomatis untuk sub-item (a)/(b)/1)/2)) pada
  seeder LAMDIK, BAN-PT, dan LAMSAMA.
- Rename instrumen `INFOKOM` → `LAMINFOKOM` di seeder (migrasi otomatis data lama).
- `KriteriaBanptSeeder` disempurnakan (logika import & pemrosesan data).
- Kolom `jenjangs.nama` diperlebar dari VARCHAR(10) menjadi VARCHAR(60).

### Diperbaiki
- 12 entri `bobot` bernilai NULL pada `database/data/LAMDIK/lamdik.json`, diambil
  dari berkas XLSX sumber.
- Parser LAMSAMA/LAMTEKNIK: penanganan baris header berulang dan baris lanjutan
  hasil konversi PDF→Excel, regex bagian tanpa spasi setelah titik.

---

## [2026-06-22] — Perangkat deployment (VPS Debian 13)

### Ditambahkan
- `DEPLOY.md` — panduan setup VPS di Debian 13 dengan Nginx, PHP-FPM, dan MariaDB.
- `deploy.sh` (skrip deploy server) dan `push-deploy.sh` (push lokal + deploy).
- `.deploy.env.example` sebagai contoh konfigurasi deployment.

### Diperbaiki
- `composer.lock` kini di-track (sebelumnya ter-`gitignore`) agar `composer install`
  di produksi tidak gagal karena lock Laravel 9 yang usang; lock diperbarui ke
  Laravel 12.62.
- Skrip deployment: manajemen cache diperjelas, keterbatasan route caching
  didokumentasikan.

### Diubah
- Refactor `KriteriaDokumenController` beserta view-nya untuk penanganan data &
  pengalaman pengguna yang lebih baik.

---

## [2026-06-16 – 2026-06-17] — Refactor struktur standar & inisialisasi database

### Ditambahkan
- Kumpulan seeder untuk inisialisasi database dari nol.

### Diubah
- **Migrasi struktur standar**: 21 tabel lama `standar_elemen_*` dipensiunkan;
  seluruh konsumen dialihkan ke struktur `standards` / `elements` / `indikators`
  (lihat `docs/rencana-retire-standar-elemen.md`).
- Refactor komponen view forecasting untuk halaman Admin dan User (menghapus
  ±1.500 baris duplikasi).
- Pembersihan struktur kode secara umum agar lebih mudah dibaca & dipelihara.

### Dihapus
- Berkas dan route yang sudah usang; view evaluasi & statistik user dirapikan
  (pengurangan bersih ±5.700 baris).
- Tabel scratch `indicator` (non-migration).

---

## [2026-05-05] — Upgrade Laravel 12

### Diubah
- Kerangka kerja dinaikkan dari Laravel 9 ke **Laravel 12** (PHP ^8.2).

---

## [2026-02-09] — Pembaruan

### Diubah
- Pembaruan modul (23 berkas) dan pembersihan pemanggilan `dd()` yang tertinggal.

---

## [2025-12-01 – 2025-12-16] — Mesin forecasting baru

### Ditambahkan
- Mesin **forecasting** hasil akreditasi versi baru untuk halaman Admin dan User.

---

## [2025-11-03] — Finalisasi pra-presentasi

### Diubah
- Penyesuaian besar di 53 berkas untuk persiapan presentasi (tampilan, alur,
  perbaikan data).

---

## [2025-09-29 – 2025-10-10] — Dukungan instrumen LAMEMBA

### Ditambahkan
- Dukungan instrumen akreditasi **LAMEMBA**.
- Modul **pemenuhan dokumen** khusus LAMEMBA beserta alur revisi prodi.

---

## [2025-06-23] — Perbaikan casing folder DataTable

### Diperbaiki
- Penyeragaman penamaan/casing folder `DataTable` (masalah case-sensitivity di
  server Linux).

---

## [2025-04-16 – 2025-04-28] — Penilaian indikator & laporan PDF

### Ditambahkan
- Penanganan indikator yang **belum dinilai** pada proses penilaian.
- Header pada dokumen laporan.

### Diperbaiki
- Error saat generate PDF laporan dengan mPDF.

---

## [2025-02-13 – 2025-03-06] — Siklus PPEPP & instrumen LAMDIK

### Ditambahkan
- Modul **siklus PPEPP** (Penetapan, Pelaksanaan, Evaluasi, Pengendalian,
  Peningkatan).
- Dukungan instrumen akreditasi **LAMDIK** (v1.0, kemudian difinalisasi).

---

## [2025-01-14 – 2025-01-22] — Alur audit auditor

### Ditambahkan
- Alur kerja **audit oleh auditor** (evaluasi AMI, konfirmasi pengajuan).

### Diubah
- Pembaruan besar lintas modul (215 berkas).

---

## [2024-12-02] — Commit awal

### Ditambahkan
- Basis aplikasi **AMI Digital** di atas Laravel 9 (PHP ^8.0): autentikasi
  (Fortify), peran Admin/Auditor/User, modul kriteria dokumen, penjadwalan AMI,
  pemenuhan dokumen, nilai evaluasi diri, statistik elemen/total, dan laporan.
