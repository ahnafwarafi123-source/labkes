# LEBKES — Sistem Informasi Laboratorium Kesehatan

Aplikasi web pengelolaan laboratorium kesehatan untuk dua unit kerja:
**Kesmas** (kesehatan masyarakat: pendaftaran sampel, pengujian, laporan)
dan **Klinik** (rekam medis, permintaan pemeriksaan, laporan klinik).
Dibangun dengan **CodeIgniter 3 + MySQL + Bootstrap 5 + jQuery AJAX + mPDF**.

- Remote GitHub: `https://github.com/agvaprtma/lebkes-production.git` (branch `main`)
- Aturan detail pengembangan: [AGENTS.md](AGENTS.md)
- Riwayat perubahan: [CHANGELOG.md](CHANGELOG.md)

## Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi Lokal](#instalasi-lokal)
- [Struktur Folder](#struktur-folder)
- [Cara Mengakses](#cara-mengakses)
- [Konvensi Pengembangan](#konvensi-pengembangan)
- [Alur Git & GitHub](#alur-git--github)
- [Troubleshooting](#troubleshooting)

## Tentang Aplikasi

| Modul  | Fungsi utama                                              | Akses                  |
|--------|-----------------------------------------------------------|------------------------|
| Kesmas | Pendaftaran sampel, permintaan uji, hasil uji, laporan    | `/k-panel/...` (admin), `/kesmas/...` (publik) |
| Klinik | Rekam medis, permintaan pemeriksaan klinik, laporan uji   | `/klinik/...`          |

Halaman admin (Kesmas & Klinik) hanya bisa dibuka setelah login. Halaman publik
Kesmas (pendaftaran & survei) terbuka tanpa login.

## Persyaratan Sistem

| Komponen   | Versi                        | Keterangan                          |
|------------|------------------------------|-------------------------------------|
| PHP        | 7.4+ (dikembangkan di 8.2)   | `composer` **tidak** dipakai di repo ini |
| MySQL      | 5.7+ / MariaDB 10.3+         |                                     |
| Web server | Apache (Laragon / XAMPP)     | mod `rewrite` aktif                 |

`vendor/` sudah di-commit apa adanya — **jangan** menjalankan `composer install`.

## Instalasi Lokal

1. Salin/clone repo ke folder web, misal `C:\laragon\www\lebkes-backup`.
2. Buat database, lalu import tiga file **schema-only** berikut ke database yang sama (jangan import dump berisi data yang lain):

   ```bash
   mysql -u root -p lebkes_db < database/lebkes_schema.sql
   mysql -u root -p lebkes_db < database/security_features.sql
   mysql -u root -p lebkes_db < database/security_sessions.sql
   ```

3. Set env vars bila berbeda dari default (default: `lebkes_db` @ `127.0.0.1`, user `root`, password kosong):

   | Env var       | Default    | Fungsi                          |
   |---------------|------------|---------------------------------|
   | `DB_HOST`     | `127.0.0.1`| Host MySQL                      |
   | `DB_USER`     | `root`     | Username                        |
   | `DB_PASS`     | *(sesuai lingkungan)* | Password                        |
   | `DB_NAME`     | `lebkes_db`| Nama database                   |
   | `CI_DB_GROUP` | `default`  | Grup koneksi di `database.php`  |

4. Buka `http://localhost/lebkes-backup/` — root URL redirect ke halaman publik
   Kesmas (`/kesmas`); login petugas ada di `/login`.

## Struktur Folder

```
application/
  controllers/
    kesmas/        # modul Kesmas (Dashboard, Form_permintaan_kesmas, Survei, ...)
    klinik/        # modul Klinik
    Auth.php       # login unified (users + user_kesmas)
  models/
    kesmas/  Klinik/   # perhatikan: folder Klinik ber-HURUF BESAR
  views/
    kesmas/  klinik/   # view per modul; layout admin di klinik/layout/
  core/MY_Controller.php
database/          # schema & security SQL (dump berisi data di-.gitignore)
tools/             # util CLI (duplicate_database, backfill_baku_mutu)
assets/            # CSS/JS, sw.js & manifest.json (PWA offline)
cetak_laporan.php  # script laporan offline standalone (PDO)
vendor/            # library pihak ketiga (mPDF dll), jangan diubah manual
```

## Cara Mengakses

| URL                          | Fungsi                                    |
|------------------------------|-------------------------------------------|
| `/login`                     | Login petugas (Kesmas & Klinik)           |
| `/dashboard`                 | Dashboard terpadu (setelah login)         |
| `/k-panel/...`               | Admin Kesmas — **semua** rute admin Kesmas |
| `/kesmas/pendaftaran_publik` | Form pendaftaran publik (tanpa login)     |
| `/kesmas`                    | Halaman publik modul Kesmas (root redirect ke sini) |
| `/klinik/dashboard`          | Dashboard modul Klinik                    |

> Rute admin **selalu** lewat `k-panel/...`. Mengakses `/kesmas/dashboard`
> langsung akan menghasilkan 404 — itu disengaja (blokir akses fisik folder).

## Konvensi Pengembangan

Ringkas — detail lengkap ada di [AGENTS.md](AGENTS.md). Yang paling sering
melakukan kesalahan:

- Query database wajib CI Query Builder (`$this->db->...`), bukan SQL mentah.
- Validasi input pakai `form_validation` + `xss_clean`; escape output view dengan `html_escape()`.
- Layout: pakai flex/grid `div`, **jangan** `<table>` untuk tata letak.
- Bahasa tampilan Indonesia; nama file/class/method tetap Inggris.
- Setiap file PHP baru diawali `defined('BASEPATH') OR exit('No direct script access allowed');`.
- Verifikasi minimal perubahan: `php -l <file>` (tidak ada test suite).
- Public POST baru harus didaftarkan di `$config['csrf_exclude_uris']`.
- Jangan menghapus/mengosongkan `application/cache/` — berisi session aktif.

## Alur Git & GitHub

### Workflow: branch → Pull Request → review → merge

1. Pastikan `main` bersih dan up to date:

   ```bash
   git checkout main
   git pull --rebase origin main
   ```

2. Buat branch kerja dengan prefix:

   | Prefix   | Untuk                                    |
   |----------|------------------------------------------|
   | `feat/`  | fitur baru                               |
   | `fix/`   | perbaikan bug                            |
   | `chore/` | pemeliharaan, konfigurasi, readme        |
   | `docs/`  | perubahan dokumentasi saja               |

   ```bash
   git checkout -b feat/kwitansi-cetak
   ```

3. Commit dengan **Conventional Commits** (sudah dipakai di history repo):

   ```
   <type>(<scope>): <pesan pendek, imperatif, bahasa Inggris>

   feat(klinik): add kwitansi lunas + cetak PDF
   fix(laporan): add klinik/ prefix to laporan_rekam_medis links
   chore: update readme
   ```

   Scope = modul (`kesmas`, `klinik`, `laporan`, `auth`, `security`).

4. Sebelum push, cek tidak ada file sensitif ikut ter-commit:

   ```bash
   git status
   git diff --cached --stat
   ```

   Yang **tidak boleh** masuk repo: dump SQL berisi data, backup produksi,
   session di `application/cache/`, kredensial, data pasien/uji. Perhatikan
   `.gitignore` — hanya 3 file schema yang diizinkan.

5. Push branch dan buka **Pull Request** ke `main`:

   ```bash
   git push -u origin feat/kwitansi-cetak
   ```

6. Minimal **1 review** sebelum merge. Checklist reviewer:
   - `php -l` lolos di semua file yang diubah
   - Tidak ada SQL mentah / query tanpa Query Builder
   - Tidak ada data sensitif di diff
   - Label & pesan tampilan berbahasa Indonesia
   - Perubahan layout tetap responsif (div grid, bukan table)

### Larangan

- **Jangan force-push `main`** — riwayat semua orang harus aman.
- Jangan commit langsung ke `main` kecuali perbaikan typo dokumentasi trivial.
- Jangan commit file di luar scope satu PR (campur fitur + refactor besar).
- Jangan commit `vendor/` hasil `composer` — versi sudah dikunci di repo.

## Troubleshooting

| Gejala | Penyebab & solusi |
|--------|-------------------|
| Keluar sendiri tiap buka halaman | `is_logged_in()` mengecek baris `active_sessions` (session id + IP + user-agent, kedaluwarsa 2 jam). IP berubah (VPN/ganti WiFi) atau DB di-restore → login ulang. |
| Halaman PDF error/fatal | `application/libraries/Pdf.php` butuh `vendor/autoload.php`. Pastikan folder `vendor/` utuh. |
| 404 di halaman admin | Akses lewat `k-panel/...`, bukan `/kesmas/...` (diblokir sengaja). |
| Form publik ditolak "The action you have requested is not allowed" | POST publik baru belum didaftarkan di `csrf_exclude_uris` di `application/config/config.php`. |
| Session semua user hilang | `application/cache/` (folder session) dihapus/dikosongkan — hindari. |

---

Dokumen ini mengikuti kode yang ada; bila berbeda dengan kode, **percayai kode**
(lalu perbaiki dokumentasinya).
