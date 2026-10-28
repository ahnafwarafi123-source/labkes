# Changelog

Perubahan proyek lebkes-backup, versi terbaru di atas.
Format: Keep a Changelog + CalVer (vYYYY.MM.DD).

> **Catatan:** Ini git repository; versi dan tanggal direkonstruksi
> dari timestamp file dan evidensi kode.
>
> **Cara membaca:** setiap versi punya blok *Ringkasan* (penjelasan sederhana
> untuk umum) lalu detail teknis (Added = tambahan baru, Changed = perubahan,
> Fixed = perbaikan bug, Security = keamanan).

## [Unreleased]

**Ringkasan:** Menu Kwitansi RM di grup Rekam Medis dihapus. Kwitansi kini
hanya untuk Kesmas dan Klinik.

### Changed

- Kelompok tarif kwitansi Kesmas "Air Bersih / Air Sanitasi" → "Air Higiene / Air Sanitasi"
  (data `tarif_pemeriksaan` & `kwitansi_item`, serta pemetaan di `Kwitansi_model::sync_tarif_kesmas`)
- Cetak kwitansi (print & PDF) hanya menampilkan pemeriksaan yang dipilih, dalam satu
  tabel dengan baris TOTAL; ringkasan bukti pembayaran hanya berisi kelompok yang terisi
  (`views/kwitansi/print.php`, `Kwitansi_model::ringkasan_kelompok`)

### Removed

- Data: tarif Kesmas kelompok "benda" (item "kursi", Rp 20.000) beserta itemnya di
  KW/0003, KW/0004, KW/0005/KM/2026 (total kwitansi dihitung ulang) — `lebkes_db`
- Controller `klinik/Kwitansi_rm` (URL `klinik/kwitansi_rm/...` kini 404)
- Menu "Kwitansi RM" di sidebar Rekam Medis
- Cabang modul `rm` di `Kwitansi_model` (`MODUL_KODE`, `get_sumber`,
  `_sumber_query`, `saran_tarif`) dan catatan RM di view `kwitansi/tarif.php`

## [v2026.10.22] - 2026-10-22

**Ringkasan:** Formulir laporan cetak diperbaiki massal (tampilan & penamaan
grup pemeriksaan) di modul Kesmas & Klinik, plus perbaikan auth.

### Changed

- Helper `kesmas_helper`, `report_helper`, `Pdf.php` (penamaan grup & escaping)
- Massal view `permintaan`, `laporan`, `master`, `petugas`, `verifikator`,
  `survei`, `sample`, `uji`, `tindakan_sampel` (Kesmas) dan view Klinik
  (form_permintaan, hasil_uji, login_logs, penanggung_teknis, petugas, puskesmas)
- `Auth.php` (perbaikan)

## [v2026.10.22] - 2026-10-22

**Ringkasan:** Master Wilayah Puskesmas baru: input kecamatan, objek, dan wilayah
pada formulir permintaan dan laporan pelanggan.

### Added

- `Puskesmas_wilayah_kesmas` controller + model + views (`form.php`, `index.php`)
- Input kecamatan, objek, wilayah di `Form_permintaan_kesmas` & `Laporan_pelanggan`

### Changed

- `Kesmas_model`, `laporan_pelanggan/detail|print`, `permintaan/detail|form|print`
- Sidebar klinik (menu Master Wilayah)

## [v2026.10.19] - 2026-10-19

**Ringkasan:** Fitur Kwitansi (bukti pembayaran) beserta tombol cetak untuk
modul Kesmas & Klinik: tandai lunas, nomor KW.YYYYMMDD.NNNN, cetak PDF mPDF.

### Added

- `Kwitansi_kesmas`, `Kwitansi_klinik`, `Kwitansi_rm` controllers
- `Kwitansi_model`, `Kwitansi_flow` library
- Views `kwitansi/{form,index,print,tarif.php}`
- `report_helper` (dukungan kwitansi), `Pdf.php`

### Changed

- `Laporan_uji_kesmas`, sidebar klinik, `laporan/list|print`

## [v2026.10.18] - 2026-10-18

**Ringkasan:** Perbaikan tampilan cetak: garis tabel saat terpotong page break,
alamat kop surat Klinik, prefix `klinik/` pada link laporan rekam medis.

### Fixed

- Garis tabel cetak saat terpotong page break (`laporan_pelanggan/print.php`)
- Alamat kop surat Klinik (`permintaan/print.php`)
- Prefix `klinik/` pada link `laporan_rekam_medis`

## [v2026.10.15] - 2026-10-15

**Ringkasan:** Perbaikan bug model & query Klinik serta hardening views.

### Fixed

- Bug model & query Klinik (`Form_permintaan_klinik`, `Laporan_hasil_pemeriksaan`,
  `Petugas_dokter`, `Uji_rekam_klinik`, `Verifikator`)
- Hardening views Klinik

### Changed

- Sidebar klinik

## [v2026.10.12] - 2026-10-12

**Ringkasan:** Halaman pendaftaran (baik petugas maupun formulir publik)
dirapikan: daftar jenis pemeriksaan kini dikelompokkan per kategori
(mis. Air Minum, Lingkungan) sehingga mudah dibaca, bukan daftar panjang.

### Changed

- Form permintaan internal (`views/kesmas/permintaan/form.php`) dan publik
  (`views/kesmas/public/permintaan_form.php`): master pemeriksaan
  dikelompokkan per kategori dengan judul prefix kelompok
- `views/kesmas/laporan/print.php`: penamaan grup pemeriksaan saat dicetak
  disamakan dengan halaman detail

### Added

