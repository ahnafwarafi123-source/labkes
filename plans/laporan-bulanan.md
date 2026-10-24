# Rencana Fitur: Buku Laporan Bulanan Kesmas (7 halaman + grafik)

Ditinjau: 2026-10-19
Status: **RENCANA — belum diimplementasi**

## 1. Latar belakang

Ada script lama `cetak_laporan.php` di root repo (warisan tim sebelumnya,
baseline `7d78bca`) yang mencetak buku laporan bulanan Kesmas 7 halaman +
grafik Chart.js. Script itu:

- **Standalone PDO**, tidak memakai `application/config/database.php`.
- **Tanpa login** dan disajikan langsung dari root web.
- Hanya cocok dengan **skema lama** (`kesmas_permintaan_item`, `kesmas_hasil`,
  `kesmas_permintaan.instansi`). Di DB aktif `lebkes_db` langsung gagal
  (`Table 'kesmas_hasil' doesn't exist`).

DB aktif aplikasi = `lebkes_db` (skema baru: `kesmas_item_permintaan`,
`kesmas_hasil_pengujian`, `kesmas_pelanggan`, `id_master_pemeriksaan`).
Karena itu buku laporan bulanan dipindahkan ke dalam aplikasi sebagai
controller baru, bukan mempertahankan script root.

Fitur laporan yang **sudah ada** dan tidak perlu dibuat ulang:
`k-panel/laporan` (cetak hasil per sampel), `k-panel/laporan/parameter`
(rekap MS/TMS bulanan), `k-panel/laporan_pelanggan` (laporan per pelanggan).
Yang belum ada = buku laporan bulanan agregat (jenis sampel, wilayah,
10 terbanyak, per instansi + grafik).

## 2. Keputusan yang sudah dikunci

| # | Keputusan | Nilai |
|---|---|---|
| 1 | Basis bulan laporan | `COALESCE(ps.tgl_pelaporan, ps.tgl_selesai, p.tgl_permintaan)` — konsisten dengan `Laporan_uji_kesmas::parameter()` |
| 2 | Cakupan data | Hanya pemeriksaan **yang sudah ada hasil** → wajib ada baris `kesmas_hasil_pengujian` (INNER JOIN) |
| 3 | Keluaran | Cetak browser A4 (`window.print()`) **dan** tombol PDF (mPDF) |
| 4 | Grafik | **SVG server-side** (bukan Chart.js) agar tampil di browser dan PDF, tanpa CDN (ramah offline/PWA) |
| 5 | Letak | Controller + view di dalam aplikasi, terautentikasi |

## 3. Data acuan (dicek di `lebkes_db`, 2026-10-19)

| Metrik | Nilai |
|---|---|
| Total permintaan | 11 |
| Sudah punya baris `kesmas_pengujian_sampel` | 9 |
| Sudah punya baris `kesmas_hasil_pengujian` (cakupan laporan) | 9 |
| Baris `kesmas_hasil_pengujian` | 75 |
| Distribusi `status` | MS=40, TMS=7, kosong=28 |

Catatan: `status` kosong (28 baris) **tidak** dihitung MS/TMS — sama seperti
`Laporan_uji_kesmas::parameter()`.

## 4. Query dasar (dipakai seluruh halaman)

```sql
kesmas_hasil_pengujian h
  JOIN kesmas_pengujian_sampel ps   ON h.id_pengujian = ps.id_pengujian
  JOIN kesmas_pembayaran b          ON b.id_pembayaran = ps.id_pembayaran
  JOIN kesmas_penerimaan_sampel r   ON r.no_registrasi = b.no_registrasi
  JOIN kesmas_permintaan p          ON p.id_permintaan = r.id_permintaan
  JOIN kesmas_master_pemeriksaan m  ON m.id_master_pemeriksaan = h.id_master_pemeriksaan
-- filter bulan:
--   COALESCE(ps.tgl_pelaporan, ps.tgl_selesai, p.tgl_permintaan) = 'YYYY-MM'
```

