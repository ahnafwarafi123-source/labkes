# CURRENT TASK: Fitur Kwitansi + Cetak Kwitansi

Status: **RENCANA — belum diimplementasi**
Tanggal rencana: 12 Okt 2026

## Task

Fitur Kwitansi + Cetak Kwitansi (modul Kesmas & Klinik)
Terdapat Bug pengisian form pendaftaran saat data belum terisi semua yang sudah di isi langsung reset kosong semua

## Objective

Pembayaran yang sudah lunas bisa ditandai lunas → sistem menerbitkan no.
kwitansi `KW.YYYYMMDD.NNNN` → kwitansi bisa dicetak sebagai PDF (mPDF).

## Task Type

Feature

## Temuan Riset (dasar keputusan)

- Fitur kwitansi **belum ada**; modul pembayaran lama pernah dihapus
  (`controllers/kesmas/Form_permintaan_kesmas.php:208` — komentar
  "Pembayaran module removed").
- **Klinik** — tabel `pembayaran` (`database/lebkes_dp.sql:3483`) sudah punya
  `status` enum ('Belum Lunas'/'Lunas') + `tanggal_pelunasan`, tapi **belum
  pernah diisi** (selalu 'Belum Lunas' hardcode,
  `controllers/klinik/Form_permintaan_klinik.php:282`).
- **Kesmas** — tabel `kesmas_pembayaran` (`database/lebkes_dp.sql:2650`)
  sudah punya `tgl_pembayaran` yang selalu `NULL`; dipakai sebagai penanda
  lunas + tanggal kwitansi.
- **Rekam medis**: tidak ada tabel/kolom pembayaran; pendaftaran RM =
  pendaftaran klinik (`Form_permintaan_rm` tanpa data bayar) → tidak perlu
  fitur tambahan, kwitansi Klinik sudah mencakup RM.
- Pola cetak PDF mPDF: `controllers/klinik/Form_permintaan_klinik.php:708-728`
  + view standalone `views/klinik/form_permintaan_klinik/print.php`.
- Pola auto-number: `generate_no_register()`
  (`controllers/klinik/Form_permintaan_klinik.php:536-561`).
- Sidebar aktif: `views/klinik/layout/sidebar.php` (dipakai `MY_Controller::render()`,
  `core/MY_Controller.php:85-89`).
- **DB aktif = `lebkes_db`** (default `config/database.php`); dump
  `database/kesmas_new.sql` basi — jangan dipakai.

## Desired Behavior

1. Dari halaman detail, petugas klik "Tandai Lunas" (AJAX POST) → server
   generate `no_kwitansi` = `KW.Ymd.` + urutan harian, isi kolom lunas.
   Idempoten: jika sudah lunas, tolak/tampilkan no. yang sudah ada.
2. Tombol "Cetak Kwitansi" muncul hanya jika lunas →
   `$this->pdf->generate_view('.../kwitansi/print', $data,
   'Kwitansi_XXX.pdf', 'I', true)` (auto-print).
3. Template PDF: kop, No. kwitansi, tanggal, "Sudah terima dari", identitas
   pelanggan/pasien, jumlah, metode bayar, tanda tangan.

## Scope

### In Scope

- SQL ALTER: tambah `no_kwitansi VARCHAR(50) NULL` di `pembayaran` dan
  `kesmas_pembayaran` (kolom lain direuse: `status`/`tanggal_pelunasan`
  klinik, `tgl_pembayaran` kesmas).
- Controller + model + view (list, print) kwitansi untuk kedua modul.
- Tombol Tandai Lunas / Cetak Kwitansi di halaman detail kedua modul.
- Menu sidebar kwitansi (group Layanan Kesmas + group Klinik).

### Out of Scope

- Halaman list global gabungan (per-modul list cukup).
- Terbilang otomatis (angka saja dulu).
- Pembayaran otomatis / kasir paralel.
- Perbaikan bug lama yang ditemukan (view `print_pendaftaran`/`print_kunjungan`
  hilang, `delete()` klinik pakai `$no_rm` undefined).
- Mengubah alur simpan pembayaran yang sudah ada.
- Commit (dilarang sampai diminta).

## Database Impact

```sql
ALTER TABLE pembayaran ADD no_kwitansi VARCHAR(50) NULL AFTER status;
ALTER TABLE kesmas_pembayaran ADD no_kwitansi VARCHAR(50) NULL AFTER tgl_pembayaran;
-- disarankan: UNIQUE index no_kwitansi (mencegah nomor dobel)
```

