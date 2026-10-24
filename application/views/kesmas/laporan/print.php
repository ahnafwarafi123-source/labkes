<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $is_pdf = !empty($is_pdf); ?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Laporan Hasil Uji</title>
  <style>
    <?php if (!$is_pdf): // mPDF salah mengurai "size: A4 portrait"; ukuran & margin PDF diatur lewat config mPDF
    ?>@page {
      size: 215mm 330mm;
      margin: 15mm;
    }

    <?php endif; ?>* {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10pt;
      color: #000;
      line-height: 1.3;
    }

    .kop-wrap {
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .logo {
      width: 60px;
      height: 60px;
      object-fit: contain;
      margin-left: 8mm;
    }

    .logo-kanan {
      width: 60px;
      height: 60px;
      object-fit: contain;
      margin-right: 8mm;
    }

    .kop {
      flex: 1;
      text-align: center;
      line-height: 1.2;
    }

    .kop .l1,
    .kop .l2 {
      font-weight: bold;
      font-size: 12pt;
    }

    .kop .l3 {
      font-size: 10pt;
      line-height: 1.1;
    }

    .line {
      border-top: 2px solid #000;
      margin: 4px 0 8px;
    }

    .judul {
      text-align: center;
      font-weight: bold;
      text-decoration: underline;
      margin: 8px 0 12px;
      font-size: 12pt;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    table.info {
      margin-bottom: 12px;
    }

    .info td {
      padding: 2px 4px;
      vertical-align: top;
    }

    .info .lbl {
      white-space: nowrap;
      vertical-align: top;
    }

    .info .lbl2 {
      white-space: nowrap;
      vertical-align: top;
    }

    .info .titik-dua {
      width: 10px;
      padding-right: 0px;
      padding-left: 0px;
      white-space: nowrap;
      vertical-align: top;
    }

    /* =========================
       WATERMARK KHUSUS KOLOM BAKU MUTU
       ========================= */
    .hasil-wrapper {
      position: relative;
      /* overflow: hidden; */
      /* aman */
    }

    .hasil-wrapper::before {
      content: "";
      position: absolute;
      top: 0;
      bottom: 0;

      /* MELIPUTI SELURUH TABEL */
      left: 0;
      width: 100%;

      background-image: url("<?= base_url('assets/img/background.png') ?>");
      background-repeat: no-repeat;
      background-position: center center;

      /* UTUH, tidak distorsi, di dalam kontainer */
      background-size: contain;

      opacity: 0.15;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      pointer-events: none;
    }

    /* kunci lebar kolom persen */
    table.hasil {
      table-layout: fixed;
    }

    .hasil {
      position: relative;
      background-color: transparent;
    }

    .hasil th,
    .hasil td {
      border: 1px solid #000;
      padding: 4px 6px;
      background-color: transparent !important;
      overflow-wrap: break-word;
    }

    .hasil th {
      text-align: center;
      font-weight: bold;
    }

    .section td {
      font-weight: bold;
      background: rgba(242, 242, 242, 0.7) !important;
    }

    .center {
      text-align: center;
    }

    /* .ket {
      font-size: 10px;
      margin-top: 4px;
      line-height: 1.1;
    } */

    .ket {
      display: block;
      margin-top: 8px;
      font-size: 10pt;
      line-height: 1.3;
    }

    .ket-label {
      display: block;
      font-weight: bold;
      margin-bottom: 2px;
    }

    .ket-content {
      display: block;
      margin-left: 0px;
    }

    .ket-item {
      display: grid;
      grid-template-columns: 22px 1fr;
      column-gap: 0;
      line-height: 1.3;
    }

    .ket-number {
      text-align: left;
    }

    .ket-text {
      text-align: left;
    }

    /* proporsi tabel */
    .w-no {
      width: 4%;
    }

    .w-satuan {
      width: 9%;
    }

    .w-baku {
      width: 15%;
    }

    .w-metode {
      width: 16%;
    }

    .w-hasil {
      width: 17%;
    }
  </style>
</head>

<body<?= $is_pdf ? '' : ' onload="window.print()"' ?>>
  <?php if ($is_pdf && is_file(FCPATH . 'assets/img/background.png')): ?>
    <watermarkimage src="<?= FCPATH . 'assets/img/background.png' ?>" alpha="0.1" size="150,150" position="P" />
  <?php endif; ?>

  <?php
  // Format tanggal Indonesia
  if (!function_exists('format_indo')) {
    function format_indo($datetime_string, $format = 'tanggal')
    {
      if (empty($datetime_string) || !strtotime($datetime_string)) return '-';
      $timestamp = strtotime($datetime_string);
      $bulan_indo = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      ];
      $hari  = date('d', $timestamp);
      $bulan = $bulan_indo[(int)date('m', $timestamp)];
      $tahun = date('Y', $timestamp);

      if ($format === 'tanggal_jam') {
        $jam = date('H.i', $timestamp);
        return "$hari $bulan $tahun / $jam WIB";
      }
      return "$hari $bulan $tahun";
    }
  }

  // Normalisasi nilai agar '-' / kosong tampil rapi
  if (!function_exists('val_or_dash')) {
    function val_or_dash($v)
    {
      $s = trim((string)$v);
      if ($s === '' || $s === '-' || strtolower($s) === 'null') return '-';
      return $s;
    }
  }

  // Data dari controller
  $no_reg        = $permintaan['no_registrasi'] ?? '-';
  $nama_sampel   = $permintaan['nama_sampel'] ?? '-';
  $kategori_sample = $permintaan['kategori_sampel'] ?? '-';
  $jenis_sampel  = $permintaan['jenis_sampel'] ?? '-';
  $lokasi        = $permintaan['lokasi_pengambilan'] ?? '-';
  $nama_pengirim = $permintaan['nama'] ?? '-';
  $tgl_pengiriman = $permintaan['tgl_permintaan'] ?? null;
  $kondisi       = $permintaan['status_kelayakan'] ?? '-';

  $petugas_pengambil   = $petugas_pengambil_spesimen_nama ?? '-';
  $tgl_jam_pengambilan = $tgl_jam_pengambilan_display ?? null;
  $tgl_jam_pemeriksaan = $tgl_jam_pemeriksaan_display ?? null;
  $tgl_jam_selesai     = $tgl_jam_selesai_display ?? null;

  // kanan bawah: pakai tgl lapor, fallback tgl selesai, fallback hari ini
  $tgl_kanan = $tgl_jam_lapor_display ?? ($tgl_jam_selesai_display ?? date('Y-m-d'));

  // kategori sampel untuk prefix helper get_kelompok_prefix (mengikuti detail)
  $kategori_sampel = strtolower(str_replace(' ', '_', $permintaan['jenis_sampel'] ?? ''));

  // (tetap dipakai kalau Anda butuh variabel ini di tempat lain)
  $bg_size = 'auto 100%';

  // // Normalisasi nama kelompok untuk tampilan
  // if (!function_exists('normalize_kelompok')) {
  //   function normalize_kelompok($kelompok)
  //   {
  //     $map = [
  //       'Fisika'       => 'FISIK',
  //       'FISIKA'       => 'FISIK',
  //       'Bakteriologi' => 'MIKROBIOLOGI',
  //       'BAKTERIOLOGI' => 'MIKROBIOLOGI',
  //     ];
  //     return $map[$kelompok] ?? strtoupper($kelompok);
  //   }
  // }
  // Normalisasi nama kelompok untuk tampilan menggunakan helper global
  // normalize_kelompok sudah didefinisikan di report_helper.php
  ?>

  <!-- KOP -->
  <?php if ($is_pdf): ?>
    <!-- mPDF tidak mendukung flex, kop dirender sebagai tabel -->
    <table style="width:100%;">
      <tr>
        <td style="width:22mm; vertical-align:top; padding-left:8mm;">
          <?php if (is_file(FCPATH . 'assets/img/logo.png')): ?><img src="<?= FCPATH . 'assets/img/logo.png' ?>" style="width:60px; height:60px;"><?php endif; ?>
        </td>
        <td class="kop" style="text-align:center; vertical-align:top;">
          <div style="font-weight:bold; font-size:12pt;">PEMERINTAH KOTA PANGKAL PINANG</div>
          <div style="font-weight:bold; font-size:12pt;">DINAS KESEHATAN</div>
          <div style="font-weight:bold; font-size:12pt;">UPTD. LABORATORIUM KESEHATAN</div>
          <div style="font-size:10pt; line-height:1.1;">
            Jalan Delima Siam II Kecamatan Girimaya, Telepon (0717) 9120759<br>
            Pos el : upt-labkesehatan@pangkalpinangkota.go.id Pangkal Pinang 33143
          </div>
        </td>
        <td style="width:22mm; vertical-align:top; text-align:right; padding-right:8mm;">
          <?php if (is_file(FCPATH . 'assets/img/logokanan.jpeg')): ?><img src="<?= FCPATH . 'assets/img/logokanan.jpeg' ?>" style="width:60px; height:60px;"><?php endif; ?>
        </td>
      </tr>
    </table>
  <?php else: ?>
    <div class="kop-wrap">
      <img class="logo" src="<?= base_url('assets/img/logo.png?v=' . time()) ?>" alt="logo" onerror="this.style.display='none';">
      <div class="kop">
        <div class="l1 upper">PEMERINTAH KOTA PANGKAL PINANG</div>
        <div class="l1">DINAS KESEHATAN</div>
        <div class="l2">UPTD. LABORATORIUM KESEHATAN</div>
        <div class="l3">
          Jalan Delima Siam II Kecamatan Girimaya, Telepon (0717) 9120759<br>
          Pos el : upt-labkesehatan@pangkalpinangkota.go.id Pangkal Pinang 33143
        </div>
      </div>
      <img class="logo-kanan" src="<?= base_url('assets/img/logokanan.jpeg?v=' . time()) ?>" alt="logo kanan" onerror="this.style.display='none';">
    </div>
  <?php endif; ?>
  <div class="line"></div>

  <div class="judul">LAPORAN HASIL UJI</div>

  <!-- IDENTITAS -->
  <table class="info">
    <tr>
      <td style="width:50%; vertical-align:top;">
        <table class="info">
          <tr>
            <td class="lbl">No. Registrasi</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($no_reg) ?></td>
          </tr>
          <tr>
            <td class="lbl">Nama Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($nama_sampel) ?></td>
          </tr>
          <tr>
            <td class="lbl">Alamat</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($lokasi) ?></td>
          </tr>
          <tr>
            <td class="lbl">Kategori Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($kategori_sample) ?></td>
          </tr>
          <tr>
            <td class="lbl">Jenis Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($jenis_sampel) ?></td>
          </tr>
          <tr>
            <td class="lbl">Nama Pengirim</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($nama_pengirim) ?></td>
          </tr>
          <tr>
            <td class="lbl">Tanggal Pengiriman</td>
            <td class="titik-dua">:</td>
            <td><?= format_indo($tgl_pengiriman, 'tanggal') ?></td>
          </tr>
        </table>
      </td>
      <td style="width:50%; vertical-align:top;">
        <table class="info">
          <tr>
            <td class="lbl2">Petugas Pengambil Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($petugas_pengambil) ?></td>
          </tr>
          <tr>
            <td class="lbl2">Tgl / Jam Pengambilan Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= format_indo($tgl_jam_pengambilan, 'tanggal_jam') ?></td>
          </tr>
          <tr>
            <td class="lbl2">Tgl / Jam Pemeriksaan</td>
            <td class="titik-dua">:</td>
            <td><?= format_indo($tgl_jam_pemeriksaan, 'tanggal_jam') ?></td>
          </tr>
          <tr>
            <td class="lbl2">Tgl / Jam Selesai</td>
            <td class="titik-dua">:</td>
            <td><?= format_indo($tgl_jam_selesai, 'tanggal_jam') ?></td>
          </tr>
          <tr>
            <td class="lbl2">Kondisi Sampel</td>
            <td class="titik-dua">:</td>
            <td><?= htmlspecialchars($kondisi) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- TABEL HASIL -->
  <div class="hasil-wrapper">
    <table class="hasil">
      <!-- kunci lebar kolom agar watermark tepat -->
      <colgroup>
        <col style="width:6%">
        <col style="width:36%">
        <col style="width:10%">
        <col style="width:15%">
        <col style="width:17%">
        <col style="width:16%">
      </colgroup>

      <thead>
        <tr>
          <th class="w-no">No.</th>
          <th>JENIS PEMERIKSAAN</th>
          <th class="w-satuan">SATUAN</th>
          <th class="w-baku">BAKU MUTU</th>
          <th class="w-metode">METODE PEMERIKSAAN</th>
          <th class="w-hasil">HASIL PEMERIKSAAN</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($grouped_items) && is_array($grouped_items)): ?>
          <?php foreach ($grouped_items as $kelompok => $items_in_group): ?>
            <?php $item_no = 1; // Reset number for each group 
            ?>
            <?php $no = 1; ?>
            <tr class="section">
              <td colspan="6"><?= htmlspecialchars(normalize_kelompok($kelompok)) ?></td>
            </tr>

            <?php foreach ($items_in_group as $it): ?>
              <tr data-id-permintaan-item="<?= (int)($it['id_permintaan_item'] ?? 0) ?>">
                <td class="center"><?= $no++ ?></td>
                <td><?= e_html($it['nama_pemeriksaan']) ?></td>
                <td class="center satuan-cell"><?= e_html($it['satuan']) ?></td>
                <td class="center baku-cell"><?= e_html(!empty($it['baku_mutu_override']) ? $it['baku_mutu_override'] : $it['baku_mutu']) ?></td>
                <td class="center metode-cell"><?= e_html(!empty($it['metode_override']) ? $it['metode_override'] : $it['metode']) ?></td>
                <td class="center fw-bold text-nowrap"><?= e_html($it['hasil']) ?></td>
              </tr>
            <?php endforeach; ?>

          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="center">Data pemeriksaan belum tersedia.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- KETERANGAN (catatan kaki dinamis dari form cetak) -->
  <div class="ket">
    <b class="ket-label">Ket :</b>

    <?php if (!empty($keterangan_cetak)): ?>
      <div class="ket-content">
        <?php
        $keterangan_lines = preg_split(
          '/\r\n|\r|\n/',
          trim($keterangan_cetak)
        );

        foreach ($keterangan_lines as $line):
          $line = trim($line);

          if ($line === '') {
            continue;
          }

          // Jika sudah memiliki nomor: 1. ..., 2. ..., dst.
          if (preg_match('/^(\d+)\.\s*(.*)$/', $line, $matches)) {
            $nomor = $matches[1] . '.';
            $isi   = $matches[2];
            $nomor_otomatis = (int) $matches[1];
          } else {
            // Jika belum memiliki nomor, nomor akan dibuat otomatis
            $nomor = ($nomor_otomatis ?? 0) + 1;
            $nomor_otomatis = $nomor;
            $nomor = $nomor . '.';
            $isi   = $line;
          }
        ?>

          <div class="ket-item">
            <span class="ket-number"><?= htmlspecialchars($nomor) ?></span>
            <span class="ket-text"><?= htmlspecialchars($isi) ?></span>
          </div>

        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- TTD -->
  <div style="margin-top: 20px;">
    <!-- KIRI: Petugas & Verifikator -->
    <div style="width: 50%; float: left;">
      <table style="border: none; width: 100%; font-size: 10pt; border-collapse: collapse;">
        <tr>
          <td style="width: 75px; vertical-align: top; padding: 4px 0;">Petugas</td>
          <td style="vertical-align: top; padding: 4px 0;">
            <span style="display: inline-block; min-width: 220px; border-bottom: 1px solid #000; padding-bottom: 2px; font-weight: bold;">
              <?= isset($petugas_pemeriksa['nama']) && $petugas_pemeriksa['nama'] ? htmlspecialchars($petugas_pemeriksa['nama']) : '&nbsp;' ?>
            </span>
          </td>
        </tr>
        <tr>
          <td style="height: 14px;"></td>
          <td></td>
        </tr>
        <tr>
          <td style="width: 75px; vertical-align: top; padding: 4px 0;">Verifikator</td>
          <td style="vertical-align: top; padding: 4px 0;">
            <span style="display: inline-block; min-width: 220px; border-bottom: 1px solid #000; padding-bottom: 2px; font-weight: bold;">
              <?= isset($verifikator['nama']) && $verifikator['nama'] ? htmlspecialchars($verifikator['nama']) : '&nbsp;' ?>
            </span>
          </td>
        </tr>
      </table>
    </div>

    <!-- Menjamin Penanggung Jawab berada di bawah Verifikator -->
    <div style="clear: both;"></div>

    <!-- KANAN: Penanggung Jawab -->
    <div style="width: 55%; float: right; text-align: center; line-height: 1.5; margin-top: 20px;">
      Pangkal Pinang, <?= format_indo($tgl_kanan, 'tanggal') ?><br>
      <b>PENANGGUNG JAWAB TEKNIS LABORATORIUM KESMAS</b><br>
      UPTD. LABKES KOTA PANGKAL PINANG
      <br><br><br><br><br><br>
      <b><u><?= isset($penanggung_jawab_teknis['nama']) ? htmlspecialchars($penanggung_jawab_teknis['nama']) : '____________________' ?></u></b><br>
      NIP. <?= isset($penanggung_jawab_teknis['nip']) ? htmlspecialchars($penanggung_jawab_teknis['nip']) : '____________________' ?>
    </div>

    <div style="clear: both;"></div>
  </div>

  </body>

</html>