- `database/backup/item_permintaan_p5_20261011_pre_del_alt.sql`
  (cadangan struktur tabel sebelum penataan — seperti "save point" database)

## [v2026.10.11] - 2026-10-11

**Ringkasan:** Menu Master Pemeriksaan jadi lebih lengkap: laboratorium bisa
menyalakan/mematikan, menduplikasi, dan mengatur urutan daftar pemeriksaan
tanpa mengubah kode. Nama grup pemeriksaan juga dirapikan, dan cadangan
database diperbarui.

### Added

- Master Pemeriksaan (`controllers/kesmas/Master_pemeriksaan_kesmas.php`):
  `toggle_active` (aktif/nonaktif), `duplicate` (salin), `reorder` (atur
  urutan), `check_duplicate` (cek nama ganda)
- `report_helper`: `get_kelompok_prefix()`, `normalize_kelompok()`, `e_html()`
  (utility penamaan grup & escaping)

### Changed

- Struktur tabel `kesmas_master_pemeriksaan` (backup pre_rename)
- `Kesmas_model` dan `views/kesmas/public/print_bukti.php` (dukungan grouping)
- `views/kesmas/laporan_pelanggan/print.php`

### Fixed

- `login_logs` INSERT gagal saat `user_agent` NULL (error log 11 Okt:
  "Column 'user_agent' cannot be null")

## [v2026.10.09] - 2026-10-09

**Ringkasan:** Halaman baru "Laporan Pelanggan" — petugas bisa melihat dan
mencetak daftar pelanggan lab. Tampilan beberapa halaman lama disesuaikan
supaya data pemeriksaan lebih terkelompok.

### Added

- Laporan Pelanggan (`controllers/kesmas/Laporan_pelanggan.php` + list & detail)
- `kesmas_helper`: `get_group_headers()` (header kelompok per kategori sampel)

### Changed

- View `permintaan/detail`, `uji/input_hasil`, `sample/verifikasi_form`,
  `laporan/detail`

## [v2026.10.05] - 2026-10-05

**Ringkasan:** Proyek mulai didokumentasikan secara tertulis (AGENTS.md +
hasil analisis kode lama), supaya developer baru paham struktur aplikasi tanpa
harus menebak-nebak. Aturan login/sesi juga dirapikan.

### Added

- Dokumentasi reverse-engineering `.agents/` (`2-TECH-SPEC.md`,
  `3-TASKS.md` T001–T018, `4-LEGACY-DECODER.md`)
- `AGENTS.md` (koreksi fakta: layout render, login terpadu, write-gate, dll.)

### Changed

- `auth_helper.php` (validasi sesi & fallback)

## [v2026.09.24] - 2026-09-24

**Ringkasan:** Dua aplikasi (Kesmas & Klinik) kini tampil sebagai SATU
portal: satu sidebar/menu untuk semua menu, dibedakan hanya berdasarkan
siapa yang login. Halaman lama yang masih terpisah disamakan.

### Added

- Layout terpadu: `MY_Controller::render()` memakai header+sidebar+footer
  klinik (`core/MY_Controller.php:85-89`) untuk semua halaman internal
  kedua modul
- Sidebar gabungan `views/klinik/layout/sidebar.php` (group "Layanan Kesmas" +
  "Klinik" dalam satu menu, dibedakan session `module`)

### Changed

- Penyesuaian massal controller `kesmas/*` dan `klinik/*` (cross-module
  write-gate: user Klinik tidak boleh mengubah data Kesmas, dan sebaliknya)

## [v2026.09.23] - 2026-09-23

**Ringkasan:** Dashboard beranda digabung — satu halaman beranda yang
menampilkan data sesuai peran pengguna. Menu admin kesmas (Survei,
Keamanan/Log, User) ikut dirapikan.

### Added

- `Home.php` dashboard gabungan per role, `views/home/dashboard.php`
- Modul admin: Survei, Security (`login_logs`), User (kesmas)

## [v2026.09.22] - 2026-09-22

**Ringkasan:** SATU PORTAL LOGIN: dulu Kesmas dan Klinik punya halaman login
terpisah, sekarang satu halaman login untuk semua — sistem otomatis
mengenali pengguna termasuk aplikasi mana. Alamat login lama tetap
berfungsi (dialihkan otomatis).

### Added

- Auth terpadu: `controllers/Auth.php` — satu form untuk dua tabel user
  (`users` klinik + `user_kesmas` kesmas), session `module`, `active_sessions`
- Forward URL login lama ke auth terpadu (`config/routes.php:22-36`:
  `kesmas/petugaslogin`, `klinik/petugas_klinik`, dll.)

### Changed

- `config.php` (CSRF/exclude disesuaikan auth terpadu)

## [v2026.09.16] - 2026-09-16

**Ringkasan:** Formulir pendaftaran online untuk pelanggan (tanpa login)
diperbaiki/disesuaikan.

### Changed

- `Public_pendaftaran` (pendaftaran publik kesmas)

## [v2026.09.10] - 2026-09-10

**Ringkasan:** Titik awal: seluruh aplikasi lab (modul Kesmas + Klinik),
formulir cetak, utilitas, dan database dicatat/backup sebagai baseline —
acuan "kondisi awal" sebelum pengembangan berikutnya.

### Added

- Baseline snapshot proyek: aplikasi CI3 (modul kesmas + klinik), PWA
  (`assets/sw.js`, `manifest.json` — bisa dipasang di HP seperti aplikasi),
  `cetak_laporan.php`, `tools/*`, `vendor/`, `arsitektur.txt` (kini stale),
  dump database awal
