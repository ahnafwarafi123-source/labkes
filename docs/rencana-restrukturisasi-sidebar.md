# Rencana Restrukturisasi dan Otorisasi Menu Sidebar Berbasis Role

Dokumen ini memuat perencanaan teknis dan arsitektur restrukturisasi menu sidebar pada sistem informasi laboratorium UPTD Labkes (modul **Kesmas** dan **Klinik & Rekam Medis**).

---

## 1. Latar Belakang & Tujuan

### Masalah Saat Ini (As-Is)
1. **Belum Ada Filter Role di Sidebar**: File [`application/views/klinik/layout/sidebar.php`](file:///c:/laragon/www/lebkes-production/application/views/klinik/layout/sidebar.php) saat ini menampilkan seluruh menu kepada semua pengguna yang login tanpa memandang modul maupun role.
2. **Kategori Menu Tercampur**: Menu modul Kesmas (*Layanan Kesmas*) saat ini berada di bawah header *Klinik dan Rekam Medis*.
3. **Menu Pengaturan & Sistem Tersebar**:
   - Di modul Klinik, menu akun user, log login, dan manajemen petugas terpisah.
   - Di modul Kesmas, menu *Tim Internal* berada di bagian paling bawah, sedangkan *Security Dashboard* berada di bawah *Survei*.
4. **Menu Baru Belum Terpetakan Berdasarkan Role**: Fitur-fitur baru (Kwitansi Kesmas, Kwitansi Klinik, Kwitansi RM, dan Wilayah Puskesmas Kesmas) perlu ditempatkan di grup yang tepat sesuai wewenang role.

### Tujuan Restrukturisasi (To-Be)
1. **Otorisasi Tampilan Berbasis Role**: Setiap pengguna hanya melihat menu yang relevan dengan tugas dan hak aksesnya.
2. **Pemisahan Modul Bersih**: Pengguna Kesmas hanya melihat modul Kesmas; Pengguna Klinik hanya melihat modul Klinik & RM.
3. **Penyederhanaan Struktur 3 Blok**: Menyeragamkan hierarki menu menjadi 3 blok logis:
   - **Blok 1: Menu Utama** (Dashboard)
   - **Blok 2: Pelayanan / Operasional** (Kerja harian laboratorium & rekam medis)
   - **Blok 3: Sistem & Pengaturan** (Manajemen staf, akun, keamanan, dan log)
4. **Zero-Regression**: Tidak mengubah controller, route, helper, maupun database; otorisasi tampilan diatur murni pada view sidebar.

---

## 2. Identifikasi Pengguna, Modul, dan Role

Sistem menggunakan fungsi helper terpadu [`current_user()`](file:///c:/laragon/www/lebkes-production/application/helpers/auth_helper.php#L172-L192) yang mengembalikan informasi:
- `$module` (`'kesmas'` atau `'klinik'`)
- `$role` (nama role yang dinormalisasi menjadi huruf kecil)

### Rincian Role per Modul:
1. **Modul Kesmas** (Tabel `user_kesmas`):
   - `admin`
   - `petugas`
2. **Modul Klinik & Rekam Medis** (Tabel `users`, kolom `klinik_role`):
   - `admin`
   - `petugas pendaftaran`
   - `pemeriksa sampel`
   - `petugas rm` (Dokter Pemeriksa / Petugas Rekam Medis)

---

## 3. Struktur Hirarki Menu Baru (Pola 3 Blok)

### A. Modul Klinik & Rekam Medis (`$module === 'klinik'`)

```text
├── MENU UTAMA
│   └── Dashboard                                   → (Semua Role Klinik)
│
├── PELAYANAN MEDIS
│   ├── Layanan Laboratorium (Dropdown)             → (Admin, Petugas Pendaftaran, Pemeriksa Sampel)
│   │   ├── Form Pendaftaran Klinik                 → (Admin, Petugas Pendaftaran)
│   │   ├── Uji Lab Klinik                          → (Admin, Pemeriksa Sampel)
│   │   ├── Laporan Uji Lab                         → (Admin, Petugas Pendaftaran, Pemeriksa Sampel)
│   │   └── Kwitansi Klinik [Baru]                  → (Admin, Petugas Pendaftaran, Pemeriksa Sampel)
│   │
│   ├── Rekam Medis Pasien (Dropdown)               → (Admin, Petugas Pendaftaran, Petugas RM)
│   │   ├── Pendaftaran Pasien RM                   → (Admin, Petugas Pendaftaran, Petugas RM)
│   │   ├── Kunjungan & SOAP                        → (Admin, Petugas Pendaftaran, Petugas RM)
│   │   ├── Detail Rekam Klinik                     → (Admin, Petugas RM)
│   │   └── Kwitansi RM [Baru]                      → (Admin, Petugas Pendaftaran, Petugas RM)
│   │
│   └── Laporan & Wilayah (Dropdown / Single Link)  → (Admin, Petugas RM)
│       ├── Form Laporan Akhir                      → (Admin, Petugas RM)
│       └── Manajemen Lokasi (Puskesmas Wilayah)    → (Admin, Petugas RM)
│
└── SISTEM & PENGATURAN                             → (Khusus Admin Klinik)
    ├── Master Staf Petugas (Dropdown)
    │   ├── Petugas Klinik / Sampel
    │   ├── Petugas Verifikasi
    │   ├── Petugas Validasi
    │   ├── Petugas Verifikator Hasil
    │   ├── Petugas Penanggung Jawab
    │   └── Dokter Pemeriksa Rekam Medis
    ├── Manajemen Akun Pengguna
    └── Log Login Pengguna
```

---

### B. Modul Kesmas (`$module === 'kesmas'`)

```text
├── MENU UTAMA
│   └── Dashboard                                   → (Admin & Petugas)
│
├── OPERASIONAL KESMAS
│   ├── Layanan Sampel (Dropdown)                   → (Admin & Petugas)
│   │   ├── Pendaftaran
│   │   ├── Terima Sampel
│   │   ├── Input Hasil
│   │   ├── Laporan Hasil Uji
│   │   ├── Laporan Parameter
│   │   ├── Laporan Pelanggan
│   │   └── Kwitansi Kesmas [Baru]
│   │
│   └── Fasilitas & Evaluasi (Dropdown)             → (Admin & Petugas)
│       ├── Master Parameter Lab
│       ├── Wilayah Puskesmas [Baru]
│       └── Survei Kepuasan
│
└── SISTEM & MANAJEMEN                              → (Admin & Petugas)
    ├── Data Petugas Internal (Dropdown)            → (Admin & Petugas)
    │   ├── Daftar Petugas
    │   ├── Verifikator
    │   └── Penanggung Jawab Teknis (PJT)
    │
    └── Keamanan & Audit (Security Dashboard)       → (Khusus Admin Kesmas)
```

---

## 4. Matriks Lengkap Hak Akses Menu vs Role

| Modul | Kategori Navigasi | Menu / Sub-Menu | Admin Klinik | Petugas Pendaftaran | Pemeriksa Sampel | Petugas RM | Admin Kesmas | Petugas Kesmas |
| :--- | :--- | :--- | :---: | :---: | :---: | :---: | :---: | :---: |
| **Umum** | Menu Utama | Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Klinik** | Pelayanan Medis | Form Pendaftaran Klinik | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| | | Uji Lab Klinik | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| | | Laporan Uji Lab | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| | | Kwitansi Klinik *(Baru)* | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| | Rekam Medis | Pendaftaran Pasien RM | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| | | Detail Kunjungan SOAP | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| | | Detail Rekam Klinik | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| | | Kwitansi RM *(Baru)* | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| | Laporan Akhir | Form Laporan Akhir | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| | | Manajemen Lokasi | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| | Sistem | Manajemen Akun User | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| | | Log Login User | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| | | Master Petugas (6 Sub-Menu) | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Kesmas** | Layanan Kesmas | Pendaftaran | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Terima Sample | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Input Hasil | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Laporan Hasil Uji | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Laporan Parameter | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Laporan Pelanggan | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Kwitansi Kesmas *(Baru)* | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | Fasilitas Lab | Master Parameter Lab | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Wilayah Puskesmas *(Baru)* | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | Evaluasi | Survei Kepuasan | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | Sistem Kesmas | Tim Internal (Petugas, Verif, PJT) | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| | | Security Dashboard | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |

---

## 5. Rencana Teknis Penerapan Kode

### Berkas yang Diubah:
- **HANYA** [`application/views/klinik/layout/sidebar.php`](file:///c:/laragon/www/lebkes-production/application/views/klinik/layout/sidebar.php)

### Berkas yang TIDAK Boleh Diubah:
- Controller (`application/controllers/`): Otorisasi backend sudah sesuai dengan `require_role()`.
- Routes (`application/config/routes.php`): Seluruh URL `site_url(...)` tetap mengarah ke rute yang sudah terdefinisi.
- Helper (`application/helpers/auth_helper.php`): Logika otentikasi sesi sudah lengkap.
- Skrip CSS & JS di sidebar: Fungsi pencarian `searchSidebar(keyword)` dan CSS scrollbar tetap dipertahankan.

### Blueprint Logika PHP di Atas Berkas:
```php
<?php
$user   = current_user();
$module = $user['module'] ?? '';
$role   = strtolower(trim((string)($user['role'] ?? '')));

// Flag Role Modul Klinik
$is_klinik_admin       = ($module === 'klinik' && $role === 'admin');
$is_klinik_pendaftaran = ($module === 'klinik' && $role === 'petugas pendaftaran');
$is_klinik_sampel      = ($module === 'klinik' && $role === 'pemeriksa sampel');
$is_klinik_rm          = ($module === 'klinik' && $role === 'petugas rm');
$is_klinik             = ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_sampel || $is_klinik_rm);

// Flag Role Modul Kesmas
$is_kesmas_admin   = ($module === 'kesmas' && $role === 'admin');
$is_kesmas_petugas = ($module === 'kesmas' && $role === 'petugas');
$is_kesmas         = ($is_kesmas_admin || $is_kesmas_petugas);
?>
```

---

## 6. Tahapan Eksekusi ke Depan (Action Checklist)

1. **Tahap 1: Pengkondisian Header & Menu Kesmas**
   - Bungkus seluruh grup menu Kesmas dengan `<?php if ($is_kesmas): ?>`.
   - Bungkus Security Dashboard dengan `<?php if ($is_kesmas_admin): ?>`.
2. **Tahap 2: Pengkondisian Header & Menu Klinik**
   - Bungkus seluruh grup menu Klinik dengan `<?php if ($is_klinik): ?>`.
   - Rapikan dropdown *Klinik*, *Rekam Medis*, dan *Laporan Akhir*.
3. **Tahap 3: Restrukturisasi Blok Sistem & Pengaturan**
   - Gabungkan menu akun, log, dan petugas klinik ke dalam blok *Sistem & Pengaturan* dengan proteksi `<?php if ($is_klinik_admin): ?>`.
4. **Tahap 4: Verifikasi & Uji Kualitas**
   - Cek sintaks PHP menggunakan `php -l application/views/klinik/layout/sidebar.php`.
   - Uji coba login dengan akun dari masing-masing role (Admin, Petugas Pendaftaran, Pemeriksa Sampel, Petugas RM, Admin Kesmas, Petugas Kesmas).