Alasan INNER JOIN: sampel yang belum ada hasil otomatis tersaring (sesuai
keputusan #2). Tidak perlu `LEFT JOIN` / logika exclude manual.

## 5. Isi per halaman

| Hal. | Isi | Sumber |
|---|---|---|
| 1 | Cover + Bab I Pendahuluan (teks statis, salin dari `cetak_laporan.php`) | statis |
| 2 | Jumlah sampel teruji per `jenis_sampel`, bulan ini vs bulan lalu | `COUNT(DISTINCT p.id_permintaan)` |
| 3 | Per `lokasi_pengambilan` × `jenis_sampel` (pivot) | `COUNT(DISTINCT p.id_permintaan)` |
| 4 | MS/TMS per `nama_pemeriksaan` — `status IN ('MS','TMS')` | group by `id_master_pemeriksaan` |
| 5 | 10 pemeriksaan terbanyak | `COUNT(h.id_master_pemeriksaan)` |
| 6 | Per kategori instansi (`kesmas_pelanggan.instansi`) | CASE PDAM / RS-Klinik / Restoran / Masyarakat |
| 7 | Bab V Penutup + kolom tanda tangan (statis) | statis |

## 6. File yang dibuat/diubah

| File | Aksi |
|---|---|
| `application/controllers/kesmas/Laporan_bulanan.php` | **baru** — class extends `MY_Controller`, `require_role(['admin','petugas'])`; method `cetak($bulan = null)`, `pdf($bulan = null)`, privat `_data($bulan)` |
| `application/helpers/laporan_chart_helper.php` | **baru** — `laporan_bar_svg()`, `laporan_pie_svg()` |
| `application/views/kesmas/laporan_bulanan/print.php` | **baru** — 7 halaman; grafik via helper SVG; logo `base_url('assets/img/logo.png')` |
| `application/views/klinik/layout/sidebar.php` | **+1 baris** menu "Lap. Bulanan" di grup Layanan Kesmas (setelah `k-panel/laporan_pelanggan`, sekitar baris 148) |

**Tidak diubah:** `cetak_laporan.php`, `database/*.sql`, `Kesmas_model.php`,
`Laporan_uji_kesmas.php`, helper/auth yang ada. Tidak ada migration DB
(tidak ada perubahan skema).

## 7. Interface

- **Route:** tanpa route eksplisit baru. Catch-all `k-panel/(.+)` →
  `kesmas/$1` (`application/config/routes.php:139`) sudah menangani
  `k-panel/laporan_bulanan/cetak/2026-05`.
- **URL:**
  - `k-panel/laporan_bulanan/cetak/{YYYY-MM}` — cetak browser (default: bulan lalu)
  - `k-panel/laporan_bulanan/pdf/{YYYY-MM}` — PDF via `Pdf::generate_view()`
- **Menu:** sidebar grup Layanan Kesmas (pola sama dengan baris 134-148).

## 8. Security

- Controller extend `MY_Controller` → login + session validation + cross-module
  write-gate otomatis (`core/MY_Controller.php:36-50`).
- `require_role(['admin','petugas'])`.
- Hanya GET (baca) → tidak kena write-gate.
- Tidak ada input user yang masuk ke SQL selain `$bulan` (divalidasi format
  `YYYY-MM` via regex, lalu dipakai sebagai parameter bind / escaping).

## 9. Urutan implementasi

1. `application/helpers/laporan_chart_helper.php` (SVG bar + pie)
2. `application/controllers/kesmas/Laporan_bulanan.php` (query + `_data`)
3. `application/views/kesmas/laporan_bulanan/print.php` (7 halaman)
4. `application/views/klinik/layout/sidebar.php` (+1 menu)
5. Verifikasi

Versi paling kecil tanpa halaman form pilih bulan: URL langsung, default bulan
lalu. Bisa ditambah halaman form nanti bila perlu.

## 10. Verifikasi (repo ini tidak punya test suite/CI)

1. `php -l` pada 3 file PHP baru.
2. Buka `k-panel/laporan_bulanan/cetak/2026-05` sebagai admin → tabel & grafik
   tampil.
3. Bandingkan Halaman 4 dengan
   `k-panel/laporan/parameter?dari=2026-05&sampai=2026-05` → angka MS/TMS harus
   identik.
4. Klik tombol PDF → PDF terbuka, grafik ikut muncul.
5. Klik "Cetak" browser → 7 halaman A4, page-break benar.
6. Cek sampel yang belum diuji (id_permintaan 11, 2) **tidak** muncul.

## 11. Risiko

| Risiko | Mitigasi |
|---|---|
| mPDF gagal merender SVG | Fallback: render grafik jadi PNG via GD (GD aktif), tetap tanpa JS |
| `jenis_sampel` kini teks bebas (`"Makanan ( Nasi Kuning )"`, `"Angka Kuman Udara Ruang OK 4"`) → H.2/H.3 banyak kategori acak | Dibiarkan apa adanya (jujur). Normalisasi ke 4 kelompok = pekerjaan terpisah bila diperlukan |
| `status` MS/TMS banyak kosong (28/75) → H.4 lebih kecil dari total diuji | Disengaja; sama dengan `parameter`. Bila perlu hitung otomatis dari baku mutu, itu keputusan terpisah |
| Grafik SVG manual lebih banyak kode daripada `Chart.js` | Trade-off diterima demi PDF + offline (satu jalur render) |

## 12. Open Questions

- Perlu halaman form pilih bulan, atau cukup URL + default bulan lalu?
- Kop laporan memakai logo instansi mana (`assets/img/logo.png` sudah dipakai
  `cetak_laporan.php`)?
- Perlu tanda tangan digital/scan pejabat, atau tetap garis + NIP kosong?

## 13. Completion Criteria

- Buku laporan bulanan terbuka di `k-panel/laporan_bulanan/cetak/{bulan}`
  sebagai user login, memuat 7 halaman + grafik.
- Tombol PDF menghasilkan PDF yang memuat grafik.
- H.4 identik dengan `k-panel/laporan/parameter` pada bulan yang sama.
- Hanya pemeriksaan yang sudah ada hasil yang ikut terhitung.
- `php -l` bersih; alur lama tidak berubah; tanpa commit kecuali diminta.