Simpan sebagai file SQL baru di `database/` (mis. `database/kwitansi.sql`),
jalankan manual ke DB aktif `lebkes_db`.

| Modul  | Status lunas                       | Tanggal kwitansi      | No. kwitansi |
|--------|------------------------------------|-----------------------|--------------|
| Klinik | reuse `status` = 'Lunas'           | reuse `tanggal_pelunasan` | **baru** `no_kwitansi` |
| Kesmas | derive: `tgl_pembayaran IS NOT NULL` | reuse `tgl_pembayaran` | **baru** `no_kwitansi` |

## Interface Impact

- Route: **tanpa route eksplisit baru** — cukup catch-all
  `k-panel/(.+)` → `kesmas/$1` (config/routes.php:139) untuk kesmas;
  klinik memakai `site_url('klinik/...')` mengikuti pola sidebar.
- Endpoint AJAX baru: `tandai_lunas` (POST + CSRF token `kesmasnew_token`,
  cek `$this->input->is_ajax_request()`).

## Security Impact

- Semua controller baru extend `MY_Controller` (login + cross-module
  write-gate otomatis).
- `tandai_lunas` = POST AJAX dengan CSRF; no_kwitansi digenerate server-side,
  tidak dari input user.

## File yang Diubah (implementasi)

**Klinik:**

- `controllers/klinik/Kwitansi_klinik.php` (baru) — `index`, `tandai_lunas`, `cetak`
- `models/Klinik/Kwitansi_klinik_model.php` (baru)
- `views/klinik/kwitansi/index.php` + `views/klinik/kwitansi/print.php` (baru)
- `views/klinik/layout/sidebar.php` — menu "Kwitansi" group Klinik
- `views/klinik/form_permintaan_klinik/detail.php` — tombol lunas/cetak

**Kesmas:**

- `controllers/kesmas/Kwitansi_kesmas.php` (baru)
- `models/kesmas/Kwitansi_kesmas_model.php` (baru) — read via join yang ada
  (`Kesmas_model::_build_permintaan_query`, models/kesmas/Kesmas_model.php:50-66)
- `views/kesmas/kwitansi/index.php` + `views/kesmas/kwitansi/print.php` (baru)
- `views/klinik/layout/sidebar.php` — menu "Kwitansi" group Layanan Kesmas
- `views/kesmas/permintaan/detail.php` — tombol lunas/cetak

**Database:** `database/kwitansi.sql` (baru).

**Tidak diubah:** alur store/update pembayaran existing, `Form_permintaan_rm`,
`cetak_laporan.php`, helper, auth.

## Implementation Sequence

1. SQL ALTER + jalankan ke `lebkes_db`
2. Modul Klinik: model → controller → view print → view index → tombol detail → sidebar
3. Modul Kesmas: polos sama
4. Verifikasi

## Verification Plan

1. `php -l` semua file baru/berubah.
2. Klinik: pendaftaran → detail → Tandai Lunas → cek `no_kwitansi` terisi +
   status 'Lunas' → Cetak Kwitansi → PDF terbit & auto-print.
3. Kesmas: sama, cek `tgl_pembayaran` + `no_kwitansi` terisi.
4. Tandai lunas dua kali → ditolak/idempoten.
5. Cetak ulang → nomor sama, tidak dobel.
6. Regresi: form pendaftaran klinik & kesmas tetap jalan; PDF formulir tetap terbit.

## Risks

| Risiko | Mitigasi |
|---|---|
| Nomor dobel saat transaksi paralel (pola LIKE) | Cek duplikat sebelum update + UNIQUE index `no_kwitansi` |
| Data lama kesmas: `tgl_pembayaran` kosong semua | List default hanya yang lunas; tombol tandai lunas dari detail |
| Update pembayaran existing tidak menyentuh `no_kwitansi` | Kolom baru tidak disentuh alur update lama — aman |

## Open Questions

- Apakah akses Tandai Lunas perlu dibatasi role tertentu? (default: semua
  role yang bisa akses halaman detail)
- Perlu "terbilang" otomatis / logo kop khusus di template? (default: tidak)

## Completion Criteria

- Pembayaran lunas di kedua modul punya `no_kwitansi` unik format
  `KW.YYYYMMDD.NNNN`.
- Kwitansi PDF terbit benar di kedua modul.
- Alur lama tidak berubah; `php -l` bersih; verifikasi manual lulus.
- Tanpa commit kecuali diminta user.
