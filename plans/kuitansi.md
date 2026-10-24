# Rencana Fitur Kuitansi (Klinik + Kesmas)

Ditinjau: 2026-10-15
Status: Rencana (petakan)

## 1. Tujuan
Menambahkan fitur cetak kuitansi per transaksi, dengan tombol di halaman detail, dan opsi menandai pembayaran menjadi Lunas.

## 2. Temuan Verifikasi

| Aspek | Klinik | Kesmas |
|---|---|---|
| Tabel pembayaran | `pembayaran` | `kesmas_pembayaran` |
| Field | `id_pasien`, `no_register`, `total_biaya`, `metode_pembayaran`, `status enum('Belum Lunas','Lunas')`, `tanggal_pelunasan`, `keterangan` | `id_pembayaran`, `no_registrasi`, `tgl_pembayaran`, `biaya`, `metode_pembayaran`, `keterangan` |
| Status | Ada (`status`) | Tidak ada. Harus ditambahkan. |
| PK | Tidak ada PRIMARY KEY (banyak baris `id_pembayaran=0`). | `id_pembayaran` AUTO_INCREMENT. |
| Titik detail | `views/klinik/form_permintaan_klinik/detail.php`, controller `Form_permintaan_klinik` | `views/kesmas/permintaan/detail.php`, controller `Form_permintaan_kesmas` |
| Pola cetak | PDF via `Pdf::generate_view()` + auto-print (method `print($id)`) | HTML via `load->view()` + window.print (method `print($id)`) |

## 3. Rencana Implementasi

### A. Modul Klinik

| File | Perubahan |
|---|---|
| `application/controllers/klinik/Form_permintaan_klinik.php` | Tambah `kuitansi($id)` → baca `get_by_id($id)` + `pembayaran WHERE no_register=? ORDER BY updated_at DESC LIMIT 1`. Load `klinik/form_permintaan_klinik/kuitansi.php` via `Pdf::generate_view(..., autoprint=true)`. |
| `application/controllers/klinik/Form_permintaan_klinik.php` | Tambah `tandai_lunas($id)` → UPDATE `pembayaran SET status='Lunas', tanggal_pelunasan=NOW() WHERE no_register=?` → redirect ke detail. |
| `application/views/klinik/form_permintaan_klinik/kuitansi.php` | Baru. Header mirip `print.php`, isi: No. Kuitansi (pakai `no_register`), Tanggal, Nama Pasien/NIK, Total Biaya (Rp), Metode Pembayaran, Status, opsional Terbilang, TTD. |
| `application/views/klinik/form_permintaan_klinik/detail.php` | Tambah tombol "Cetak Kuitansi". Tambah tombol "Tandai Lunas" jika `status != 'Lunas'`. Tampilkan status pembayaran (baca dari tabel `pembayaran`, fallback dari form). |

Catatan: Karena `pembayaran` tanpa PK, pencocokan by `no_register` (ambil 1 terbaru). Pastikan fallback saat baris pembayaran tidak ada (pakai data `form_permintaan_klinik.jumlah_biaya`).

### B. Modul Kesmas

| File | Perubahan |
|---|---|
| `application/controllers/kesmas/Form_permintaan_kesmas.php` | Guard saat construct: bila `kesmas_pembayaran` tidak punya kolom `status` → tambahkan via `dbforge` (enum('Belum Lunas','Lunas'), default 'Belum Lunas'). |
| `application/controllers/kesmas/Form_permintaan_kesmas.php` | Tambah `kuitansi($id)` → ambil `permintaan` + baris `kesmas_pembayaran WHERE no_registrasi=? LIMIT 1` → load view dengan `this->load->view()` + window.print (mirip `print($id)`). |
| `application/controllers/kesmas/Form_permintaan_kesmas.php` | Tambah `tandai_lunas($id)` → UPDATE `kesmas_pembayaran SET status='Lunas', tgl_pembayaran=CURDATE()/NOW()` → redirect ke detail. |
| `application/views/kesmas/permintaan/kuitansi.php` | Baru. Mirip style `print.php` (auto-print). Tampilkan No. Registrasi, Tanggal, Nama, Biaya, Metode, Status. |
| `application/views/klinik/form_permintaan_klinik/detail.php` → typo **seharusnya** `application/views/kesmas/permintaan/detail.php` (di tabel ditulis tepat, arahkan ke kesmas). |

Kesmas detail harusnya: tambah tombol "Cetak Kuitansi" dekat "Cetak Formulir". Tambah "Tandai Lunas" bila status Belum Lunas. Tampilkan status pembayaran (baca dari `kesmas_pembayaran.status`, fallback lihat `pendaftaran->jumlah_biaya`/keadaan).

Catatan: Tambah kolom `status` ke `kesmas_pembayaran`. Opsional: tambah `updated_at` untuk konsistensi.

### C. Opsional

| File | Perubahan |
|---|---|
| `application/helpers/report_helper.php` | Tambah `terbilang($angka)` (satu fungsi), ditaruh di helper yang sudah autoload. Gunakan di kedua view kuitansi. |

## 4. Titik Tombol & URL

| Modul | Controller | Method | URL Contoh | Lokasi Tombol |
|---|---|---|---|---|
| Klinik | `Form_permintaan_klinik` | `kuitansi/$id` | `klinik/form_permintaan_klinik/kuitansi/1` | `detail.php` (samping Cetak PDF) |
| Klinik | `Form_permintaan_klinik` | `tandai_lunas/$id` | `klinik/form_permintaan_klinik/tandai_lunas/1` | Di card pembayaran, tampil jika Belum Lunas |
| Kesmas | `Form_permintaan_kesmas` | `kuitansi/$id` | `k-panel/form_permintaan_kesmas/kuitansi/1` | `permintaan/detail.php` dekat "Cetak Formulir" |
| Kesmas | `Form_permintaan_kesmas` | `tandai_lunas/$id` | `k-panel/form_permintaan_kesmas/tandai_lunas/1` | Di blok pembayaran, tampil jika Belum Lunas |

## 5. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| `pembayaran` Klinik tanpa PK | Cocokkan via `no_register`, ambil terbaru (`ORDER BY updated_at DESC LIMIT 1`). |
| Baris pembayaran tidak ada | Fallback ke data form (jumlah_biaya, metode). Bisa insert/update saat `tandai_lunas`. |
| Perubahan DB (Kesmas) | Guard dbforge di `__construct()` (auto-migrasi ringan) atau SQL terpisah. |
| Duplikasi status | Baca status dari tabel pembayaran (bukan asumsi). |

## 6. Ceklist Verifikasi

- [ ] Klinik: Cetak Kuitansi muncul, PDF auto-print, data benar
- [ ] Klinik: Tandai Lunas — status berubah
- [ ] Kesmas: kolom `status` tersedia → fitur berfungsi
- [ ] Kesmas: Cetak Kuitansi & Tandai Lunas OK
- [ ] Tidak merusak cetak/formulir yang ada
