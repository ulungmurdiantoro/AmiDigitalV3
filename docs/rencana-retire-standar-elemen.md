# Rencana Retire Tabel `standar_elemen_*`

> Status: **SELESAI — 2026-06-16.** Ke-21 tabel sudah di-drop, semua kode dialihkan
> ke `standards`/`elements`/`indikators`. Ringkasan eksekusi ada di bagian bawah.

## Tujuan

Memensiunkan 21 tabel lama `standar_elemen_*` dan memindahkan seluruh isinya ke
struktur baru yang sudah ada: **`standards`**, **`elements`**, **`indikators`**
(dibuat 2025-07-29).

## 21 tabel yang akan dihapus

```
standar_elemen_banpt_d3_s              standar_elemen_infokom_d3_s            standar_elemen_lamdik_d3_s
standar_elemen_banpt_s1_s              standar_elemen_infokom_s1_s            standar_elemen_lamdik_s1_s
standar_elemen_banpt_s2_s              standar_elemen_infokom_s2_s            standar_elemen_lamdik_s2_s
standar_elemen_banpt_s3_s              standar_elemen_infokom_s3_s            standar_elemen_lamdik_s3_s
standar_elemen_banpt_terapan_s1_s      standar_elemen_infokom_terapan_s1_s   standar_elemen_lamdik_terapan_s1_s
standar_elemen_banpt_terapan_s2_s      standar_elemen_infokom_terapan_s2_s   standar_elemen_lamdik_terapan_s2_s
standar_elemen_banpt_terapan_s3_s      standar_elemen_infokom_terapan_s3_s   standar_elemen_lamdik_terapan_s3_s
```

> ⚠️ Tabel-tabel ini **punya file migration** dan tercatat di tabel `migrations`.
> Saat ini kosong di DB lokal **hanya karena import Excel belum pernah dijalankan
> di lokal** — bukan karena fiturnya mati. Di produksi kemungkinan berisi.

## Kenapa TIDAK boleh langsung di-drop

Tabel `standar_elemen_*` masih jadi sumber data fitur yang **aktif & terdaftar di route**.
Drop tanpa rewire = halaman fatal error `Class/Table not found`.

| Pemakai (kode) | Cara pakai |
|---|---|
| `app/Http/Controllers/Admin/NewKriteriaDokumenController.php` | `storeImport()` (import Excel menulis ke tabel), `kelolaTargetEdit()` baca `StandarElemenBanptS1` |
| `app/Http/Controllers/User/InputAmiUserController.php` | `index()` ambil kriteria 1–12 dari `StandarElemenBanptS1` |
| `app/Http/Controllers/Auditor/InputAmiAuditorController.php` | sama dengan versi user |
| `app/Http/Controllers/Admin/StatistikElemenController.php`, `StatistikTotalController.php` | mapping `modelClass => StandarElemen*::class` |
| `app/Http/Controllers/User/UserStatistikElemenController.php`, `UserStatistikTotalController.php` | sama |
| `app/Http/Controllers/Admin/NilaiEvaluasiDiriController.php`, `User/UserNilaiEvaluasiDiriController.php` | mapping `modelClass => StandarElemen*::class` |
| `app/Models/StandarNilai.php` | relasi `belongsTo` → `StandarElemenBanptS1` / `StandarElemenLamdikS1` / `StandarElemenLamdikS2` |

Class Import terkait (dipakai `NewKriteriaDokumenController::storeImport`):
`StandarBanptD3Import`, `StandarBanptS1Import`, `StandarBanptS2Import`,
`StandarLamdikPPGImport`, `StandarLamdikS1Import`, `StandarLamdikS2Import`.

## Urutan eksekusi yang disepakati ("tunda drop, rewire dulu")

1. **[Owner]** Selesaikan pindah data `standar_elemen_*` → `standards` / `elements` / `indikators`.
2. **[Owner]** Tentukan pemetaan kolom lama → baru, mis.:
   - `standar_nama` → tabel/kolom mana di `standards`?
   - `elemen_nama` → `elements`?
   - `indikator_id`, `indikator_nama`, `indikator_bobot` → `indikators` (apakah `indikator_id` tetap?)
3. **[Claude]** Alihkan semua pemakai di tabel atas ke tabel baru, lalu tes.
4. **[Claude]** Setelah verifikasi, baru:
   - `DROP TABLE` 21 tabel `standar_elemen_*`
   - hapus 21 file `database/migrations/2024_09_17_*_create_standar_elemen_*` + baris di tabel `migrations`
   - hapus 21 Model `app/Models/StandarElemen*.php`
   - hapus 6 class Import `app/Imports/Standar*Import.php`

## Catatan tambahan

- `app/Http/Controllers/Admin/KriteriaDokumenController.php` dan
  `app/Http/Controllers/User/PemenuhanDokumenController.php` **sudah mati**
  (route-nya dikomentari di `routes/web.php`, diganti versi `New...`). Keduanya
  bisa dihapus kapan saja, terpisah dari rencana ini.
- Tabel scratch `indicator` (bukan dari migration) sudah dihapus 2026-06-16.
